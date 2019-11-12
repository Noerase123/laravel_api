<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tips extends Model
{
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'tips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'content',
    ];

    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = ucfirst($value);
    }
}