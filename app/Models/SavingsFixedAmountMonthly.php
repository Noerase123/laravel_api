<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsFixedAmountMonthly extends Models
{

    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'savings_fixed_amount_monthly';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'fixed_amount',
        'reminder_time',
        'is_reminder_checked',
        'family_code',
    ];
}