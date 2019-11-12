<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsAllocation extends Models
{

    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'savings_allocation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'savings_id',
        'vision_id',
        'amount',
    ];
}