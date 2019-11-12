<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace Tests\Feature\Api\Me\Family;

use Tests\TestCase;
use App\Models\User;
use App\Models\Family;
use Laravel\Passport\Passport;
use App\Repository\FamilyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FetchFamilyDataTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * asserts json response when trying to fetch the family data
     *
     * @return void
     */
    public function testFetchFamilyData()
    {
        $family = factory(Family::class)->create();
        $user = factory(User::class)->states(['active', 'ofw'])->create();
        $group = $this->app->make(FamilyRepository::class)->addMemberToFamily($family, $user);
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/me/family');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $family->getKey(),
                'name' => $family->name,
                'code' => $family->code,
                'data' => $family->data,
                'dateAdded' => $family->{Family::CREATED_AT}->format(config('app.default_datetime_format')),
                'links' => [
                    'self' => route('me.show_family'),
                    'members' => ''
                ]
            ]
        ]);
    }

    /**
     * asserts response should return a forbidden status
     * when user trying to access the resouce is not a valid user
     *
     * @return void
     */
    public function testResponseWhenUserIsFamilyMember()
    {
        $user = factory(User::class)->states(['active', 'family_member'])->create();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/me/family');

        $response->assertStatus(403);
        $response->assertJson([
            'message' => trans('api.resource_forbidden'),
        ]);
    }

    /**
     * asserts response should return a not found status
     * when trying to access the resource which does not exists
     * for the current user
     *
     * @return void
     */
    public function testResponseWhenNoFamilyWasCreated()
    {
        $user = factory(User::class)->states(['active', 'ofw'])->create();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/me/family');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => trans('api.resource_not_found'),
        ]);
    }
}
