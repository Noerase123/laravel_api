<?php

namespace App\Repository;

use App\Models\Savings;
use App\Support\Repository\EloquentRepository;

class SavingsRepository extends EloquentRepository
{

    public function __construct(Savings $savings)
    {
        parent::__construct($savings);
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