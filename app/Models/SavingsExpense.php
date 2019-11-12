<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsExpense extends Models
{

    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'savings_expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'savings_id',
        'expense_title',
        'amount',
    ];
}