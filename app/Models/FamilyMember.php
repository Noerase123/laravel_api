<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Models;

use App\Support\Contracts\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * family member eloquent model class
 */
class FamilyMember extends Model
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => Status::STATUS_PENDING
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alias',
        self::CREATED_AT,
    ];

    /**
     * family model relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }

    /**
     * family member model relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
