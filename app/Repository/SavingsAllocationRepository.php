<?php

namespace App\Repository;

use App\Models\SavingsAllocation;
use App\Support\Repository\EloquentRepository;

class SavingsAllocationRepository extends EloquentRepository
{

    public function __construct(Savings $savingsAllocation)
    {
        parent::__construct($savingsAllocation);
    }

    public function store(array $attributes)
    {
        $model = $this->newModel();

        // place attribute values to the model
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        return ($model->save()) ? $model : null;
    }
}