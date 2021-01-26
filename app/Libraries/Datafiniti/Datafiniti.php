<?php


namespace App\Libraries\Datafiniti;

use Illuminate\Support\Facades\Log;

class Datafiniti
{

    protected $productSearchApi = "https://api.datafiniti.co/v4/products/search";

    protected $downloadApi = "https://api.datafiniti.co/v4/downloads/";

    public $query;

    public $output;

    public $num_records = 10;

    public $download = false;

    protected $apiKey = "your-api-key";

    protected $defaultRequestType = "POST";

    protected $outputFormat = 'JSON';

    protected $outputFormats = ['JSON', 'csv'];

    protected $defaultQuery = "keys:*";

    protected $fields = array();

    protected $fieldQuery = "";

    protected $searchTerm = "";

    protected $manufacturer = "";

    protected $primaryCategories = "";

    protected $sourceURLs = "";

    protected $dateUpdated = "";

    protected $QCO = " AND "; //query combine operator

    protected $SUS = " OR "; //source url separator operator

    protected $options = array();

    protected $requestBody;

    protected $header;

    protected $downloadHeader;

    protected $downloadRequestType = 'GET';

    protected $downloadId;

    protected $downloadOutput = null;

    protected $remoteFileUrl = null;


    /**
     * Datafiniti constructor.
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    public function search(): void
    {
        self::resolveOption();
        self::resolveFields();
        self::makeQuery();
        self::setRequestBody();
        self::setHeader();
        self::makeRequest();
        self::checkDownload();
    }


    protected function checkDownload(): void
    {
        if ($this->download) {
            $temp = json_decode($this->output);
            $this->downloadId = get_property($temp, 'id');
            unset($temp);
            $this->getDownload();
        }
    }

    protected function getDownload(): void
    {
        if (!empty($this->downloadId) || !is_null($this->downloadId)) {
            $this->setDownloadHeader();
            $this->makeDownloadRequest();
            $this->generateDownloadResult();
        }
    }

    protected function makeDownloadRequest(): void
    {
        try {

            $context = stream_context_create($this->downloadHeader);
            $downloadStatus = "running";
            $downloadResponse = null;

            while ($downloadStatus != 'completed') {
                sleep(10);
                $downloadResponse = file_get_contents($this->downloadApi . $this->downloadId, false, $context);
                $downloadResponseArr = json_decode($downloadResponse);
                $downloadStatus = $downloadResponseArr->status;
            }

            if ($downloadStatus == 'completed') {
                $this->downloadOutput = $downloadResponse;
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    protected function generateDownloadResult(): void
    {
        if (!is_null($this->downloadOutput)) {
            $this->downloadOutput = json_decode($this->downloadOutput);

            $urls = get_property($this->downloadOutput, 'results');

            if (is_array($urls) && !empty($urls)) {
                $this->remoteFileUrl = $urls[0];
            }

            $records = $this->readDownloadedFile();

            $this->output = json_encode(array(
                'num_found' => get_property($this->downloadOutput, 'num_records'),
                'records'   => $records,
            ));
        }
    }

    /**
     * @return array
     */
    protected function readDownloadedFile()
    {
        $records = array();

        if (is_null($this->remoteFileUrl)) {
            return $records;
        }

        if (!$stream = @fopen($this->remoteFileUrl, 'r')) {
            return $records;
        }

        //create temporary file
        $temporaryFile = tempnam(sys_get_temp_dir(), 'datafiniti-txt');
        //write result to temporary file
        file_put_contents($temporaryFile, $stream);

        if (!$file = @fopen($temporaryFile, 'r')) {
            return $records;
        }

        while (!feof($file)) {
            $records[] = json_decode(fgets($file));
        }
        fclose($stream);
        fclose($file);

        return $records;
    }


    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * parse and set options
     */
    protected function resolveOption(): void
    {
        if (array_key_exists('fields', $this->options))
            self::setFields($this->options['fields']);

        if (array_key_exists('search_term', $this->options))
            self::setSearchTerm($this->options['search_term']);

        if (array_key_exists('sources', $this->options))
            self::setSourceURLs($this->options['sources']);

        if (array_key_exists('manufacturer', $this->options))
            self::setManufacturer($this->options['manufacturer']);

        if (array_key_exists('primaryCategory', $this->options))
            self::setPrimaryCategory($this->options['primaryCategory']);

        if (array_key_exists('output_format', $this->options))
            self::setOutputFormat($this->options['output_format']);

        if (array_key_exists('dateUpdated', $this->options))
            self::setDateUpdated($this->options['dateUpdated']);

        if (array_key_exists('num_records', $this->options))
            self::setNumberOfRecords($this->options['num_records']);

        if (array_key_exists('download', $this->options))
            self::setDownload($this->options['download']);

    }

    /**
     * parse and set fields
     */
    protected function resolveFields()
    {
        $this->fieldQuery = implode($this->QCO, array_filter($this->fields));
    }

