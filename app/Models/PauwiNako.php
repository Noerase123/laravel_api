<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PauwiNako extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ofw_id','family_code','date_of_return'];
    
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'pauwi_nakos';
}
