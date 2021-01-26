<?php

use Illuminate\Support\Collection;


/**
 * @param $data
 * @param $propertyName
 * @return null
 */
function get_property($data, $propertyName)
{
    if (!is_object($data))
        return null;

    return (property_exists($data, $propertyName)) ? $data->$propertyName : null;
}

function extractDomain($url)
{
    preg_match('/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/', $url, $result);

    if (!filter_var($result[0], FILTER_VALIDATE_URL))
        return false;

    return $result;
}

function splitDomainByExt($domain)
{
    $response = explode('.', $domain);

    return !empty($response) ? $response : false;
}

function remove_query_arg($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    preg_match('/^[^?]+/', $url, $output);
    if (!empty($output[0])) {
        return $output[0];
    }

    return $url;
}

function getRemoteImageDetail($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL))
        return false;

    try {
        $url = file_get_contents($url);
        $image = \Intervention\Image\Facades\Image::make($url);
    } catch (Exception $e) {
        return false;
    }

    $fileExt = array_search($image->mime, \Illuminate\Support\Facades\Config::get('constants.media_mimes'));

    if (!$fileExt)
        return false;

    return ['extension' => $fileExt, 'mime' => $image->mime];
}

function parseDate($date): array
{
    $result = [];

    $datePattern = '/\d{1,4}([.\-\/])\d{1,2}([.\-\/])\d{1,2}/';
    $timePattern = '/[0-9]?[0-9]([:.][0-9]{2})([:.][0-9]{2})/';

    //extract date
    preg_match($datePattern, $date, $onlyDate);
    $result['date'] = !empty($onlyDate) ? $onlyDate[0] : null;

    //extract time
    preg_match($timePattern, $date, $time);
    $result['time'] = !empty($time) ? $time[0] : null;

    $result['date_time'] = $result['date'] . ' ' . $result['time'];

    return $result;
}

function generateImageNameByUrl($url)
{
    return sha1(microtime()) . '.' . getRemoteImageDetail($url)['extension'];
}

function group_by($key, $data)
{
    $result = array();

    foreach ($data as $val) {
        if (!is_null(get_property($val, $key))) {
            $lastVariationId = "";
            if ($lastVariationId != $val->variationId) {
                $result[$val->variationId][] = $val;
                $lastVariationId = $val->variationId;
            }
        }
    }

    return $result;
}

