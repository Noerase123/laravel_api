<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Investment eloquent model class
 */
class Investment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_investment_level',
        'initial_capital',
        'min_estimated_return',
        'max_estimated_return',
        'risk_level',
        'description',
        'url',
        'banner',
        'short_description',
    ];
}
