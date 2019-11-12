<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * VisionCategoryOthers eloquent model class
 */
class VisionCategoryOthers extends Model
{
    /**
     * Defining the table that will be used in this model
     *
     * @var string
     */
    protected $table = 'vision_cat_others';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'author_id',
        'category',
        'family_code',
    ];
}