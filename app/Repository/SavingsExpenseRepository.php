<?php

namespace App\Repository;

use App\Models\SavingsExpense;
use App\Support\Repository\EloquentRepository;

class SavingsExpenseRepository extends EloquentRepository
{

    public function __construct(Savings $savingsExpense)
    {
        parent::__construct($savingsExpense);
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