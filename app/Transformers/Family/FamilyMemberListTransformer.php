<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Family;

use App\Models\FamilyMember;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;


class FamilyMemberListTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(FamilyMember $member)
    {
        return [
            'id' => $member->user->getKey(),
            'firstname' => ucfirst($member->user->firstname),
            'lastname' => ucfirst($member->user->lastname),
            'middlename' =>  ucfirst($member->user->middlename),
            'extension_name' => ucfirst($member->user->extension_name),
            'email' =>  $member->user->email,
            'status' =>  $member->user->status,
            'birthdate' => $member->user->birth_date->format(config('app.default_birthdate_format')),
            'type' => $member->user->type,
            'dateAdded' => $member->{FamilyMember::CREATED_AT}->format(config('app.default_datetime_format'))
        ];
    }
}
