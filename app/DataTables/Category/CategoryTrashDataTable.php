<?php

namespace App\DataTables\Category;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryTrashDataTable extends DataTable
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
                'data-id' => function ($category) {
                    return $category->id;
                },
            ])
            ->editColumn('deleted_at', function ($category) {
                return $category->deleted_at ? with(new Carbon($category->deleted_at))->format('Y-m-d') : '';
            })
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @return Category[]|Collection
     */
    public function query()
    {
        $query = Category::onlyTrashed()->select();
        return $this->applyScopes($query);
    }

    /*public function html()
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
    }*/

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
            Column::make('deleted_at')->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'categories_trash_' . date('YmdHis');
    }
}