    /**
     * @param $fields
     */
    protected function setFields($fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param $term
     */
    protected function setSearchTerm($term): void
    {
        if (!is_null($term)) {
            $this->searchTerm = 'name:("' . $term . '")';

            // unset name from fields
            $this->unsetNameField();
        }
    }

    /**
     * @param $category
     */
    protected function setPrimaryCategory($category): void
    {
        if (!is_null($category)) {
            $this->primaryCategories = 'primaryCategories:("' . self::getCategories()[$category] . '")';
            //unset primary category from field
            $this->unsetPrimaryCategory();
        }
    }

    /**
     * @param $date
     */
    protected function setDateUpdated($date): void
    {
        if (!is_null($date)) {
            $this->dateUpdated = "dateUpdated:[{$date} TO *]";
        }
    }

    /**
     * @param $term
     */
    protected function setManufacturer($term): void
    {
        if (!is_null($term)) {
            $this->manufacturer = '(manufacturer:("' . $term . '") OR brand:("' . $term . '"))';

            //unset brand and manufacturer
            $this->unsetManufacturer();
            $this->unsetBrand();
        }
    }

    /**
     * @param $urls
     */
    protected function setSourceURLs($urls): void
    {
        if (!is_null($urls) && !empty($urls) && is_array($urls)) {
            $this->sourceURLs = "(sourceURLs:(";
            $this->sourceURLs .= implode($this->SUS, array_filter($urls));
            $this->sourceURLs .= ")";
            $this->sourceURLs .= $this->SUS. "domains:(";
            $this->sourceURLs .= implode($this->SUS, array_filter($urls));
            $this->sourceURLs .= "))";

            //unset sourceUrl from fields
            $this->unsetSourceUrl();
        }
    }

    /**
     * @param $format
     */
    protected function setOutputFormat($format): void
    {
        if (in_array($format, $this->outputFormats)) {
            $this->outputFormat = $format;
        }
    }

    /**
     * @param $num
     */
    protected function setNumberOfRecords($num): void
    {
        if (!is_null($num) && (int)$num != 0) {
            $this->num_records = (int)$num;
        }
    }

    protected function setDownload($val): void
    {
        if (is_bool($val)) {
            $this->download = $val;
        }
    }

    protected function unsetNameField(): void
    {
        $key = array_search('name:*', $this->fields);
        if (is_integer($key)) {
            unset($this->fields[$key]);
        }
    }

    protected function unsetSourceUrl(): void
    {
        $key = array_search('sourceURLs:*', $this->fields);
        if (is_integer($key)) {
            unset($this->fields[$key]);
        }
    }

    protected function unsetManufacturer(): void
    {
        if ($this->manufacturer != "") {
            $key = array_search('manufacturer:*', $this->fields);
            if (is_integer($key)) {
                unset($this->fields[$key]);
            }
        }
    }

    protected function unsetBrand(): void
    {
        $key = array_search('brand:*', $this->fields);
        if (is_integer($key)) {
            unset($this->fields[$key]);
        }
    }

    protected function unsetPrimaryCategory(): void
    {
        $key = array_search('primaryCategories:*', $this->fields);
        if (is_integer($key)) {
            unset($this->fields[$key]);
        }
    }

    protected function makeQuery(): void
    {
        if (empty($this->fields) && empty($this->searchTerm) && empty($this->sourceURLs) && empty($this->dateUpdated) && empty($this->manufacturer)) {
            $this->query = $this->defaultQuery;
        } else {
            $this->query = implode($this->QCO, array_filter(array($this->fieldQuery, $this->searchTerm, $this->sourceURLs, $this->dateUpdated, $this->primaryCategories, $this->manufacturer)));
        }
    }

    /**
     * @return array
     */
    public static function getSources(): array
    {
        return array(
            'amazon.com'  => 'amazon.com',
            'ebay.com'    => 'ebay.com',
            'bestbuy.com' => 'bestbuy.com',
            'newegg.com'  => 'newegg.com',
            'walmart.com' => 'walmart.com',
        );
    }

    /**
     * @return array
     */
    public static function getCategories(): array
    {
        return array(
            'Animals & Pet Supplies',
            'Apparel & Accessories',
            'Arts & Entertainment',
            'Baby & Toddler',
            'Business & Industrial',
            'Electronics',
            'Food Beverages & Tobacco',
            'Furniture',
            'Hardware',
            'Health & Beauty',
            'Home & Garden',
            'Luggage & Bags',
            'Media',
            'Office Supplies',
            'Shoes',
            'Software',
            'Sporting Goods',
            'Toys & Games',
            'Vehicles & Parts',
        );
    }

    protected function setRequestBody(): void
    {
        $this->requestBody = array(
            'query'       => $this->query,
            'format'      => $this->outputFormat,
            'num_records' => $this->num_records,
            'download'    => $this->download,
        );
    }

    /**
     * @return void
     */
    protected function setHeader(): void
    {
        $this->header = array(
            'http' => array(
                'header'  => "Authorization: Bearer " . $this->apiKey . "\r\n" . "Content-Type: application/json\r\n",
                'method'  => $this->defaultRequestType,
                'content' => json_encode($this->requestBody)
            )
        );
    }

    protected function setDownloadHeader(): void
    {
        $this->downloadHeader = array(
            'http' => array(
                'method' => $this->downloadRequestType,
                'header' => "Authorization: Bearer " . $this->apiKey . "\r\n" . "Content-Type: application/json\r\n",
            )
        );
    }

    protected function makeRequest(): void
    {
        $context = stream_context_create($this->header);
        try {
            $this->output = file_get_contents($this->productSearchApi, false, $context);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->output = null;
        }
    }
}
