<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Contact eloquent model class
 */
class BalitahananImages extends Model
{

    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'balitahanan_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'balitahanan_id',
        'image_url',       
    ];

    public function balitahanan() 
    {
        return $this->belongsTo(Balitahanan::class, 'id');
    }
}
