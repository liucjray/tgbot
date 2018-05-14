<?php

namespace App\Repositories\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use RuntimeException;

abstract class BaseRepository
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $criteria = [];

    /**
     * @var array
     */
    protected $scopes = [];

    /**
     * BaseRepository constructor.
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->boot();
    }

    public function boot()
    {
        //
    }

    /**
     * @return string
     */
    abstract public function model();

    /**
     * @param  mixed  $criteria
     * @return $this
     */
    public function pushCriteria($criteria)
    {
        $this->criteria[] = $criteria;
        return $this;
    }

    /**
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        $query = $this->newQuery();
        $result = $query->get($columns);
        $this->resetModel();
        return $result;
    }

    /**
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        return $this->all($columns);
    }

    /**
     * Get a generator for the given query.
     *
     * @return \Generator
     */
    public function cursor()
    {
        $query = $this->newQuery();
        $records = $query->cursor();
        $this->resetModel();

        foreach ($records as $record) {
            yield $record;
        }
    }

    /**
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function first($columns = ['*'])
    {
        $query = $this->newQuery();
        $result = $query->first($columns);
        $this->resetModel();
        return $result;
    }

    /**
     * Get an array with the values of a given column.
     *
     * @param  string  $column
     * @param  string|null  $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        $query = $this->newQuery();
        $result = $query->pluck($column, $key);
        $this->resetModel();
        return $result;
    }

    /**
     * @param  string  $column
     * @return mixed
     */
    public function value($column)
    {
        $query = $this->newQuery();
        $result = $query->value($column);
        $this->resetModel();
        return $result;
    }

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $query = $this->newQuery();
        $result = $query->paginate($perPage, $columns, $pageName, $page);
        $this->resetModel();
        return $result;
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $query = $this->newQuery();
        $result = $query->simplePaginate($perPage, $columns, $pageName, $page);
        $this->resetModel();
        return $result;
    }

    /**
     * @param  \Closure  $scope
     * @return $this
     */
    public function scopeQuery(\Closure $scope)
    {
        $this->scopes[] = $scope;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newQuery()
    {
        $query = $this->model->newQuery();
        $this->applyCriteria($query);
        $this->applyScope($query);
        return $query;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    protected function applyScope($query)
    {
        foreach ($this->scopes as $scope) {
            $scope($query);
        }
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    protected function applyCriteria($query)
    {
        foreach ($this->criteria as $criteria) {
            $criteria->apply($query, $this);
        }
    }

    /**
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->scopeQuery(function ($query) use ($column, $direction) {
            $query->orderBy($column, $direction);
        });
        return $this;
    }

    /**
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {

            throw new RuntimeException("Class {$this->model()} must be an instance of " . Model::class);
        }
        return $this->model = $this->app->make($this->model());
    }

    /**
     * @return $this
     */
    public function resetModel()
    {
        $this->criteria = [];
        $this->scopes = [];
        return $this;
    }
}