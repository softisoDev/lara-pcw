<?php


namespace App\Libraries\ImgSearcher;


interface ImageSearchInterface
{
    public function register($query,$param);

    public function getImages();
}
