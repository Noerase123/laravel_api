<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace Tests\Feature\Api\Me;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FetchAuthenticatedUserDataTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * asserts that api /api/me will return the authenticated user
     * data when requested
     *
     * @return void
     */
    public function testFetchingUserData()
    {
        /**
         * a user that will mock as the current
         * authenticated user and we will set the current user
         * in the request using the passport actingAs method
         */
        $user = factory(User::class)->states(['active', 'ofw'])->create();
        Passport::actingAs($user);

        // call api
        $response = $this->json('GET', '/api/me');

        // assert
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $user->getKey(),
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'middlename' => $user->middlename,
                'email' => $user->email,
                'status' => $user->status,
                'type' => $user->type,
                'owwaId' => $user->{'owwa_id'},
                'dateAdded' => $user->{User::CREATED_AT}->format(config('app.default_datetime_format'))
            ]
        ]);
    }

    /**
     * asserts that resource will response an unauthenticated error
     * when access to the resource does not contain an authentication or
     * invalid authentication
     *
     * @return void
     */
    public function testUnauthenticated()
    {
        // call api without acting from the user
        $response = $this->json('GET', '/api/me');

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Access to this resource is denied.',
        ]);
    }
}
