<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginUserTest extends TestCase
{
    /**
     * api endpoint
     *
     * @var string
     */
    protected $endpoint = '/api/login';

    /**
     * Required fields test
     *
     * @return void
     */
    public function testRequiredFields()
    {
        $response = $this->callApi([]);

        $expectedRequiredFields = [            
            'email',
            'password',           
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
     * Test for login with correct credentials
     *
     * @return void
     */
    public function testLoginWithCorrectCredentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'samplepassword'),
        ]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test for login with incorrect credentials
     *
     * @return void
     */
    public function testLoginWithIncorrectCredentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('samplepassword'),
        ]);
        
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
        
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
