<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Repository;

use App\Models\User;
use App\Models\Family;
use App\Support\Repository\EloquentRepository;

/**
 * family model eloquent repository class
 */
class FamilyRepository extends EloquentRepository
{
    /**
     * {@inheritDoc}
     *
     * @param \App\Models\Family $family
     */
    public function __construct(Family $family)
    {
        parent::__construct($family);
    }

    /**
     * find the family object using a code string
     *
     * @param  string $code
     *
     * @return \App\Models\Family|null
     */
    public function findFamilyByCode($code)
    {
        return $this->builder()
            ->where('code', $code)
            ->first();
    }

    /**
     * find the family of this user
     *
     * @param  \App\Models\User $user
     *
     * @return \App\Models\Family|null
     */
    public function findFamily(User $user)
    {
        // try to fetch the \App\Models\FamilyMember object of this user
        $member = $user->family()->first();

        // if nothing was found
        if (is_null($member)) {
            return;
        }

        return $member->family()->first();
    }

    /**
     * find family members from a given family instance
     *
     * @param  \App\Models\Family $family
     *
     * @return \Illuminate\Support\Collection
     */
    public function findFamilyMembers(Family $family)
    {
        return $family
            ->members()
            ->with('user')
            ->get();
    }

    public function findFamilyMembersWithId(Family $family, $id)
    {
        
        return $family
                ->members()
                ->with('user')
                ->where('user_id','!=', $id)
                ->get();
    }

    /**
     * create a family invitation code for the current ofw
     *
     * @param  array              $data
     * @param  \App\Models\Family $family
     *
     * @return \App\Models\Family|null
     */
    public function createCode(array $data = [], Family $family = null)
    {
        if (is_null($family)) {
            $family = $this->newModel();
        }

        $family->code = $this->generateCode();
        $family->data = $data;

        return ($family->save()) ? $family : null;
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
        return app(FamilyMemberRepository::class)->addMemberToFamily($family, $member, $attributes);
    }

    /**
     * generate a new code
     *
     * @return string
     */
    protected function generateCode()
    {
        $length = config('app.invitation_code_length');
        if ($length <= 32) {
            $hash = md5(mt_rand() . str_random($length));
            return substr($hash, 0, $length);
        }
        return str_random($length);
    }
}
