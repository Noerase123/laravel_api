<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'savings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'savings_type',
        'principal_amount',
        'remarks',
        'family_code',
    ];
}