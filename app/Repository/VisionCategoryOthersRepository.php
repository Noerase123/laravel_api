<?php

namespace App\Repository;

use App\Models\VisionCategoryOthers;
use App\Support\Repository\EloquentRepository;

class VisionCategoryOthersRepository extends EloquentRepository
{
    public function __construct(VisionCategoryOthers $visionCategory)
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

        return $model->all()->count();
    }

    public function showAll($familyCode)
    {
        $model = $this->newModel();

        return $model->select("category_id","category")->where('family_code', $familyCode);
    }

    public function countCategoryPerFamily($familyCode)
    {
        $model = $this->newModel();

        return $model->all()
            ->where('family_code', $familyCode)
            ->count();
    }
}