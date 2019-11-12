<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * VisionCategory eloquent model class
 */
class VisionCategory extends Model
{
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'vision_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'category',
        'family_code'
    ];
}