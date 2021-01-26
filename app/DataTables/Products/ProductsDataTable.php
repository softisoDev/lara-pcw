<?php

namespace App\DataTables\Products;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
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
            ->addColumn('action', function ($product) {
                return $this->generateAction($product);
            })
            ->setRowAttr([
                'data-id' => function ($product) {
                    return $product->id;
                },
            ])
            ->editColumn('media', function (Product $product) {
                return '<img class="img img-responsive" src="' . $product->getMainImageUrl('small-thumb') . '">';
            })
            ->editColumn('created_at', function ($product) {
                return $product->created_at ? with(new Carbon($product->created_at))->format('Y-m-d') : '';
            })
            ->editColumn('updated_at', function ($product) {
                return $product->updated_at ? with(new Carbon($product->updated_at))->format('Y-m-d') : '';
            })
            ->rawColumns(['media', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @return Product[]|Collection
     */
    public function query()
    {
        $query = Product::with(['brand', 'media'])->select();
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
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
            ->lengthMenu([10, 15, 25, 50, 100])
            ->buttons([
                Button::make('reload'),
                Button::make('colvis')->columns(':not(:first-child)')->text('Columns'),
            ])
            ->select(true)
            ->orderBy(5);
    }

    public function collection()
    {

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
            Column::make('media')->title('Image')->orderable(false)->searchable(false),
            Column::make('title'),
            Column::make('manufacturer')->addClass('text-center'),
            Column::make('brand.name')->title('Brand')->addClass('text-center'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }


    protected function generateAction($product)
    {
        $response = '<div class="btn-group btn-group-xs" role="group">';
        $response .= '<a class="btn btn-outline-primary btn-sm" href="' . addSlash2Url(route('admin.product.edit', $product->id)) . '"><i class="fa fa-pencil text-blue"></i> Edit</a>';
        $response .= '<a class="btn btn-outline-danger btn-sm" target="_blank" href="' . $product->generateUrl() . '"><i class="fa fa-eye text-blue"></i> View</a>';
        $response .= '</div>';

        return $response;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'products_' . date('YmdHis');
    }
}
