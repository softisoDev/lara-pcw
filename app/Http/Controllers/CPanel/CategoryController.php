<?php

namespace App\Http\Controllers\CPanel;

use App\DataTables\Category\CategoryDataTable;
use App\DataTables\Category\CategoryTrashDataTable;
use App\Http\Requests\CPanel\CategoryStoreRequest;
use App\Http\Requests\CPanel\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class CategoryController extends MainController
{
    public $subViewFolder;
    protected $imageDir;

    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = "category";
        $this->imageDir = "category";
    }

    /**
     * Display a listing of the resource.
     *
     * @param CategoryDataTable $dataTable
     * @return Factory|View
     */
    public function index(CategoryDataTable $dataTable)
    {
        $viewData = array(
            'pageTitle'  => 'Category',
            'categories' => Category::parentSelectBox('Select category'),
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
            'pageTitle'      => 'Create category',
            'parentCategory' => Category::selectBox('Parent category', null),
            'trueFalse'      => Config::get('constants.selectBox.trueFalse'),
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.create")->with($viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryStoreRequest $request)
    {
        // save brand to db
        $data = [
            'name'             => $request->post('name'),
            'title'            => $request->post('title'),
            'description'      => $request->post('description'),
            'content'          => $request->post('content'),
            'slug'             => $request->post('slug'),
            'show_on_homepage' => $request->post('showOnHomepage'),
        ];

        if ((int)$request->post('parent_category') == 0) {
            $save = Category::create($data);
        } else {
            $parent = Category::findOrFail($request->post('parent_category'));
            $save = $parent->children()->create($data);
        }

        //store image
        if ($request->hasFile('image')) {
            $this->catch($save->addMediaFromRequest('image')
                ->withCustomProperties(['title' => $request->post('image_title', $request->post('title'))])
                ->usingName($this->imageDir)->usingFileName($this->generateImageName($request->file('image')))
                ->toMediaCollection('image'));
        }

        if ($save) {
            session()->flash('alert', $this->alerts['add']['success']);
        } else {
            session()->flash('alert', $this->alerts['add']['fail']);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        dd(Category::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $viewData = array(
            'pageTitle'      => 'Edit category',
            'parentCategory' => Category::selectBox('Parent category', 0),
            'category'       => Category::findOrFail($id),
            'trueFalse'      => Config::get('constants.selectBox.trueFalse'),
        );

        $viewData['mediaPath'] = !is_null($viewData['category']->getFirstMedia('image')) ? $viewData['category']->getFirstMedia('image')->getFullUrl() : null;
        $viewData['mediaTitle'] = !is_null($viewData['category']->getFirstMedia('image')) ? $viewData['category']->getFirstMedia('image')->getCustomProperty('title') : null;

        return view("{$this->viewFolder}.{$this->subViewFolder}.edit")->with($viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param Category $category
     * @return string
     * @throws \Exception
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        // update category
        $update = $category->update([
            'name'             => $request->post('name'),
            'title'            => $request->post('title'),
            'description'      => $request->post('description'),
            'content'          => $request->post('content'),
            'slug'             => $request->post('slug'),
            'show_on_homepage' => $request->post('showOnHomepage'),
        ]);


        //update image
        if ($request->hasFile('image')) {

            if (!is_null($category->getFirstMedia('image'))) {
                $category->getFirstMedia('image')->delete();
            }

            $this->catch($category->addMediaFromRequest('image')->withCustomProperties(['title' => $request->post('image_title', $request->post('title'))])
                ->usingName($this->imageDir)->usingFileName($this->generateImageName($request->file('image')))
                ->toMediaCollection('image'));
        }

        if ($update) {
            session()->flash('alert', $this->alerts['update']['success']);
        } else {
            session()->flash('alert', $this->alerts['update']['fail']);
        }

        $category->updateSlug();

        return redirect(route('admin.category.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $response['error'] = 1;

        if ($request->ajax()) {
            /* Find data from DB */
            $data = Category::findOrFail($id);

            /* Remove record from DB */
            if ($data->delete()):
                $response['error'] = 0;
                $response['message'] = 'Data trashed successfully';
            endif;
        }

        return response()->json($response);
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
            $data = Category::withTrashed()->findOrFail($id);

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
     * @return false|string
     */
    public function restoreTrash(Request $request, $id)
    {
        $response['error'] = 1;

        if ($request->ajax()) {

            // Find trashed data from DB
            $data = Category::withTrashed()->findOrFail($id);

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
     * @param $id
     * @return false|string
     */
    public function trueFalseSetter(Request $request, $id)
    {

        $data = Category::findOrFail($id);
        $data->show_on_homepage = $request->isChecked;
        $data->save();

        $response['error'] = 0;
        $response['message'] = 'Success';

        return response()->json($response);
    }

    /**
     * @return Factory|View
     */
    public function order()
    {
        $viewData = array(
            'pageTitle'  => 'Category',
            'categories' => Category::defaultOrder()->get()->toTree(),
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.order")->with($viewData);
    }

    /**
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function setOrder(Request $request)
    {
        if (!$request->ajax()) {
            return false;
        }

        $data = $request->post('data');
        $save = Category::rebuildTree($data);

        return response()->json(['success' => true, 'message' => 'Tree updated successfully']);
    }


    /**
     * @param Request $request
     * @param CategoryTrashDataTable $trashDataTable
     * @return mixed
     */
    public function getTrashedDataTable(Request $request, CategoryTrashDataTable $trashDataTable)
    {
        return $trashDataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index");
    }

    /**
     * @param Request $request
     * @return array
     */
    public function select2Remote(Request $request)
    {
        if (!$request->ajax() || is_null($request->input('term')) || empty($request->input('term'))) {
            return ['results' => ['id' => null, 'text' => 'Result not found']];
        }

        $categories = Category::whereLike('name', $request->post('term'))->get(['id', 'name AS text']);
        return ['results' => $categories];
    }

    public function getSubcategory(Request $request, Category $category)
    {
        if (!$request->ajax()) {
            return false;
        }

        return view("{$this->viewFolder}.{$this->subViewFolder}.includes.sub_category")->with(['category' => $category])->render();
    }

    /**
     * @param Category $category
     */
    protected function updateSlug(Category $category)
    {
        $category->slug = $category->generateSlug();
        $category->save();
    }
}
