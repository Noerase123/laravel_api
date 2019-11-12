<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Repository;

use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Support\Repository\EloquentRepository;

class FamilyMemberRepository extends EloquentRepository
{
    /**
     * {@inheritDoc}
     *
     * @param \App\Models\FamilyMember $family
     */
    public function __construct(FamilyMember $family)
    {
        parent::__construct($family);
    }

    /**
     * add the current user member to the family group
     *
     * @param  \App\Models\Family $family
     * @param  \App\Models\User   $member
     * @param  array              $attributes
     *
     * @return \App\Models\FamilyMember|null
     */
    public function addMemberToFamily(Family $family, User $member, array $attributes = [])
    {
        $model = $this->newModel();

        // append attributes
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }
        $model->user()->associate($member);
        $model->family()->associate($family);

        return ($model->save()) ? $model : null;
    }
}
