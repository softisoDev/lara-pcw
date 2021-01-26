<?php


namespace App\Libraries\ImgSearcher;


use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GoogleSearch extends ImgSearcher implements ImageSearchInterface
{

    protected $baseUrl = "https://www.google.com/search";
    private $client;
    private $response = null;
    private $requestOptions = [];


    public function __construct(Crawler $crawler, Client $client)
    {
        $this->crawler = $crawler;
        $this->client = $client;
    }

    public function register($query, $param = [])
    {
        $this->request($this->buildUrl($query, $param));
        return $this;
    }

    protected function request($url, $options = [])
    {
        $response = $this->client->request('GET', $url, array_merge($this->requestOptions, $options));

        if ( $response->getStatusCode() === 200 ) {
            $this->response = $response;
        }
    }

    public function bindRequestIp($ip)
    {
        if ( !empty($ip) ) {
            $this->requestOptions['curl'] = array_merge($this->requestOptions['curl'] ?? [], [
                CURLOPT_INTERFACE => $ip,
            ]);
        }
        return $this;
    }

    public function bindUserAgent($agent)
    {
        if ( !empty($agent) ) {
            $this->requestOptions['headers'] = array_merge($this->requestOptions['headers'] ?? [], [
                'User-Agent' => $agent,
            ]);
        }
        return $this;
    }

    public function getImages()
    {
        if ( is_null($this->response) ) {
            return null;
        }

        $images = $this->getHtmlImgSrc($this->response->getBody()->getContents());

        return array_filter($images, function ($img) {
           return strpos($img, 'encrypted-tbn0.gstatic.com');
        });
    }

    public function buildUrl($query, $params = [])
    {
        return urldecode($this->baseUrl . '?' . http_build_query(array_merge([
                "q"       => urldecode($query) . "+-site%3Aamazon.*",
                "tbm"     => "isch",
                "oq"      => urldecode($query) . "+-site%3Aamazon.*",
                "sclient" => "img",
            ], $params)));

    }
}
