<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipsCategory extends Model
{
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'tips_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}