<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsImages extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'news_id',
        'image_url',
    ];

    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'news_images';
}
