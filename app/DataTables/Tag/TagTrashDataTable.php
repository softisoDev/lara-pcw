<?php

namespace App\DataTables\Tag;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TagTrashDataTable extends DataTable
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
            ->editColumn('deleted_at', function ($tag) {
                return $tag->deleted_at ? with(new Carbon($tag->deleted_at))->format('Y-m-d') : '';
            })
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @return Tag[]|Collection
     */
    public function query()
    {
        $query = Tag::onlyTrashed()->select();
        return $this->applyScopes($query);
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
            Column::make('slug'),
            Column::make('title'),
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
        return 'tags_trash_' . date('YmdHis');
    }
}
