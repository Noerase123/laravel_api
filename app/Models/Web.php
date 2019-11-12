<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Web extends Model
{

    protected $fillable = [
        'category',
        'title',
        'content',
    ];
}