function seoUrl($string)
{
    $string = trim($string);

    $mix = array('ş', 'Ş', 'ı', 'I', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç', '(', ')', '/', ':', ',', 'Ə', 'ə');

    $eng = array('s', 's', 'i', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c', '', '', '-', '-', '', 'e', 'e');


    $string = str_replace($mix, $eng, $string);

    $string = strtolower($string);

    $string = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $string);

    $string = preg_replace("/[\s-]+/", " ", $string);

    $string = preg_replace("/[\s_]/", "-", $string);

    return $string;
}

function findInFeatures($features, $key = 'Product Features')
{
    if (is_null($features) || empty($features))
        return null;

    $features = json_decode($features);
    foreach ($features as $feature) {
        if ($feature->key == $key) {
            return $feature->value;
        }
    }
}

function calculateAverageRating($reviews)
{
    if ($reviews->count() == 0)
        return 0;

    $ratings = [];
    foreach ($reviews as $review) {
        $ratings[] = (int)$review->rating;
    }

    return number_format(array_sum($ratings) / count($ratings), 1);
}

function execCommand($command)
{
    if (function_exists('system')) {
        ob_start();
        system($command, $return_var);
        $output = ob_get_contents();
        ob_end_clean();
    } elseif (function_exists('pass' + 'thru')) {
        ob_start();
        passthru($command, $return_var);
        $output = ob_get_contents();
        ob_end_clean();
    } elseif (function_exists('exec')) {
        exec($command, $output, $return_var);
    } elseif (function_exists('shell_exec')) {
        $output = shell_exec($command);
    } else {
        $output = false;
    }

    return $output;
}

function externalUrl($url)
{
    if (preg_match('/www/', $url)) {
        return $url;
    } else {
        return 'www.' . $url;
    }
}

function addSlash2Url($url)
{
//    $url = add3W2Url($url);

    if (!preg_match('/\/$/', $url))
        return $url . '/';

    return $url;
}

/*function add3W2Url($url)
{
    if ( preg_match('/www/', $url) ) {
        return $url;
    }

    $parsed = explode('://', $url);
    return $parsed[0] . '://www.' . $parsed[1];
}*/

function sourceMediaUrl($source)
{
    $path = 'image' . DIRECTORY_SEPARATOR . 'sources' . DIRECTORY_SEPARATOR . $source;

    if (Storage::disk('media')->exists($path . '.png')) {
        return url(env('UPLOAD_DIR')) . DIRECTORY_SEPARATOR . $path . '.png';
    } elseif (Storage::disk('media')->exists($path . '.jpg')) {
        return url(env('UPLOAD_DIR')) . DIRECTORY_SEPARATOR . $path . '.jpg';
    } else {
        return config('constants.image.no_image');
    }


}

/**
 * @return \Predis\Client
 */
function redisCache()
{
//    return app('redis')->connection('cache')->client();
    return app('redis')->connection('cache')->client();
}

function tagExtractor($collection)
{
    return $collection->pluck('tag')->flatten()->unique('slug');
}

function pageVisitor($urls)
{
    if (is_array($urls)) {
        foreach ($urls as $url) {
            @file_get_contents($url);
        }
    } else {
        @file_get_contents($urls);
    }
}

function categoryUrl($category)
{
    return addSlash2Url(route('front.category.show', ['slugCategory' => $category['slug'], 'category' => $category['id']]));
}

function productUrl($product)
{
    return addSlash2Url(route('front.product.show', ['product' => $product['sp_hash'], 'slugProduct' => seoUrl($product['title'])]));
}

function refreshHomePageCache()
{
    \App\Models\Product::forgetOne(\App\Models\Product::REMOVABLE_CACHE_NAME['homepage']);
    @file_get_contents(url('/'), false);
}

function getStatusCodeOfUrl($url)
{
    $handle = curl_init($url);

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

    curl_exec($handle);

    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    curl_close($handle);

    return $httpCode;
}

function modifyWalmartUrl($url)
{
    $parsedUrl = array_filter(explode('/', $url));

    $parsedUrl[0] = $parsedUrl[0] . '/';
    unset($parsedUrl[4]);

    return implode('/', $parsedUrl);
}

function isJSON($string)
{
    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}

function checkUrl($url)
{
    $mustBeEncodedUrls = [
        'bestbuy.com',
        'bestbuy',
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

function makeSingleProductMetaDesc($product)
{
    return sprintf(__('pages.products.single.meta_description'),
        config('constants.currency')[$product->variations->first()->currency] . $product->variations->first()->current_price,
        $product->title,
        $product->reviewDetail->aggregate);
}

function makeCategoryMetaDesc($category, $page = 1)
{
    if ($page == 1) {
        return sprintf(__('pages.category.description'), $category->name, $category->name, null);
    }

    return sprintf(__('pages.category.description'), $category->name, $category->name, ' | ' . $page);
}

function makeCategoryTitle($category, $page = 1)
{
    if ($page == 1) {
        return sprintf(__('pages.category.title'), $category->name, null);
    }

    return sprintf(__('pages.category.title'), $category->name, ' | ' . $page);
}

function makeIndexInfo()
{
    return [
        1 => sprintf(__('pages.index.end_of_page_1'), \App\Models\Product::total(), \App\Models\Brand::total(), \App\Models\Category::total()),
        2 => sprintf(__('pages.index.end_of_page_2'), \App\Models\Review::total(), \App\Models\Product::totalManufacturer()),
    ];
}

function api($data = null)
{
    $apiResponse = new \App\Libraries\ApiResponse();

    if (!is_null($data)) {
        $apiResponse->setData($data);
    }

    return $apiResponse;
}

function prodImp($record = null)
{
    $importer = new \App\Libraries\ProductImporter();

    if (is_null($record)) {
        return $importer;
    }
    return $importer->record($record);
}

function build_url(array $parts)
{
    $scheme = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';

    $host = $parts['host'] ?? '';
    $port = isset($parts['port']) ? (':' . $parts['port']) : '';

    $user = $parts['user'] ?? '';
    $pass = isset($parts['pass']) ? (':' . $parts['pass']) : '';
    $pass = ($user || $pass) ? ($pass . '@') : '';

    $path = $parts['path'] ?? '';

    $query = empty($parts['query']) ? '' : ('?' . $parts['query']);

    $fragment = empty($parts['fragment']) ? '' : ('#' . $parts['fragment']);

    return implode('', [$scheme, $user, $pass, $host, $port, $path, $query, $fragment]);
}


if (!function_exists('http_build_url')) {
    define('HTTP_URL_REPLACE', 1);              // Replace every part of the first URL when there's one of the second URL
    define('HTTP_URL_JOIN_PATH', 2);            // Join relative paths
    define('HTTP_URL_JOIN_QUERY', 4);           // Join query strings
    define('HTTP_URL_STRIP_USER', 8);           // Strip any user authentication information
    define('HTTP_URL_STRIP_PASS', 16);          // Strip any password authentication information
    define('HTTP_URL_STRIP_AUTH', 32);          // Strip any authentication information
    define('HTTP_URL_STRIP_PORT', 64);          // Strip explicit port numbers
    define('HTTP_URL_STRIP_PATH', 128);         // Strip complete path
    define('HTTP_URL_STRIP_QUERY', 256);        // Strip query string
    define('HTTP_URL_STRIP_FRAGMENT', 512);     // Strip any fragments (#identifier)
    define('HTTP_URL_STRIP_ALL', 1024);         // Strip anything but scheme and host

    // Build an URL
    // The parts of the second URL will be merged into the first according to the flags argument.
    //
    // @param   mixed           (Part(s) of) an URL in form of a string or associative array like parse_url() returns
    // @param   mixed           Same as the first argument
    // @param   int             A bitmask of binary or'ed HTTP_URL constants (Optional)HTTP_URL_REPLACE is the default
    // @param   array           If set, it will be filled with the parts of the composed url like parse_url() would return
    function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false)
    {
        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

        // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
        if ($flags & HTTP_URL_STRIP_ALL) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
            $flags |= HTTP_URL_STRIP_PORT;
            $flags |= HTTP_URL_STRIP_PATH;
            $flags |= HTTP_URL_STRIP_QUERY;
            $flags |= HTTP_URL_STRIP_FRAGMENT;
        } // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
        else if ($flags & HTTP_URL_STRIP_AUTH) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
        }

        // Parse the original URL
        // - Suggestion by Sayed Ahad Abbas
        //   In case you send a parse_url array as input
        $parse_url = !is_array($url) ? parse_url($url) : $url;

        // Scheme and Host are always replaced
        if (isset($parts['scheme']))
            $parse_url['scheme'] = $parts['scheme'];
        if (isset($parts['host']))
            $parse_url['host'] = $parts['host'];

        // (If applicable) Replace the original URL with it's new parts
        if ($flags & HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key]))
                    $parse_url[$key] = $parts[$key];
            }
        } else {
            // Join the original URL path with the new path
            if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
                if (isset($parse_url['path']))
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
                else
                    $parse_url['path'] = $parts['path'];
            }

            // Join the original query string with the new query string
            if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
                if (isset($parse_url['query']))
                    $parse_url['query'] .= '&' . $parts['query'];
                else
                    $parse_url['query'] = $parts['query'];
            }
        }

        // Strips all the applicable sections of the URL
        // Note: Scheme and Host are never stripped
        foreach ($keys as $key) {
            if ($flags & (int)constant('HTTP_URL_STRIP_' . strtoupper($key)))
                unset($parse_url[$key]);
        }


        $new_url = $parse_url;

        return
            ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
            . ((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') . '@' : '')
            . ((isset($parse_url['host'])) ? $parse_url['host'] : '')
            . ((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
            . ((isset($parse_url['path'])) ? $parse_url['path'] . '/' : '/')
            . ((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
            . ((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '');
    }
}

function addTag($product, string $source, string $tag)
{
    foreach ($product->variations as $variation) {
        if ($variation->source->name === $source) {
            $urlParts = parse_url($variation->source_url);
            $params = [];
            if (isset($urlParts['query'])) {
                parse_str($urlParts['query'], $params);
            }
            $params['tag'] = 'larapcw-20';
            $urlParts['query'] = http_build_query($params);
            $variation->source_url = http_build_url($urlParts);
        }
    }
}


function imageSearcher($class)
{
    return app()->make($class);
}
