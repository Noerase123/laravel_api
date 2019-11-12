<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'data',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'data' => []
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json'
    ];

    /**
     * family members model relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(FamilyMember::class, 'family_id');
    }
}
