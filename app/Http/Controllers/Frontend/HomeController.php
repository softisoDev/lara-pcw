<?php

namespace App\Http\Controllers\Frontend;

use App\Libraries\ImgSearcher\GoogleSearch;
use App\Repository\HomePageRepository;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class HomeController extends MainController
{
    public $subViewFolder;
    protected $host = "http://api.gooanalytics.com/grab/";

    public function boot()
    {
        $this->subViewFolder = 'home';
    }

    public function index(HomePageRepository $repository)
    {
        return $this->render("{$this->viewFolder}.{$this->subViewFolder}.index", [
            'pageTitle'   => __('pages.index.title'),
            'description' => __('pages.index.description'),
            'categories'  => $repository->get(4),
            'info'        => makeIndexInfo(),
        ]);
    }

    public function test()
    {

        dd(remove_query_arg('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9dTprg3NhyDE5zvDIidqqPihUX2hhs2Yh9-O4wIRXgurSeGc8f3stby_sYA&s'));

        $google = imageSearcher(GoogleSearch::class)
            ->bindUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)')
            ->bindRequestIp("89.147.213.100")
//            ->bindRequestIp("8.21.8.100")
            ->register('High Pressure Shower Head - Voolan Rain Shower head Made of 304 Stainless Steel - Comfortable Shower Experience Even at Low Water Flow (8 Inch)')
            ->getImages();

        dd($google);

        /*$url = 'https://www.google.com/search?q=Kaytee+Fiesta+Conure+Food+-site%3Aamazon.com&tbm=isch&oq=Kaytee+Fiesta+Conure+Food+-site%3Aamazon.com&sclient=img';
        $client = new Client();

        $response = $client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            ],
        ]);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $images = $crawler->filter('img')->each(function ($node){
            return $node->attr('src');
        });

        dd($images);*/

        /*$bing = imageSearcher(BingSearch::class)->register("men boat black");
        sleep(10);
        $images = $bing->resizeImages($bing->getImages());
        $bing->removeSource();
        dd($images);*/

    }

}

