<?php


namespace App\Libraries;


class Grabber
{
    private static $host = "http://api.gooanalytics.com/grab/";

    public static function createContent(string $url)
    {
        return file_get_contents(self::$host . 'create/?url=' . $url);
    }

    public static function getContent(string $url)
    {
        return file_get_contents(self::$host . 'get/?url=' . $url);
    }

    public static function forgetUrl(string $url)
    {
        return file_get_contents(self::$host . 'remove/?url=' . $url);
    }

}
