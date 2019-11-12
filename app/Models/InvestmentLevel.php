<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Investment level eloquent model class
 */
class InvestmentLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_level',
        'investment_name',
        'min_range',
        'max_range'
    ];
}