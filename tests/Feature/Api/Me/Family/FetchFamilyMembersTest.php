<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace Tests\Feature\Api\Me\Family;

use Tests\TestCase;
use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
use Laravel\Passport\Passport;
use App\Repository\FamilyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FetchFamilyMembersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * asserts response data when user requesting is a ofw
     * should expect the arrangement must be the ofw first
     *
     * @return void
     */
    public function testFetchAllFamilyMembersOfOfw()
    {
        $family = factory(Family::class)->create();
        $user = factory(User::class)->states(['ofw', 'pending'])->create();
        $group = $this->app->make(FamilyRepository::class)->addMemberToFamily($family, $user);
        Passport::actingAs($user);

        // create family members
        factory(User::class, 5)->states(['family_member', 'pending'])
            ->create()
            ->each(function ($user) use ($family) {
                $this->app->make(FamilyRepository::class)->addMemberToFamily($family, $user);
            });

        // fetch all the family members and create an expected result
        $expected = [];
        $family->members()->get()->sortByDesc(function ($group) use ($user) {
            $member = $group->user;
            if ($member->isOfw()) {
                return 0;
            } elseif ($member->getKey() === $user->getKey()) {
                return 1;
            } else {
                return -1;
            }
        })->each(function ($group) use (&$expected) {
            $user = $group->user;
            $expected[] = [
                'dateAdded' => $group->{FamilyMember::CREATED_AT}->format(config('app.default_datetime_format')),
                'user' => [
                    'data' => [
                        'id' => $user->getKey(),
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'middlename' => $user->middlename,
                        'email' => $user->email,
                        'status' => $user->status,
                        'type' => $user->type,
                        'owwaId' => $user->{'owwa_id'},
                        'dateAdded' => $user->{User::CREATED_AT}->format(config('app.default_datetime_format')),
                    ]
                ]
            ];
        });

        // call api
        $response = $this->json('GET', '/api/me/family/members');

        // assert
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $expected
        ]);
    }

    /**
     * asserts response data when user requesting is a family listing should given priority
     * to the current user then followed by the ofw of the family
     *
     * @return void
     */
    public function testFetchAllFamilyMembersOfFamilyMember()
    {
        $family = factory(Family::class)->create();
        $user = factory(User::class)->states(['ofw', 'pending'])->create();
        $group = $this->app->make(FamilyRepository::class)->addMemberToFamily($family, $user);

        // create family members
        $members = factory(User::class, 5)->states(['family_member', 'pending'])
            ->create();
        $members->each(function ($user) use ($family) {
            $this->app->make(FamilyRepository::class)->addMemberToFamily($family, $user);
        });;

        // substitute the current user with the firsdt member of the family
        Passport::actingAs($user = $members->first());

        // fetch all the family members and create an expected result
        $expected = [];
        $family->members()->get()->sortByDesc(function ($group) use ($user) {
            $member = $group->user;
            if ($member->isOfw()) {
                return 0;
            } elseif ($member->getKey() === $user->getKey()) {
                return 1;
            } else {
                return -1;
            }
        })->each(function ($group) use (&$expected) {
            $user = $group->user;
            $expected[] = [
                'dateAdded' => $group->{FamilyMember::CREATED_AT}->format(config('app.default_datetime_format')),
                'user' => [
                    'data' => [
                        'id' => $user->getKey(),
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'middlename' => $user->middlename,
                        'email' => $user->email,
                        'status' => $user->status,
                        'type' => $user->type,
                        'owwaId' => $user->{'owwa_id'},
                        'dateAdded' => $user->{User::CREATED_AT}->format(config('app.default_datetime_format')),
                    ]
                ]
            ];
        });

        // call api
        $response = $this->json('GET', '/api/me/family/members');

        // assert
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $expected
        ]);
    }
}
