<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Transformers\Family;

use App\Models\FamilyMember;
use League\Fractal\TransformerAbstract;
use App\Transformers\User\UserTransformer;

class FamilyMemberTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'user'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(FamilyMember $member)
    {
        return [
            'dateAdded' => $member->{FamilyMember::CREATED_AT}->format(config('app.default_datetime_format'))
        ];
    }

    /**
     * include user
     *
     * @param  \App\Models\FamilyMember $member
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(FamilyMember $member)
    {
        return $this->item($member->user, new UserTransformer);
    }
}
