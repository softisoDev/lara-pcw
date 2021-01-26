<?php


namespace App\Libraries\ImgSearcher;


use App\Libraries\Grabber;
use Symfony\Component\DomCrawler\Crawler;

abstract class ImgSearcher
{
    protected $crawler;
    protected $sourceUrl;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    protected abstract function getImages();

    protected function registerSource($url)
    {
        Grabber::createContent($this->sourceUrl = $url);
    }

    public function removeSource()
    {
        Grabber::forgetUrl($this->sourceUrl);
    }

    protected function getSource()
    {
        return Grabber::getContent($this->sourceUrl);
    }

    protected function extractFromHTML($source, callable $selector)
    {
        $this->crawler->add($source);
        return $selector($this->crawler);
    }

    protected function getHtmlImgSrc($html)
    {
        return $this->extractFromHTML($html, function (Crawler $crawler) {
            return $crawler->filter('img')->each(function ($node) {
                return $node->attr('src');
            });
        });
    }


}
