<?php

namespace App\Repository;

use App\Models\PauwiNako;
use App\Models\User;
use App\Support\Repository\EloquentRepository;

class PauwinakoRepository extends EloquentRepository
{
    /**
     * Constructor of NewsRepository
     *
     * @param \App\Models\News $news
     *
     * @return 
     */
    public function __construct(PauwiNako $pauwinako)
    {
        parent::__construct($pauwinako);
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

    /**
     * Show data from model
     *
     * @return $model->all();
     */
    public function showAll()
    {
        $model = $this->newModel();

        return $model->orderBy('created_at', 'desc')
                     ->paginate(10, ['*']);
    }

    public function findFamily(User $user)
    {
        // try to fetch the \App\Models\FamilyMember object of this user
        $member = $user->family()->first();

        // if nothing was found
        if (is_null($member)) {
            return;
        }

        return $member->family()->first();
    }

    /**
     * Fetch Single data from model
     *
     * @param int $id
     *
     * @return $model->where($id)->first();
     */
    public function showSingle($id)
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

    public function notif($famCode) 
    {

        define( 'API_ACCESS_KEY', 'AAAADr_G3HM:APA91bG0SsbB9HjMxGCbq8_PC26N9RBmZXAEsoRM4e4O0VNQKBf2Dr2hpHQU_VDleqC1IhrtRYPa97TjTim-UTgUxyBkKXPzAcK169vaAC5f8LIiwx6w1bVcHVU5Th58vzXmPhiuUcEN');
        define('FIREBASE_URL', 'https://fcm.googleapis.com/fcm/send');
    
        $response = array(     
              "condition"       => "'".$famCode."' in topics ", 
              "priority"        => "high",
              "data"    => array(
                  "Family_Code"        => $famCode,
                  
            )
        );
    
        $headers = array(
              'Authorization: key='.API_ACCESS_KEY,
              'Content-Type: application/json'
        );
    
        $context = curl_init();
        curl_setopt($context, CURLOPT_URL, FIREBASE_URL);
        curl_setopt($context, CURLOPT_POST, true);
        curl_setopt($context, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($context, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($context, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($context, CURLOPT_POSTFIELDS, json_encode($response));
    
        $result = curl_exec($context);
        
        $data = explode(':', $result);

        $data_in = str_replace('}','',$data);

        return $data_in[1];

        curl_close( $context );
    
        // $fields = json_encode($response);
        // if ($result) {
        //     echo $fields . "<br>";
        //     echo "Successful API key: " . API_ACCESS_KEY;
        // }
    }

}