<?php

namespace App\Http\Controllers\CPanel;

use App\DataTables\Products\ProductsDataTable;
use App\DataTables\Products\ProductsImagesDataTable;
use App\DataTables\Products\ProductsTrashDataTable;
use App\Http\Requests\CPanel\ProductUpdateRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Spatie\MediaLibrary\Models\Media;

class ProductController extends MainController
{

    public $subViewFolder;

    protected $imageDir;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = 'product';
        $this->imageDir = 'products';
    }

    /**
     * @param ProductsDataTable $dataTable
     * @return Factory|View
     */
    public function index(ProductsDataTable $dataTable)
    {
        $viewData = array(
            'pageTitle' => 'Products',
        );

        return $dataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index", $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param Product $product
     * @param ProductsImagesDataTable $imagesDataTable
     * @return Factory|View
     */
    public function singleProductImages(Product $product, ProductsImagesDataTable $imagesDataTable)
    {
        $viewData = [
            'pageTitle' => 'Edit product images',
            'product'   => $product,
        ];

        return $imagesDataTable->with('modelId', $product->id)->render("{$this->viewFolder}.{$this->subViewFolder}.media_upload", $viewData);
//        return view("{$this->viewFolder}.{$this->subViewFolder}.media_upload")->with($viewData);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function uploadImage(Request $request, Product $product)
    {
        if ($request->has('file')) {
            $path = $this->imageDir . DIRECTORY_SEPARATOR . substr($product->id, 0, 4);
            $this->catch($product->addMediaFromRequest('file')
                ->usingName($path)
                ->usingFileName($this->generateImageName($request->file('file')))
                ->toMediaCollection('image'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $product = Product::with(['brand', 'category', 'tag'])->findOrFail($id);

        $viewData = [
            'pageTitle'  => 'Edit product',
            'product'    => $product,
            'brand'      => Brand::selectBox('Choose brand', ''),
            'category'   => Category::parentSelectBox('Choose category', ""),
            'cAncestors' => Category::ancestorsAndSelf($product->category[0]->id),
            'features'   => findInFeatures($product->features),
            'variations' => $product->variations,
        ];

        return view("{$this->viewFolder}.{$this->subViewFolder}.edit")->with($viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return RedirectResponse|Redirector
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $update = $product->update([
            'codes'            => json_encode($request->post('codes')),
            'brand_id'         => $request->post('brand'),
            'title'            => $request->post('title'),
            'manufacturer'     => $request->post('manufacturer'),
            'description'      => $request->post('description'),
            'features'         => (!is_null($request->post('features'))) ? json_encode(array(array('key' => 'Product Features', 'value' => $request->post('features')))) : null,
            'weight'           => $request->post('weight'),
            'dimensions'       => $request->post('dimensions'),
            'meta_description' => $request->post('meta_description'),
        ]);

        //sync category
        $category = $request->post('category');
        $product->category()->sync(array(end($category) => ['is_primary' => 1]));
        //update tags
        $tagIDs = $this->importTags($request->post('tags'));
        $product->tag()->sync($tagIDs);

        if ($update) {
            session()->flash('alert', $this->alerts['update']['success']);
        } else {
            session()->flash('alert', $this->alerts['update']['fail']);
        }

        return redirect(route('admin.product.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Product $product)
    {
        $response['error'] = 1;

        if ($request->ajax()) {
            /* Remove record from DB */
            if ($product->delete()):
                $response['error'] = 0;
                $response['message'] = 'Data trashed successfully';
            endif;
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function destroyTrash(Request $request, $id)
    {
        $response['error'] = 1;

        if ($request->ajax()) {
            // Find trashed data from DB
            $data = Product::withTrashed()->findOrFail($id);

            // Remove record from DB permanently
            if ($data->forceDelete()) {
                $response['error'] = 0;
                $response['message'] = 'Data removed successfully';
            }
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrash(Request $request, $id)
    {
        $response['error'] = 1;

        if ($request->ajax()) {

            // Find trashed data from DB
            $data = Product::withTrashed()->findOrFail($id);

            // Restore trashed data
            if ($data->restore()) {
                $response['error'] = 0;
                $response['message'] = 'Data restored successfully';
            }
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param ProductsTrashDataTable $trashDataTable
     * @return mixed
     */
    public function getTrashedDataTable(Request $request, ProductsTrashDataTable $trashDataTable)
    {
        return $trashDataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index");
    }

    public function mainImageSetter(Product $product, Media $media)
    {
        /**
         * @var $oldMedia Media
         */
        $response['error'] = 1;

        if ($media->hasCustomProperty('is_main')) {

            $propertyStatus = filter_var($media->getCustomProperty('is_main'), FILTER_VALIDATE_BOOLEAN);

            if ($propertyStatus) {

                $media->forgetCustomProperty('is_main');
                $media->save();

                $response['error'] = 0;
            }
        } else {

            $oldMedia = $product->getMainImage();

            if (!is_null($oldMedia)) {
                $oldMedia->forgetCustomProperty('is_main');
                $oldMedia->save();
            }

            $media->setCustomProperty('is_main', true);
            $media->save();

            $response['error'] = 0;
        }

        $response['message'] = 'Success';
        return response()->json($response);
    }
}
