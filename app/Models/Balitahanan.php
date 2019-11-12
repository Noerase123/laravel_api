<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Contact eloquent model class
 */
class Balitahanan extends Model
{

    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'balitahanan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'image_url',
        'newsfeed_msg',
        'family_code'
    ];    

    public function balitahananImg() 
    {
        return $this->hasMany(BalitahananImages::class);
    }
}
