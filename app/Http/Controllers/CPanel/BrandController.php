<?php

namespace App\Http\Controllers\CPanel;

use App\DataTables\Brand\BrandDataTable;
use App\DataTables\Brand\BrandTrashDataTable;
use App\Http\Requests\CPanel\BrandStoreRequest;
use App\Http\Requests\CPanel\BrandUpdateRequest;
use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends MainController
{

    public $subViewFolder;

    protected $imageDir;


    /**
     * BrandController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = "brand";
        $this->imageDir = "brand";
    }

    /**
     * Display a listing of the resource.
     *
     * @param BrandDataTable $dataTable
     * @return Factory|View
     */
    public function index(BrandDataTable $dataTable)
    {
        $viewData = array(
            'pageTitle' => 'Brand',
        );

        return $dataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index", $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $viewData = array(
            'pageTitle'   => 'Create brand',
            'parentBrand' => Brand::selectBox('Parent brand', null),
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.create")->with($viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BrandStoreRequest $request
     * @return RedirectResponse
     */
    public function store(BrandStoreRequest $request)
    {

        $data = [
            'name'        => $request->post('name'),
            'subtitle'    => $request->post('subtitle'),
            'description' => $request->post('description'),
            'slug'        => $request->post('slug'),
        ];

        if ((int)$request->post('parent_brand') == 0) {
            $save = Brand::create($data);
        } else {
            $parent = Brand::findOrFail($request->post('parent_brand'));
            $save = $parent->children()->create($data);
        }

        //store image
        if ($request->hasFile('image')) {
            $this->catch($save->addMediaFromRequest('image')
                ->withCustomProperties(['title' => $request->post('image_title')])
                ->usingName($this->imageDir)->usingFileName($this->generateImageName($request->file('image')))
                ->toMediaCollection('image'));
        }

        if ($save):
            session()->flash('alert', $this->alerts['add']['success']);
        else:
            session()->flash('alert', $this->alerts['add']['fail']);
        endif;

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $viewData = array(
            'pageTitle' => 'Brand',
            'brand'     => Brand::findOrFail($id),
        );
        dd($viewData['brand']);
        return view("{$this->viewFolder}.{$this->subViewFolder}.show")->with($viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $viewData = array(
            'pageTitle'   => 'Edit brand',
            'parentBrand' => Brand::selectBox('Parent brand', 0),
            'brand'       => Brand::findOrFail($id),
        );
        $viewData['mediaPath'] = !is_null($viewData['brand']->getFirstMedia('image')) ? $viewData['brand']->getFirstMedia('image')->getFullUrl() : null;
        $viewData['mediaTitle'] = !is_null($viewData['brand']->getFirstMedia('image')) ? $viewData['brand']->getFirstMedia('image')->getCustomProperty('title') : null;

        return view("{$this->viewFolder}.{$this->subViewFolder}.edit")->with($viewData);
    }


    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        // update brand to db
        $update = $brand->update([
            'name'        => $request->post('name'),
            'subtitle'    => $request->post('subtitle'),
            'description' => $request->post('description'),
            'slug'        => $request->post('slug'),
        ]);

        // upload image
        if ($request->hasFile('image')) {

            if (!is_null($brand->getFirstMedia('image'))) {
                $brand->getFirstMedia('image')->delete();
            }

            $this->catch($brand->addMediaFromRequest('image')->withCustomProperties(['title' => $request->post('image_title')])
                ->usingName($this->imageDir)->usingFileName($this->generateImageName($request->file('image')))
                ->toMediaCollection('image'));
        }

        if ($update):
            session()->flash('alert', $this->alerts['update']['success']);
        else:
            session()->flash('alert', $this->alerts['update']['fail']);
        endif;

        return redirect(route('admin.brand.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return false|string
     * @throws Exception
     */
    public function destroy(Request $request, $id)
    {
        $response['error'] = 1;
        if ($request->ajax()) {
            /* Find data from DB */
            $data = Brand::findOrFail($id);

            /* Remove record from DB */
            if ($data->delete()):
                $response['error'] = 0;
                $response['message'] = 'Data trashed successfully';
            endif;
        }

        return json_encode($response);

    }

    /**
     * @param Request $request
     * @param $id
     * @return false|string
     */
    public function destroyTrash(Request $request, $id)
    {
        $response['error'] = 1;

        if ($request->ajax()) {
            // Find trashed data from DB
            $data = Brand::withTrashed()->findOrFail($id);

            // Remove record from DB permanently
            if ($data->forceDelete()):
                $response['error'] = 0;
                $response['message'] = 'Data removed successfully';
            endif;
        }

        return json_encode($response);
    }

    /**
     * @param Request $request
     * @param $id
     * @return false|string
     */
    public function restoreTrash(Request $request, $id)
    {
        if ($request->ajax()) {

            // Find trashed data from DB
            $data = Brand::withTrashed()->findOrFail($id);

            // Restore trashed data
            if ($data->restore()):
                $response['error'] = 0;
                $response['message'] = 'Data restored successfully';
            endif;
        }

        return json_encode($response);
    }

    /**
     * @return Factory|View
     */
    public function order()
    {
        $viewData = array(
            'pageTitle' => 'Category',
            'brands'    => Brand::defaultOrder()->get()->toTree(),
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.order")->with($viewData);
    }

    public function setOrder(Request $request)
    {
        if (!$request->ajax()) {
            return false;
        }

        $data = $request->post('data');
        $save = Brand::rebuildTree($data);

        return response()->json(['success' => true, 'message' => 'Tree updated successfully']);
    }

    /**
     * @param Request $request
     * @param BrandTrashDataTable $trashDataTable
     * @return mixed
     */
    public function getTrashedDataTable(Request $request, BrandTrashDataTable $trashDataTable)
    {
        return $trashDataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index");
    }
    /**
     * @param $childrenBrand
     */
    public function getChildren($childrenBrand)
    {
        foreach ($childrenBrand as $item) {
            echo '<li>' . $item->name . '</li>';
            if ($item->children) {
                echo '<ul>';
                self::getChildren($item->children);
                echo '</ul>';
            }
        }
    }

}
