<?php

namespace App\DataTables\Tag;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TagDataTable extends DataTable
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
                'data-id' => function ($tag) {
                    return $tag->id;
                },
            ])
            ->editColumn('created_at', function ($tag) {
                return $tag->created_at ? with(new Carbon($tag->created_at))->format('Y-m-d') : '';
            })
            ->addColumn('status', function ($tag) {
                return $this->generateSwitchery($tag->status, $tag, 'status');
            })
            ->addColumn('is_hot', function ($tag) {
                return $this->generateSwitchery($tag->is_hot, $tag, 'is_hot');
            })
            ->rawColumns(['status', 'is_hot'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @return Tag[]|Collection
     */
    public function query()
    {
        $query = Tag::select();
        return $this->applyScopes($query);
    }


    /**
     * @param $isChecked
     * @param $tag
     * @param $column
     * @return string
     */
    protected function generateSwitchery($isChecked, $tag, $column)
    {
        $checked = ($isChecked) ? 'checked=""' : '';

        return '<input type="checkbox" ' . $checked . '
        data-url="' . route('admin.tag.boolean.setter', $tag->id) . '"
        class="radio-switch" data-size="mini" data-column="' . $column . '" onchange="trueFalseSetter(this)">';
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
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
            ->drawCallback('function(){ initSwitchery(); }')
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
            Column::make('slug')->title('Tag'),
            Column::make('title'),
            Column::make('search_count')->addClass('text-center'),
            Column::make('created_at')->addClass('text-center'),
            Column::computed('status')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
            Column::computed('is_hot')
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
        return 'tags_' . date('YmdHis');
    }
}
