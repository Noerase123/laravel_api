<?php

namespace App\Repository;

use App\Models\Vision;
use App\Support\Repository\EloquentRepository;
use Illuminate\Support\Facades\DB;

class VisionRepository extends EloquentRepository
{
    public function __construct(Vision $vision)
    {
        parent::__construct($vision);
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

    public function showAll($familyCode, $id = null)
    {
        $model = $this->newModel();

        $result = $model->leftJoin('vision_category', 'visions.target_cat_id', '=', 'vision_category.category_id')
            ->leftJoin('vision_cat_others', 'visions.target_cat_id', '=', 'vision_cat_others.category_id');

        $result->select(
            DB::raw (
             '(CASE WHEN vision_category.category_id <= 5 THEN vision_category.category_id ELSE vision_cat_others.category_id END) as id,
             (CASE WHEN vision_category.category_id <= 5 THEN vision_category.category ELSE vision_cat_others.category END) AS category,
             COUNT(CASE WHEN vision_category.category_id <= 5 THEN vision_category.category ELSE vision_cat_others.category END) AS item,
             SUM(visions.target_amount) AS amount'
        ))->where('visions.family_code', $familyCode);

        if (!empty($id)){
            return $result->where(
                DB::raw (
                '(CASE WHEN vision_category.category_id <= 5 THEN vision_category.category_id ELSE vision_cat_others.category_id END)'
                ),'=',$id)
                ->groupBy( 'vision_cat_others.category_id' , 'vision_category.category_id')
                ->orderBy('visions.created_at')
                ->first();
         }

        return $result->groupBy( 'vision_cat_others.category_id' , 'vision_category.category_id')
            ->orderBy('visions.created_at')
            ->get();
    }

    public function showAllVisions ($familyCode, $id = null)
    {
        $model = $this->newModel();

        $result = $model->where('family_code', $familyCode);

        if (!empty($id)) {
            $withId = $result->where('target_cat_id', $id)
                ->orderBy('id')
                ->get();

            if (is_null($withId)) {
                return;
            }

            return $withId;
        }

        return $result->orderBy('id')->get();
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

    public function sumAllVision ($familyCode)
    {
        $model = $this->newModel();

        $result = $model->select(DB::raw (
            'SUM(target_amount) as totalAmount'
            ))->where('family_code', $familyCode)->first();

        if (is_null($result)) {
            return;
        }

        return $result;
    }

    public function countData()
    {
        $model = $this->newModel();

        $result = $model->select(
            DB::raw (
                'COUNT(CASE WHEN target_cat_id = 1  THEN 1 ELSE NULL END) AS personal ,
                COUNT(CASE WHEN target_cat_id = 2  THEN 1 ELSE NULL END) AS car,
                COUNT(CASE WHEN target_cat_id = 3  THEN 1 ELSE NULL END) AS housing,
                COUNT(CASE WHEN target_cat_id = 4  THEN 1 ELSE NULL END) AS education,
                COUNT(CASE WHEN target_cat_id = 5  THEN 1 ELSE NULL END) AS debt ,
                COUNT(CASE WHEN target_cat_id > 5   THEN 1 ELSE NULL END) AS others,
                COUNT(id) AS total'
                
            ));

        return $result->get();
    }

    public function countAllVision($id = null)
    {
        $model = $this->newModel();
       
        if (is_null($id)){
            return $model->count();
        }

        $result = $model->select(
            DB::raw (
                'COUNT(target_cat_id) as total' 
            ))
            ->where('target_cat_id', $id);

        return $result->first();
    }

    public function countDataOthers($id)
    {
        $model = $this->newModel();

        $result = $model->select(
            DB::raw (
                'COUNT(CASE WHEN target_cat_id > '. $id .' THEN 1 ELSE NULL END) as total' 
            ));

        return $result->first();
    }

}