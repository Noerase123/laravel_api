<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogsCategory extends Model
{
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'family_code',
        'activity',
    ];
}