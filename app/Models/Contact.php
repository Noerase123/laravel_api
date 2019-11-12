<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Contact eloquent model class
 */
class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_name',
        'hotline',
        'address'
    ];
}
