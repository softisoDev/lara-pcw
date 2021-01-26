<?php

namespace App\Http\Controllers\CPanel;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\Tag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use \Illuminate\Http\Request;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\Models\Media;

class MainController extends Controller
{
    public $viewFolder;

    public $pageTitle;

    public $data;

    public $alerts = array();

    public $responses = array();

    public function __construct()
    {

        $this->alerts = Config::get('constants.alerts');

        $this->responses = Config::get('constants.responses');

        $this->viewFolder = "cpanel";

        $this->pageTitle = "Dashboard";

        $this->data = [];

        if (method_exists($this, 'boot')) {
            $this->boot();
        }
    }

    public function render($data = array())
    {
        return response()->json(array_merge($data, $this->data));
    }

    /**
     * @param $data // Model data
     * @param $url
     * @param $path
     * @param $properties
     * @param string $collection
     */
    public function saveImageByUrl($data, $url, $path, $properties = array(), $collection = 'image')
    {
        try {
            $data->addMediaFromUrl($url)->usingFileName(generateImageNameByUrl($url))->usingName($path)->withCustomProperties($properties)->toMediaCollection($collection);
        } catch (FileCannotBeAdded $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function generateImageName(UploadedFile $file)
    {
        return sha1(microtime()) . '.' . strtolower($file->getClientOriginalExtension());
    }

    /**
     * @param $call
     * @return mixed
     */
    public function catch($call)
    {
        try {
            return app()->call($call);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . ' Line: ' . $exception->getLine() . ' in ' . $exception->getFile());
        }
    }

    public function deleteMediaAjax(Request $request, Media $media)
    {
        $response = [
            'error' => 1,
        ];

        if (!$request->ajax()) {
            return response()->json($response);
        }

        if ($media->delete()) {
            $response['error'] = 0;
            $response['message'] = 'Media deleted successfully';
            return response()->json($response);
        }

        return response()->json($response);
    }

    protected function importTags($tags)
    {
        $result = [];
        $tags = explode(',', $tags);
        $tags = array_filter($tags);

        if (empty($tags))
            return $result;

        foreach ($tags as $tag) {
            $tag = trim($tag);
            $find = Tag::withoutGlobalScopes()->where('slug', $tag)->first();

            if (is_null($find) && !empty($tag)) {

                $find = Tag::create([
                    'slug' => $tag,
                ]);
            }

            $result[] = $find->id;
        }

        return $result;
    }

    protected function importSource($source)
    {
        $source = trim($source);
        $find = Source::withoutGlobalScopes()->where('name', trim($source))->first();

        if (is_null($find) && !empty($source)) {
            $find = Source::create([
                'name' => trim($source),
            ]);
        }

        return $find->id;
    }


}
