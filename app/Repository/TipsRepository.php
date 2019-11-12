<?php

namespace App\Repository;

use App\Models\Tips;
use App\Support\Repository\EloquentRepository;

class TipsRepository extends EloquentRepository
{

    public function __construct(Tips $tips)
    {
        parent::__construct($tips);
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

    public function showAll()
    {
        $model = $this->newModel();

        return $model->all();
    }

    public function checkId($code = null, $id = null)
    {
        $model = $this->newModel();

        if (! empty($code)) {
            $result = $model->where('category',$code)->first();
        } elseif (! empty($id)) {
            $result = $model->where('id',$id)->first();
        }

        //check if the  $model is null
        if (is_null($result)) {
            return;
        }

        return $result;
    }
}