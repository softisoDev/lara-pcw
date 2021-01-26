<?php

namespace App\DataTables\Source;

use App\Models\Source;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SourceDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('image', function ($source) {
                return '<img class="img img-responsive w-75" src="' . sourceMediaUrl($source->name) . '">';
            })
            ->addColumn('status', function ($source) {
                return $this->generateSwitchery($source->status, $source);
            })
            ->setRowAttr([
                'data-source' => function ($source) {
                    return $source->name;
                },
            ])
            ->rawColumns(['status', 'image'])
            ->make(true);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        $query = Source::withoutGlobalScopes()->select();
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
            Column::computed('image')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
            Column::make('name'),
            Column::computed('status')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    protected function generateSwitchery($isChecked, $source)
    {
        $checked = ($isChecked) ? 'checked=""' : '';

        return '<input type="checkbox" ' . $checked . '
        data-url="' . route('admin.product.source.status', $source->id) . '"
        class="radio-switch" data-size="mini" data-column="status" data-source="'.$source->name.'" onchange="trueFalseSetter(this)">';
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'source_' . date('YmdHis');
    }
}
