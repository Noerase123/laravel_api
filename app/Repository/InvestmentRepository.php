<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Repository;

use App\Models\Investment;
use App\Support\Repository\EloquentRepository;

class InvestmentRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\Investment $investment
     */
    public function __construct(Investment $investment)
    {
        parent::__construct($investment);
    }

    /**
     * store newly created investment to the storage
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
     * @param  $id
     *
     * @return void
     */
    public function showAll($id)
    {
        $model = $this->newModel();

        $result = $model->where('id_investment_level', $id)->get();

        if (is_null($result)) {
            return;
        }

        return $result;
    }

    public function showDetails($id, $id_inv)
    {
        $model = $this->newModel();

        return $model->where('id_investment_level', $id)
            ->where('id', $id_inv)->first();
    }

    /**
     * show the total number of registered investment
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