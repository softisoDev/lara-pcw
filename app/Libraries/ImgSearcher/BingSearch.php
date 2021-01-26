<?php


namespace App\Libraries\ImgSearcher;


use Illuminate\Support\Collection;

class BingSearch extends ImgSearcher implements ImageSearchInterface
{
    protected $baseUrl = 'https://www.bing.com/images/search';

    public function register($query, $params = [])
    {
        $this->registerSource($this->buildUrl($query, $params));
        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        $images = $this->getHtmlImgSrc($this->getSource());

        return array_filter($images, function ($img) {
            return !empty($img) && strpos($img, "th.bing.com") && !strpos($img, 'w=42');
        });
    }

    /**
     * @param $images
     * @return array
     */
    public function resizeImages($images)
    {
        $images = new Collection($images);

        return $images->map(function ($image){
            $parseUrl = parse_url($image);
            parse_str($parseUrl['query'], $queries);

            if ( array_key_exists('w', $queries) ) {
                $queries['w'] = (int) $queries['w'] * 2;
            }

            if ( array_key_exists('h', $queries) ) {
                $queries['h'] = (int) $queries['h'] * 2;
            }
            $parseUrl['query'] = http_build_query($queries);

            return http_build_url($parseUrl);
        })->toArray();
    }

    private function buildUrl($query, $params = [])
    {
        return urldecode($this->baseUrl . '?' . http_build_query(array_merge([
                'q'        => sprintf('%s+-amazon.com', urlencode($query)),
                'qs'       => 'n',
                'form'     => 'QBIR',
                'sp'       => '-1',
                'pq'       => sprintf('%s+-amazon.com', urlencode($query)),
                'sc'       => '0-35',
//                'cvid'     => '2FD62F30E6844496AB300946D85B5750',
                'first'    => '1',
                'tsc'      => 'ImageBasicHover',
                'scenario' => 'ImageBasicHover',
            ], $params)));
    }
}
