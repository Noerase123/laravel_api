<?php

namespace App\Repository;

use App\Models\News;
use App\Models\NewsCategory;
use Carbon\Carbon;
use App\Support\Repository\EloquentRepository;

class NewsRepository extends EloquentRepository
{
    /**
     * Constructor of NewsRepository
     *
     * @param \App\Models\News $news
     *
     * @return 
     */
    public function __construct(News $news)
    {
        parent::__construct($news);
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

    public function showAllType() {

        $model = $this->newModel();

        return $model->orderBy('created_at','desc')->get();
    }

    /**
     * Show blog data from model
     *
     * @return $model->all();
     */
    public function getType($itemtype)
    {
        $model = $this->newModel();

        if (is_null($itemtype)) {
            return;
        }

        return $model->orderBy('created_at', 'desc')
                    ->where('type', $itemtype)
                    ->get();
    }

    /**
     * Fetch Single data from model
     *
     * @param int $id
     *
     * @return $model->where($slug)->first();
     */
    public function showSingle($slug)
    {
        $model = $this->newModel();
        
        $result = $model->where('slug', $slug)
                        ->first();

        //check if the  $model is null
        if (is_null($result)) {
            return;
        }

        return $result;
    }

    /**
     * Fetch Single data from model
     *
     * @param int $id
     *
     * @return $model->where($id)->first();
     */
    public function showSingleAdmin($id)
    {
        $model = $this->newModel();
        
        $result = $model->where('id',$id)
                        ->first();

        //check if the  $model is null
        if (is_null($result)) {
            return;
        }

        return $result;
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

    public function newsCategory() 
    {
        return NewsCategory::all();
    }

    public function newsCat() 
    {
        $posts = $this->newsCategory();
        
        foreach ($posts as $post) {
            $data[] = $post->name;
        }

        return $data;
    }

    public function addNewsCat(array $attributes)
    {
        $model = new NewsCategory;
        
        // place attribute values to the model
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        return ($model->save()) ? $model : null;
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

    public function removeTags($data) 
    {
        $content = strip_tags($data);
        $content = str_replace('\n',' ',$content);
        $content = str_replace('&nbsp;','',$content);

        return $content;
    }

    public function strRep($string) 
    {
        $res = str_replace(' ','-',$string);
        $res = strtolower($res);
        
        return $res;
    }

    public function timeStamp($name) 
    {
        $current_timestamp = Carbon::now()->timestamp;

        $data_exp = explode(' ',$name);
        
        foreach ($data_exp as $key => $value) {
            $datas[] = $value;
        }

        $name = implode("_",$datas);

        $slug = $name.'-'.$current_timestamp;
        
        return $slug;
    }

    public function statusReq($request) 
    {
        if ($request == 'Draft' || $request == 'D') {
            return 'D';
        }
        else if ($request == 'Activate' || $request == 'A') {
            return 'A';
        }
        else if ($request == 'Deactivate' || $request == 'DA'){
            return 'DA';
        }
    }

}