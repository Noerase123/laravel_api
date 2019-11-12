<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Models;

use App\Support\Contracts\Status;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\SocialAccount;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
use Carbon\Carbon;

/**
 * user eloquent model class
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * user type values
     *
     * @const
     */
    const USER_TYPE_FAMILY_MEMBER = 'F';
    const USER_TYPE_OFW = 'O';
    const USER_TYPE_ADMIN = 'A';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'extension_name',
        'status',
        'type',
        'email',
        'password',
        'birth_date',
        'is_deleted'
    ];

    protected $dates = ['birth_date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function socialAccounts(){
        return $this->hasMany(SocialAccount::class);
    }

    public function findForPassport ($username) {
        return $user = (new User)->where('email', $username)->first();
    }

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => Status::STATUS_PENDING
    ];

    /**
     * family model relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function family()
    {
        return $this->hasMany(FamilyMember::class, 'user_id');
    }

    public function comments()
    {
        return $this->belongsTo(Comment::class, 'author_id');
    }

    /**
     * checks if current user instance is type of family member
     *
     * @return boolean
     */
    public function isOfw()
    {
        return ($this->getAttribute('type') == static::USER_TYPE_OFW);
    }

    /**
     * checks if current user instance is type of family member
     *
     * @return boolean
     */
    public function isFamilyMember()
    {
        return ($this->getAttribute('type') == static::USER_TYPE_FAMILY_MEMBER);
    }

    public function isAdmin()
    {
        return ($this->getAttribute('type') == static::USER_TYPE_ADMIN);
    }

    public function getFullNameAttribute($value)
    {
       return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['firstname'] = ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['lastname'] = ucfirst($value);
    }

    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middlename'] = ucfirst($value);
    }

}
