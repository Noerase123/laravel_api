<?php

namespace App\Repository;

use App\Models\NewsCategory;
use App\Support\Repository\EloquentRepository;

class NewsCategoryRepository extends EloquentRepository
{
    /**
     * Constructor of NewsRepository
     *
     * @param \App\Models\News $news
     *
     * @return 
     */
    public function __construct(NewsCategory $newsCateg)
    {
        parent::__construct($newsCateg);
    }

    /**
     * Store data from model
     *
     * @param array $attributes
     *
     * @return $model
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

    public function showAll() {

        $model = $this->newModel();

        return $model->orderBy('created_at','desc')->get();
    }

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

    public function slugData($data) 
    {
        $data_exp = explode(' ',$data);
        
        foreach ($data_exp as $key => $value) {
            $str = substr($value,0,1);
            $datas[] = $str;
        }

        $data_imp = implode("",$datas);
        $data_imp = strtoupper($data_imp);

        return $data_imp;
    }

}