<?php

namespace App\Repository;

use App\Models\VisionCategory;
use App\Support\Repository\EloquentRepository;
use Illuminate\Support\Facades\DB;

class VisionCategoryRepository extends EloquentRepository
{
    public function __construct(VisionCategory $visionCategory)
    {
        parent::__construct($visionCategory);
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

    public function countData()
    {
        $model = $this->newModel();

        return $model->get();
    }
    
}