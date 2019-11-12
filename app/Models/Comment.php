<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Comment eloquent model class
 */
class Comment extends Model
{

    protected $fillable = [
        'author_id',
        'content',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}