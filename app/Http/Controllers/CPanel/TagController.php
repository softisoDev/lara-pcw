<?php

namespace App\Http\Controllers\CPanel;

use App\DataTables\Tag\TagDataTable;
use App\DataTables\Tag\TagTrashDataTable;
use App\Http\Requests\CPanel\TagMultiCreateRequest;
use App\Http\Requests\CPanel\TagOptionQueryRequest;
use App\Http\Requests\CPanel\TagStoreRequest;
use App\Http\Requests\CPanel\TagUpdateRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class TagController extends MainController
{

    public $subViewFolder;

    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = "tag";
    }

    /**
     * Display a listing of the resource.
     *
     * @param TagDataTable $dataTable
     * @return Factory|View
     */
    public function index(TagDataTable $dataTable)
    {
        $viewData = array(
            'pageTitle' => 'Tag',
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
            'pageTitle'  => 'Create tag',
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.create")->with($viewData);
    }

    public function createMulti()
    {
        $viewData = array(
            'pageTitle'  => 'Create multi tag',
            'categories' => Category::parentSelectBox('Select category'),
        );

        return view("{$this->viewFolder}.{$this->subViewFolder}.create_multi")->with($viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TagStoreRequest $request)
    {
        $save = Tag::create($request->all());

        if ($save):
            session()->flash('alert', $this->alerts['add']['success']);
        else:
            session()->flash('alert', $this->alerts['add']['fail']);
        endif;

        return redirect()->back();
    }

    public function multiStore(TagMultiCreateRequest $request)
    {
        $keywords = explode(PHP_EOL, trim($request->post('keywords')));

        $save = $this->importTags(implode(",", $keywords));

        if (empty($save)) {
            session()->flash('alert', $this->alerts['add']['fail']);
            return redirect()->back();
        }

        $importedTags = Tag::find($save);

        $options = array(
            'categories' => array_filter($request->post('category')),
            'query'      => $request->post('query'),
        );

        foreach ($importedTags as $tag) {
            $tag->update([
                'options' => json_encode($options),
            ]);
        }

        session()->flash('alert', $this->alerts['add']['success']);
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);

        $viewData = array(
            'pageTitle'  => 'Edit tag',
            'tag'        => $tag,
            'categories' => Category::parentSelectBox('Select category'),
        );

        $options = $this->resolveOption($tag->options);
        if (!is_null($options) && is_array($options)) {
            if (!is_null($options['categories'])) {
                $viewData['cAncestors'] = Category::ancestorsAndSelf(end($options['categories']));
            }
            $viewData['query'] = $options['query'];
        }

        return view("{$this->viewFolder}.{$this->subViewFolder}.edit")->with($viewData);
    }

    private function resolveOption($options)
    {
        if (is_null($options)) {
            return null;
        }

        $options = json_decode($options);
        return [
            'categories' => $options->categories,
            'query'      => $options->query,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param Tag $tag
     * @return RedirectResponse|Redirector
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $update = $tag->update($request->all());

        if ($update):
            session()->flash('alert', $this->alerts['update']['success']);
        else:
            session()->flash('alert', $this->alerts['update']['fail']);
        endif;

        return redirect(route('admin.tag.index'));
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
            $data = Tag::findOrFail($id);

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
            $data = Tag::withTrashed()->findOrFail($id);

            // Remove record from DB permanently
            if ($data->forceDelete()):
                $response['error'] = 0;
                $response['message'] = 'Data removed successfully';
            endif;
        }

        return response()->json($response);
    }

    public function restoreTrash(Request $request, $id)
    {
        $response['error'] = 1;

        if ($request->ajax()) {

            // Find trashed data from DB
            $data = Tag::withTrashed()->findOrFail($id);

            // Restore trashed data
            if ($data->restore()):
                $response['error'] = 0;
                $response['message'] = 'Data restored successfully';
            endif;
        }

        return json_encode($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchTags(Request $request)
    {
        $result = array('success' => false);

        if (!$request->ajax())
            return response()->json($result);

        $data = Tag::all()->pluck('slug', 'id');

        return response()->json($data);
    }

    /**
     * @param TagOptionQueryRequest $request
     * @param Tag $tag
     * @return RedirectResponse
     */
    public function updateOption(TagOptionQueryRequest $request, Tag $tag)
    {
        $options = array(
            'categories' => array_filter($request->post('category')),
            'query'      => $request->post('query'),
        );

        if (empty($options['categories'])) {
            $options['categories'] = null;
        }

        $update = $tag->update([
            'options' => json_encode($options),
        ]);

        if ($update):
            session()->flash('alert', $this->alerts['update']['success']);
        else:
            session()->flash('alert', $this->alerts['update']['fail']);
        endif;

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param TagTrashDataTable $trashDataTable
     * @return mixed
     * @throws \Exception
     */
    public function getTrashedDataTable(Request $request, TagTrashDataTable $trashDataTable)
    {
        return $trashDataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index");
    }

    public function trueFalseSetter(Request $request, Tag $tag)
    {
        $response['error'] = 1;

        $status = filter_var($request->post('isChecked'), FILTER_VALIDATE_BOOLEAN);
        $column = $request->post('column');

        $update = $tag->update([
            $column => $status,
        ]);

        if ($update) {
            $response['error'] = 0;
            $response['message'] = 'Success';
        }

        return response()->json($response);
    }

}
