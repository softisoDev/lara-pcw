<?php

namespace App\Http\Controllers\CPanel;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class LogController extends MainController
{
    public $subViewFolder;

    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = 'log';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPriceParser()
    {
        $viewData = [
            'pagetTitle' => 'Log info',
            'files'      => self::getPriceParserLogFiles(true),
        ];

        return view("{$this->viewFolder}.{$this->subViewFolder}.price_parcing")->with($viewData);
    }

    /**
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Throwable
     */
    public function getPriceParserData(Request $request)
    {
        if (!$request->ajax()) {
            return false;
        }

        $allData = json_decode(Storage::disk('public')->get(env('PRICE_PARSER_LOG_DIR') . DIRECTORY_SEPARATOR . $request->post('logFile')), true);

        $perPage = $request->post('dataLength');

        $currentPage = $request->post('page', 1);

        $currentData = array_slice($allData, (($currentPage * $perPage) - $perPage), $perPage);

        $data = new LengthAwarePaginator($currentData, count($allData), $perPage, $currentPage);

        $viewData = [
            'data'       => $data,
            'perPage'    => $perPage,
            'dataLength' => Config::get('constants.selectBox.dataLength'),
        ];

        return response()->json([
            'success' => true,
            'result'  => view('cpanel.log.includes.price_parser_log_template')->with($viewData)->render(),
        ]);
    }

    /**
     * @param $prepend
     * @return Collection
     */
    protected function getPriceParserLogFiles($prepend)
    {
        $result = new Collection();

        $files = Storage::disk('public')->files(env('PRICE_PARSER_LOG_DIR'));

        foreach ($files as $file) {
            $result->put(basename($file), basename($file, '.json'));
        }

        if ($prepend) $result->prepend('Please, select file', '');

        return $result;
    }
}
