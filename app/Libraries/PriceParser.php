<?php


namespace App\Libraries;


class PriceParser
{
    protected $host = "http://api.gooanalytics.com/grab/";

    protected $parserConfig = [];

    protected $error = [];

    const ERROR_CODES = [
        0 => 'Domain not found in config',
        1 => 'Appropriate pattern not found',
        2 => "Domain cant'be extracted",
        3 => "Currency can't be extracted",
        4 => "Price can't be extracted",
    ];

    public function __construct()
    {
        $this->parserConfig = self::getConfig();
    }

    /**
     * @param string $url
     * @return false|string
     */
    public function createContent(string $url)
    {
        return file_get_contents($this->host . 'create/?url=' . $url);
    }

    /**
     * @param string $url
     * @return false|string
     */
    public function getContent(string $url)
    {
        return file_get_contents($this->host . 'get/?url=' . $url);
    }

    /**
     * @param string $url
     */
    public function forgetUrl(string $url)
    {
        file_get_contents($this->host . 'remove/?url=' . $url);
    }

    /**
     * @param $content
     * @param $url
     * @return array|null
     */
    public function extract($content, $url)
    {
        $patterns = $this->getPatterns($url);

        if (is_null($patterns)) {
            return $this->error;
        }

        foreach ($patterns as $pattern) {
            $check = preg_match($pattern['pattern'], $content, $output);

            if ($check) {

                $currency = self::extractCurrency($output[$pattern['index']]);
                if (is_null($currency)) {
                    return $this->error;
                }

                $price = self::extractPrice($output[$pattern['index']]);
                if (is_null($price)) {
                    return $this->error;
                }

                return self::makeSuccessResponse([
                    'currency' => $currency,
                    'price'    => $price,
                ]);
            }
        }

        self::makeError(1);

        return $this->error;
    }

    /**
     * @param $url
     * @return mixed|null
     */
    protected function getPatterns($url)
    {
        $domain = self::getDomainName($url);

        if (is_null($domain)) {
            self::makeError(2);
            return null;
        }

        if (!array_key_exists($domain, $this->parserConfig)) {
            self::makeError(0);
            return null;
        }

        return $this->parserConfig[$domain]['patterns'];
    }

    protected function getConfig()
    {
        return app('config')->get('price-parser');
    }

    /**
     * @param $price
     * @return int|mixed|string|null
     */
    protected function extractCurrency($price)
    {
        $currencies = config('constants.currency');

        foreach ($currencies as $key => $currency) {

            $check = preg_match('/\b(\w*' . $key . '\w*)\b|\b(\w*' . $currency . '\w*)\b|[' . $currency . ']/', $price, $output);

            if ($check) {
                return $key;
            }
        }
        self::makeError(3);

        return null;
    }

    /**
     * @param $price
     * @return mixed|null
     */
    protected function extractPrice($price)
    {
        $check = preg_match('/\d+([\,]\d+)*([\.]\d+)/', $price, $output);

        if ($check) {
            return preg_replace('/[,\s]/', '', $output[0]);
        }

        self::makeError(4);

        return null;
    }

    protected function getDomainName($url)
    {
        $domain = extractDomain($url);

        if (!$domain) {
            return null;
        }

        $domainName = splitDomainByExt($domain[1]);

        if (!$domainName) {
            return null;
        }

        return $domainName[0];

    }

    protected function makeError($code)
    {
        $this->error = [
            'success'    => false,
            'message'    => self::ERROR_CODES[$code],
            'error_code' => $code,
            'data'       => [],
            'date'       => date('Y-m-d'),
        ];
    }

    protected function makeSuccessResponse($data)
    {
        return [
            'success' => true,
            'data'    => $data,
            'date'    => date('Y-m-d'),
        ];
    }

    public function getSupportedDomain()
    {
        return array_keys($this->parserConfig);
    }
}
