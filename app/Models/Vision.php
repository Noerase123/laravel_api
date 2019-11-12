<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Vision eloquent model class
 */
class Vision extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'target_amount',
        'target_cat_id',
        'vision_cat_others_id',
        'target_date',
        'description',
        'family_code'
    ];

    /**
     * Get dates from unix timestamp
     *
     * @return array
     */
     protected $dates = ['target_date'];
}