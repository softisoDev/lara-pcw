<?php

namespace App\Http\Controllers\CPanel;

use App\Jobs\ProductImporterJob;
use App\Libraries\Datafiniti\Datafiniti;
use App\Libraries\Datafiniti\DataParser;
use App\Models\Category;
use App\Models\Searches;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Throwable as ThrowableAlias;


class ProductImportController extends MainController
{

    public $subViewFolder;

    protected $tempSaveDir;

    protected $permSaveDir;

    protected $searchDir;

    protected $imageDir;

    protected $productCode;

    /**
     * ProductImportController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = 'product_import';
        $this->imageDir = 'products';
        $this->searchDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR;
        $this->tempSaveDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR . env('TEMP_SEARCH_DIR') . DIRECTORY_SEPARATOR;
        $this->permSaveDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR . env('SAVED_SEARCH_DIR') . DIRECTORY_SEPARATOR;

        $this->productCode = '{"asin":null,"upc":null,"upce":null,"upca":null,"ean":null,"ean8":null,"ean13":null,"vin":null,"gtins":null,"isbn":null}';

    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $viewData = array(
            'pageTitle' => 'Import Product',
            'sources' => Datafiniti::getSources(),
            'apiCategories' => Datafiniti::getCategories(),
            'dataLength' => Config::get('constants.selectBox.dataLength'),
            'categories' => Category::parentSelectBox('Select category'),
            'savedSearch' => self::getSavedSearchList(true),
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.index")->with($viewData);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * @throws ThrowableAlias
     */
    public function search(Request $request)
    {
        $result = array('success' => false);

        $options = array(
            'search_term' => $request->post('term'),
            'fields' => $request->post('fields'),
            'sources' => $request->post('sources'),
            'dateUpdated' => $request->post('dateUpdated'),
            'manufacturer' => $request->post('manufacturer'),
            'primaryCategory' => $request->post('primaryCategory'),
            'output_format' => 'JSON',
            'num_records' => $request->post('numRecords', 10),
            'download' => ((integer)$request->post('numRecords') > 50) ? true : false,
        );

        $df = new Datafiniti($options);

        $df->search();

        $output = $df->getOutput();

        $parser = new DataParser(json_decode($output), true);

        //parse data
        $data = $parser->parseMultiData();

        //set file name
        $searchName = is_null($options['search_term']) ? "search" : $options['search_term'];
        $searchName = seoUrl($searchName) . '_' . time();

        $fileExt = ".json";

        $saveSearch = self::saveSearch($data, $searchName);

        if (!$saveSearch)
            return $result;

        $result = array(
            'success' => true,
            'search_file' => $searchName . $fileExt,
            'query' => $df->getQuery(),
        );

        return response()->json($result);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws FileNotFoundException
     * @throws ThrowableAlias
     */
    public function saveProduct(Request $request)
    {

        $result = array(
            'success' => false,
            'message' => 'Something went wrong',
        );

        $validator = self::importValidator($request->all());

        if (!$validator['success'])
            return response()->json($validator);

        $tags = $request->post('tags');
        $category = array_filter($request->post('category'));
        $category = end($category);

        $fileContent = json_decode(Storage::disk('public')->get($request->post('sourceFile')));
        $fileContent->tags = $tags;
        $fileContent->category = $category;

        $records = get_property($fileContent, 'records');

        if (is_null($records) || empty($records))
            return response()->json($result);

        try {
            Searches::firstOrCreate([
                "asin" => $request->post('sourceFile'),
            ]);
            Storage::disk('public')->put($request->post('sourceFile'), json_encode($fileContent, JSON_PRETTY_PRINT));

            return response()->json(['success' => true, 'message' => 'Selected data added successfully']);
        } catch (\Exception $e) {
            return response()->json($result);
        }

    }

    /**
     * @param $file
     * @param array $indexS
     * @throws FileNotFoundException
     */
    protected function removeSaveDataFromFile($file, $indexS = array()): void
    {
        $fileContent = json_decode(Storage::disk('public')->get($file));

        foreach ($indexS as $index) {
            unset($fileContent->records[$index]);
        }

        $newData['success'] = true;
        $newData['records'] = [];

        foreach ($fileContent->records as $record) {
            $newData['records'][] = $record;
        }
        $newData['number_data'] = true;

        Storage::disk('public')->put($file, json_encode($newData), JSON_PRETTY_PRINT);
    }

    /**
     * @param $data
     * @return array
     * @throws ThrowableAlias
     */
    protected function importValidator($data)
    {
        $validator = Validator::make($data, [
            'products' => 'required|array',
            'sourceFile' => 'required',
            'products.*.category' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = view("{$this->viewFolder}.{$this->subViewFolder}.includes.error_template", ['errors' => $validator->errors()->all()])->render();
            return ['success' => false, 'errors' => $errors];
        }

        return ['success' => true];
    }

    /**
     * @param $data
     * @return array|JsonResponse
     * @throws ThrowableAlias
     */
    protected function searchValidator($data)
    {

        $validator = Validator::make($data, [
            'term' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = view("{$this->viewFolder}.{$this->subViewFolder}.includes.error_template", ['errors' => $validator->errors()->all()])->render();
            return ['success' => false, 'errors' => $errors];
        }

        return ['success' => true];
    }

    /**
     * @param Request $request
     * @return bool|JsonResponse
     * @throws FileNotFoundException
     * @throws ThrowableAlias
     */
    public function makeTemplate(Request $request)
    {

        if (!$request->ajax())
            return false;

        $filePath = ((boolean)$request->post('tempDir')) ? $this->tempSaveDir : $this->permSaveDir;

        $filePath .= $request->post('searchName');

        //get content
        $allProducts = (array)json_decode(Storage::disk('public')->get($filePath));

        if (!array_key_exists('records', $allProducts)) {
            $allProducts['records'] = [];
        }

        $perPage = $request->post('dataLength', 10);

        $currentPage = $request->post('page', 1);

        $currentPageData = array_slice($allProducts['records'], (($currentPage * $perPage) - $perPage), $perPage);

        $products = new LengthAwarePaginator($currentPageData, count($allProducts['records']), $perPage, $currentPage);

        $viewData = array(
            'products' => $products->toArray(),
            'paginator' => $products,
            'perPage' => $perPage,
            'sourceFile' => $filePath,
        );

        return response()->json([
            'success' => true,
            'parsedData' => count($allProducts['records']),
            'result' => view('cpanel.product_import.includes.result_template')->with($viewData)->render(),
        ]);

    }

    /**
     * @param $content
     * @param null $searchName
     * @param string $fileExt
     * @param bool $permanently
     * @return bool
     */
    protected function saveSearch($content, $searchName = null, $permanently = false, $fileExt = ".json")
    {
        if (!Storage::disk('public')->exists($this->searchDir)) {
            Storage::disk('public')->makeDirectory($this->searchDir);
        }

        $path = (!$permanently) ? $this->tempSaveDir : $this->permSaveDir;

        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        $searchName = is_null($searchName) ? "search_" . time() : $searchName;

        $saveFile = Storage::disk('public')->put($path . $searchName . $fileExt, $content);

        if ($saveFile)
            return true;

        return false;
    }

    /**
     * save search permanently
     * @param Request $request
     * @return JsonResponse
     */
    public function saveSearchPerm(Request $request)
    {
        $result['success'] = false;
        $result['message'] = 'Something went wrong';

        if (!$request->ajax())
            return response()->json($result);

        if (is_null($request->post('fileName')) || is_null($request->post('tempFileName')))
            return response()->json($result);

        $fileName = seoUrl($request->post('fileName')) . '.json';
        $tempFileName = $request->post('tempFileName');

        if (Storage::disk('public')->exists($this->permSaveDir . $fileName)) {
            return response()->json(['success' => false, 'message' => 'File is already exist']);
        }

        try {
            Storage::disk('public')->copy($this->tempSaveDir . $tempFileName, $this->permSaveDir . $fileName);
            return response()->json(['success' => true, 'message' => 'File is save successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($result);
        }

    }

    /**
     * @param bool $prepend
     * @return Collection
     */
    protected function getSavedSearchList($prepend = false)
    {
        $result = new Collection();

        $files = Storage::disk('public')->files($this->permSaveDir);
        foreach ($files as $file) {
            $result->put(basename($file), basename($file, '.json'));
        }

        if ($prepend) $result->prepend('Select search please', '');

        return $result;
    }
}

