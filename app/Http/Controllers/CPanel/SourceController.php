<?php

namespace App\Http\Controllers\CPanel;

use App\DataTables\Source\SourceDataTable;
use App\Models\Source;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;


class SourceController extends MainController
{

    public $subViewFolder;

    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = 'source';
    }

    /**
     * Display a listing of the resource.
     *
     * @param SourceDataTable $dataTable
     * @return Factory|View
     */
    public function index(SourceDataTable $dataTable)
    {
        $viewData = [
            'pageTitle' => 'Product sources',
        ];

        return $dataTable->render("{$this->viewFolder}.{$this->subViewFolder}.index", $viewData);
    }

    public function uploadMedia(Request $request)
    {
        $validator = Validator::make($request->all(), array(
            'source_name' => 'required',
            'image'       => 'required|mimes:jpeg,jpg,png',
        ));

        if ($validator->fails()) {
            $errors = view("{$this->viewFolder}.product_import.includes.error_template", ['errors' => $validator->errors()->all()])->render();
            return ['success' => false, 'errors' => $errors];
        }

        $image = $request->file('image');
        $destinationPath = public_path(env('UPLOAD_DIR') . DIRECTORY_SEPARATOR . "image" . DIRECTORY_SEPARATOR . 'sources') . DIRECTORY_SEPARATOR;
        //create directory if doesn't exist
        Storage::makeDirectory($destinationPath);

        try {
            $image->move($destinationPath, $request->post('source_name') . '.' . $image->getClientOriginalExtension());
            return ['success' => 'true'];
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => $e->getMessage()];
        }
    }

    public function trueFalseSetter(Request $request, $id)
    {
        $status = filter_var($request->post('isChecked'), FILTER_VALIDATE_BOOLEAN);

        $source = Source::withoutGlobalScopes()->findOrFail($id);
        $source->update(['status' => $status]);

        $response['error'] = 0;
        $response['message'] = 'Success';

        return response()->json($response);
    }
}
