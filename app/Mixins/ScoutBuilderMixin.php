<?php


namespace App\Mixins;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ScoutBuilderMixin
{
    public function customPagination()
    {
        return function ($perPage = null, $pageName = 'page', $page = null) {
            $engine = $this->engine();

            $page = $page ?: Paginator::resolveCurrentPage($pageName);

            $perPage = $perPage ?: $this->model->getPerPage();

            $results = $this->model->newCollection($engine->map(
                $this, $rawResults = $engine->paginate($this, $perPage, $page), $this->model
            )->all());

            $paginator = (new LengthAwarePaginator($results, $engine->getTotalCount($rawResults), $perPage, $page, [
                'path'     => addSlash2Url(Paginator::resolveCurrentPath()),
                'pageName' => $pageName,
            ]));

            $paginator->setPath(addSlash2Url($paginator->path()));

            return $paginator;
        };
    }
}
