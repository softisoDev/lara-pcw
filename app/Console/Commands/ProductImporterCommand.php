<?php

namespace App\Console\Commands;

use App\Libraries\ImgSearcher\GoogleSearch;
use App\Models\Searches;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImporterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pricegrop:import-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $ip = [
        "31.210.170.177",
        "31.210.173.131",
        "185.162.9.202",
        "185.162.11.243",
    ];

    /*
     * search status
     * 0 => not processed
     * 1 => completed
     * 2 => in process
     * 3 => record not found
     */

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = Searches::query()->where(['status' => '0'])->get();


        foreach ($files as $file) {
            $file->status = '2'; //in process
            $file->save();

            $fileContent = json_decode(Storage::disk('public')->get($file->asin));

            $tags = get_property($fileContent, 'tags');
            $category = get_property($fileContent, 'category');
            $records = get_property($fileContent, 'records');

            if (is_null($records) || empty($records)) {
                $file->status = '3'; //in process
                $file->save();
                continue;
            }

            $records = $this->refineRecords($records);

            foreach ($records as $record) {
                try {
                    $this->saveRecord($record, $tags, $category);
                } catch (\Exception $exception) {
                    Log::error("Command import error: " . $exception->getMessage());
                    continue;
                }
            }

            $file->status = '1';
            $file->save();
        }
    }

    private function saveRecord($record, $tags, $category)
    {
        $hash = Str::random(14);

        return prodImp()->tag($tags)
            ->category($category ?? 1)
            ->hash($hash)
            ->record($record)
            ->save();
    }

    private function refineRecords($records)
    {
        $records = $this->filterRecords($records);

        foreach ($records as $record) {
            $record = $this->refineReviews($record);
            $record = $this->refineDescriptions($record);
            $record = $this->refineImages($record);
        }

        return $records;
    }

    private function refineReviews($record)
    {
        $record->reviews = array_filter($record->reviews ?? [], function ($review) {
            return !preg_match('/\b(\w*amazon\w*)\b/', $review->source);
        });

        return $record;
    }

    private function refineDescriptions($record)
    {
        $record->descriptions = array_filter($record->descriptions ?? [], function ($description) {
            return !preg_match('/\b(\w*amazon\w*)\b/', $description->source);
        });

        return $record;
    }

    private function filterRecords($records)
    {
        array_map(function ($record) {
            array_map(function ($variation) {
                $parsedUrl = parse_url($variation->url);
                unset($parsedUrl['query']);
                $variation->url = build_url($parsedUrl);
            }, $record->variations);
        }, $records);

        $records = array_filter($records, function ($record) {
            return !empty($record->variations);
        });

        array_map(function ($record) {
            $record->variations = new Collection($record->variations);
            $record->variations = $record->variations->unique('url');
            $record->variations = $record->variations->toArray();
        }, $records);

        return $records;
    }

    private function refineImages($record)
    {
        if (is_null(get_property($record, 'images')) || is_null(get_property($record, 'primary_images'))) {
            return $this->setRemoteImages($record);
        }

        $record->images = array_filter($record->images, function ($image) {
            return !preg_match('/\b(\w*amazon\w*)\b/', $image);
        });

        $record->primary_images = array_filter($record->primary_images, function ($image) {
            return !preg_match('/\b(\w*amazon\w*)\b/', $image);
        });

        if (empty($record->images) || empty($record->primary_images)) {
            return $this->setRemoteImages($record);
        }

        $record->primary_images = array_map(function ($img) {
            return $this->sanitizeImgUrl(urldecode($img));
        }, $record->primary_images);


        $record->images = array_map(function ($img) {
            return $this->sanitizeImgUrl(urldecode($img));
        }, $record->images);

        return $record;
    }

    private function sanitizeImgUrl($url)
    {
        if (strpos($url, 'c.shld.net')) {
            try {
                $parse = parse_url($url);
                $query = $parse['query'];
                parse_str($query, $pQuery);
                $url = array_key_exists('src', $pQuery) ? $pQuery['src'] : $url;
            } catch (\Exception $e){
                Log::error($e->getMessage());
            }
        }

        return $url;
    }

    private function setRemoteImages($record)
    {
        try {
            $images = imageSearcher(GoogleSearch::class)
                ->bindUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)')
                ->bindRequestIp(Arr::random($this->ip, 1))
                ->register($record->name)
                ->getImages();

            if (is_null($images) || empty($images)) {
                return $record;
            }

            $image = reset($images);
            $record->images = [$image];
            $record->primary_images = [$image];

            return $record;
        } catch (\Exception $exception) {
            Log::error("Remote image exception: " . $exception->getMessage());
            return $record;
        }
    }
}
