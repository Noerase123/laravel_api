<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Repository;

use App\Models\InvestmentLevel;
use App\Support\Repository\EloquentRepository;

class InvestmentLevelRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\InvestmentLevel $level
     */
    public function __construct(InvestmentLevel $level)
    {
        parent::__construct($level);
    }

    /**
     * store newly created investment level to the storage
     *
     * @param  array $attributes
     *
     * @return void
     */
    public function store(array $attributes)
    {
        $model = $this->newModel();

        // place attribute values to the model
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        return ($model->save()) ? $model : null;
    }

    /**
     * fetching all investment levels
     *
     * @param void
     *
     * @return void
     */
    public function showAll()
    {
        $model = $this->newModel();

        return $model->paginate( 10, ['*']);
    }

    /**
     * show the total number of registered investment level
     *
     * @param void
     *
     * @return void
     */
    public function countData()
    {
        $model = $this->newModel();

        return $model->all()->count();
    }

    /**
     *
     * check investment level id if existing
     *
     * @param $id
     *
     * @return void
     */
    public function checkId($id)
    {
        $model = $this->newModel();

        $result = $model->where('id',$id)->first();

        //check if the  $model is null
        if (is_null($result)) {
            return;
        }

        return $result;
    }
}