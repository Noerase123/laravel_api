<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Transformers\User;

use App\Models\User;
use App\Support\Resource\ResourceItem;
use App\Models\SocialAccount;
use League\Fractal\TransformerAbstract;


class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($user)
    {
        return [
            'id' => $user->getKey(),
            'firstname' => ucfirst($user->firstname),
            'lastname' => ucfirst($user->lastname),
            'middlename' => ucfirst($user->middlename),
            'extension_name' => ucfirst($user->extension_name),
            'email' => $user->email,
            'contactNo' => $user->contact_no,
            'status' => $user->status,
            'type' => $user->type,
            'birthdate' => $user->birth_date->format(config('app.default_birthdate_format')),
            'owwaId' => $user->{'owwa_id'},
            'dateAdded' => $user->{User::CREATED_AT}->format(config('app.default_datetime_format')),
        ];
    }

}
