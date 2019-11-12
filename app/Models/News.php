<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author',
        'content',
        'type',
        'is_deleted',
        'status',
        'slug'
    ];
    
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'news';
    
}
