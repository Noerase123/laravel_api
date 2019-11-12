<?php
/**
 * @author Mike Alvarez <mr.devpoop@gmail.com>
 * @abstract
 */

namespace App\Support\Repository;

use Illuminate\Database\Eloquent\Model;

/**
 * base class for all repository that use eloquent model to communicate
 * with the data storage
 */
abstract class EloquentRepository implements Contracts\Repository
{
    /**
     * repository model instance
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * create instance
     *
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->boot($model);
    }

    /**
     * retrieves repository model tabl name
     *
     * @return string
     */
    public function table()
    {
        return $this->model()->getTable();
    }

    /**
     * boot current repository and create its dependencies
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    protected function boot(Model $model)
    {
        $this->model = $model;
    }

    /**
     * retrieves the repository model instance
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return $this->model;
    }

    /**
     * retrieves and creates new instance of query model builder
     * for this repository
     *
     * @return void
     */
    protected function builder()
    {
        return $this->model()->newQuery();
    }

    /**
     * create new model instance
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function newModel()
    {
        return $this->model()->newInstance();
    }
}
