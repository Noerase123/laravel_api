<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateUserWithSocialAccountTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * api endpoint
     *
     * @var string
     */
    protected $endpoint = '/api/register_with_social';

    /**
     * test validation constraints for required fields
     *
     * @return void
     */
    public function testRequiredFieldsValidationConstraint()
    {
        // call api
        $response = $this->callApi([]);

        $expectedRequiredFields = [
            'firstname',
            'lastname',
            'email',
            'password',
            'confirmPassword',
            'type',
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
     * test email format validation constraint when given email is invalid format
     *
     * @return void
     */
    public function testEmailFormatValidationConstraint()
    {
        $data = ['email' => 'foo'];

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(422);
        $response->assertJson([
            'email' => ['The email must be a valid email address.'],
        ]);
    }

    /**
     * test thrown error to response when email provided in the request conflicts to the current
     * users data and should retunr 409 Conflict status
     *
     * @return void
     */
    public function testThrownErrorWhenEmailIsAlreadyExists()
    {
        // create a temp user
        $user = factory(User::class)->create();

        $data = [
            'email' => $user->email,
            'password' => $password = str_repeat('a', config('app.min_password_length')),
            'confirmPassword' => $password,
            'firstname' => 'foo',
            'lastname' => 'bar',
            'type' => User::USER_TYPE_FAMILY_MEMBER
        ];

        $response = $this->callApi($data);

        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'The email has already been taken.',
            'type' => ServerErrorType::CLIENT_ERROR_CONFLICT_EMAIL
        ]);
    }

    /**
     * test required validation for owwa id if given type is value of OFW
     *
     * @return void
     */
    public function testRequiredOwwaValidationWhenTypeIsOFW()
    {
        $data = [
            'type' => User::USER_TYPE_OFW,
        ];

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(422);
        $response->assertJson([
            'owwaId' => ['The OWWA ID field is required if given type is OFW.']
        ]);
    }

    /**
     * test validation for password and confirmation should match both values
     * and must return an error when values does not match
     *
     * @return void
     */
    public function testSamePasswordAndConfirmPasswordValidation()
    {
        $data = [
            'password' => 'foo',
            'confirmPassword' => 'bar'
        ];

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(422);
        $response->assertJson([
            'confirmPassword' => ['The confirm password and password must match.']
        ]);
    }

    /**
     * test password length validation should return an error when given password character length
     * does not match the application minimum password length
     *
     * @return void
     */
    public function testPasswordLengthValidation()
    {
        $data = [
            'password' => 'foo'
        ];

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(422);
        $response->assertJson([
            'password' => [
                str_replace([':attribute', ':min'], ['password', config('app.min_password_length')], 'The :attribute must be at least :min characters.')
            ]
        ]);
    }

    public function testCreateUserWithSocialAcount(){
        
    }
}
