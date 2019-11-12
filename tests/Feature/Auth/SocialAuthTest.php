<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\SocialAccount;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SocialAuthTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * api endpoint
     *
     * @var string
     */
    protected $endpoint = '/api/social_auth';

    /**
     * Test for required fields
     *
     * @return void
     */
    public function testRequiredFields()
    {
        $response = $this->callApi([]);

        $expectedRequiredFields = [            
            'email',
            'provider',
            'provider_user_id'           
        ];

        $errors = [];

        foreach ($expectedRequiredFields as $field) {
            $errors[$field] = [sprintf('The %s field is required.', str_replace('_', ' ', Str::snake($field)))];
        }

        // assert
        $response->assertStatus(422);
        $response->assertJson($errors);   
    }  

    /**
     * Test for login with social account correctly
     *
     * @return void
     */
    public function testLoginWithSocialAccountCorrectly()
    {
        $user = factory(SocialAccount::class)->create([
            'provider' => '',
            'provider_user_id' => ''
        ]);
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'provider' => '',
            'provider_user_id' => ''
        ]);
        //$response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }
    

    /**
     * Test for login with social account incorrectly
     *
     * @return void
     */
    public function testLoginWithSocialAccountIncorrectly()
    {
        //
    }
}
