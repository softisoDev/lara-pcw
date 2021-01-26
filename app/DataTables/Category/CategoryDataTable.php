<?php

namespace App\DataTables\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
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
            ->addColumn('action', function ($category) {
                return $this->generateAction($category);
            })
            ->setRowAttr([
                'data-id' => function ($category) {
                    return $category->id;
                },
            ])
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @return Category[]|Collection
     */
    public function query()
    {
        $query = Category::select();
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
            ->orderBy(1, 'asc');
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
            Column::make('name'),
            Column::make('title'),
            Column::make('product_total')->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }


    protected function generateAction($category)
    {
        $response = '<div class="btn-group btn-group-xs" role="group">';
        $response .= '<a class="btn btn-outline-primary btn-sm" href="' . addSlash2Url(route('admin.category.edit', $category->id)) . '"><i class="fa fa-pencil text-blue"></i> Edit</a>';
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
        return 'categories_' . date('YmdHis');
    }
}
