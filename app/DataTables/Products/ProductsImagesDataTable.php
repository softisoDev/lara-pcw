<?php

namespace App\DataTables\Products;


use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductsImagesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->setRowAttr([
                'data-id' => function ($media) {
                    return $media->id;
                },
            ])
            ->addColumn('image', function ($media) {
                return '<img class="img img-responsive" src="' . $media->getFullUrl('medium-thumb') . '">';
            })
            ->addColumn('is_main', function ($media) {
                return $this->generateSwitchery($media->getCustomProperty('is_main'), $media);
            })
            ->rawColumns(['image', 'is_main'])
            ->make(true);
    }

    protected function generateSwitchery($isChecked, $media)
    {
        $checked = ($isChecked) ? 'checked=""' : '';

        return '<input type="checkbox" ' . $checked . '
        data-url="' . route('admin.product.main.image.setter', [$this->modelId, $media->id]) . '"
        class="radio-switch" data-size="mini" onchange="trueFalseSetter(this)">';
    }

    /**
     * Get query source of dataTable.
     *
     * @return Media[]|Collection
     */
    public function query()
    {

        $query = Media::where('model_id', $this->modelId)->select();
        return $this->applyScopes($query);
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('main-table')
            ->addTableClass([
                'table',
                'w-100',
                'display',
                'table-striped',
                'table-bordered',
                'scroll-horizontal-vertical',
                'base-style',
                'dtTable'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'#custom-filter.col-md-12'l>><'row'<'#toolbar_buttons.col-md-8'B><'col-md-4'f>>rtip")
            ->pageLength(25)
            ->buttons([
                Button::make('reload'),
                Button::make('colvis')->columns(':not(:first-child)')->text('Columns'),
            ])
            ->drawCallback('function(){ initSwitchery(); }')
            ->select(true);
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->hidden()->searchable(false)->printable(false),
            Column::make('mime_type')->width(10),
            Column::computed('image')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
            Column::computed('is_main')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'product_media_' . date('YmdHis');
    }
}
