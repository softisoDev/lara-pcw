<?php

namespace App\Console\Commands;

use App\Libraries\PriceParser;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Source;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PriceUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-price {domains?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product price updater';

    private $domains = [];

    protected $logDir;

    protected $logFileName = 'unresolved_1960-01-01.json';

    protected $error = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->logDir = env('PRICE_PARSER_LOG_DIR', 'unresolved_urls') . DIRECTORY_SEPARATOR;
        $this->logFileName = 'unresolved_' . date('Y-m-d') . '.json';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->domains = $this->argument('domains');

        $checkInputDomains = self::checkUserInputDomains();

        if (!$checkInputDomains) {
            $this->error($this->error['message']);
            return false;
        }

        $ids = self::getDomainsIDs();

        $products = ProductVariation::query()->withoutGlobalScopes()->whereIn('source_id', $ids)->orderByDesc('updated_at')->get();

        $parser = new PriceParser();

        foreach ($products as $product) {

            $url = self::checkUrl($product->source_url);

            $parser->createContent($url);

            sleep(60);

            $content = $parser->getContent($url);

            $isJson = isJSON($content);

            if (is_null($content) || empty($content) || $isJson) {
                $parser->forgetUrl($url);
                continue;
            }

            $parser->forgetUrl($url);

            $output = $parser->extract($content, $product->source_url);

            $update = self::checkAndUpdate($output, $product);

            if ($update) {
                echo sprintf("Message: %s, vID: %d \n\r", 'Success', $product->id);
                self::forgetAllCache();
            } else {
                $response = [
                    'product_id'   => $product->product_id,
                    'variation_id' => $product->id,
                    'url'          => $product->source_url,
                    'error_info'   => $this->error,
                    'date'         => date('Y-m-d'),
                ];
                self::forgetAllCache();
                self::logInfo($response);

                echo sprintf("Message: %s \n\r", $this->error['message']);
            }
        }

        self::forgetAllCache();

        $this->info('Links are updated successfully');
    }

    /**
     * @param $output
     * @param $product
     * @return bool
     */
    protected function checkAndUpdate($output, $product)
    {
        if (is_array($output) && !array_key_exists('success', $output)) {
            self::makeError('Output is null');
            return false;
        }

        if (!$output['success']) {
            self::makeError($output['message'], $output['error_code']);
            return false;
        }

        $data = $output['data'];

        $checkData = self::checkData($data);

        if (!$checkData) {
            return false;
        }

        $product->current_price = $data['price'];
        $product->currency = $data['currency'];

        try {
            $product->save();
            return true;
        } catch (\Exception $e) {
            self::makeError("Product can't be updated");
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    protected function checkData($data)
    {
        if (empty($data)) {
            self::makeError('Response data is empty');
            return false;
        }

        if (is_null($data['currency']) || empty($data['currency'])) {
            self::makeError('Currency is null');
            return false;
        }

        if (is_null($data['price']) || empty($data['price'])) {
            self::makeError('Price is null');
            return false;
        }

        return true;
    }

    /**
     * @param $outPut
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function logInfo($outPut)
    {
        $exist = Storage::disk('public')->exists($this->logDir . $this->logFileName);

        if (!$exist) {
            Storage::disk('public')->put($this->logDir . $this->logFileName, json_encode(array($outPut), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            return true;
        }

        $previousData = Storage::disk('public')->get($this->logDir . $this->logFileName);
        $previousData = json_decode($previousData, true);
        $previousData[] = $outPut;

        Storage::disk('public')->put($this->logDir . $this->logFileName, json_encode($previousData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    protected function forgetAllCache()
    {
        refreshHomePageCache();
        Product::forgetOne(Product::REMOVABLE_CACHE_NAME['all_products']);
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }

    protected function getDomainsIDs()
    {
        if (empty($this->domains)) {
            $this->domains = self::getSupportedDomains();
        }

        $sources = Source::query()->withoutGlobalScopes();

        foreach ($this->domains as $domain) {
            $sources->orWhereLike('name', $domain);
        }

        return $sources->pluck('id');
    }

    protected function getSupportedDomains()
    {
        return (new PriceParser())->getSupportedDomain();
    }

    protected function checkUserInputDomains()
    {
        $supportedDomains = self::getSupportedDomains();

        $diffArr = array_diff($this->domains, $supportedDomains);

        if (!empty($diffArr)) {
            self::makeError(implode(', ', $diffArr) . ' domain(s) not supported');
            return false;
        }

        return true;
    }

    /**
     * @param $url
     * @return string
     */
    protected function checkUrl($url)
    {
        $mustBeEncodedUrls = [
            'bestbuy.com',
            'bestbuy'
        ];

        $extractDomain = extractDomain($url);

        if (!$extractDomain) {
            return $url;
        }

        if (!in_array($extractDomain[1], $mustBeEncodedUrls)) {
            return $url;
        }

        return urlencode($url);

    }

    /**
     * @param $message
     * @param null $code
     */
    protected function makeError($message, $code = null)
    {
        $this->error = [
            'message' => $message,
            'code'    => $code,
        ];
    }
}
