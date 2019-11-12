<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\Family;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use App\Events\Api\User\UserCreated;
use App\Support\Contracts\ServerErrorType;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateUserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * api endpoint
     *
     * @var string
     */
    protected $endpoint = '/api/users';

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
        $ofw = factory(User::class)->states(['ofw'])->create();
        $family = factory(Family::class)->create();

        $data = [
            'email' => $user->email,
            'password' => $password = str_repeat('a', config('app.min_password_length')),
            'confirmPassword' => $password,
            'firstname' => 'foo',
            'lastname' => 'bar',
            'type' => User::USER_TYPE_FAMILY_MEMBER,
            'familyInvitationCode' => $family->code,
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

    /**
     * a test should return a successful message containing the user data when everything was created successfully
     *
     * @return void
     */
    public function testUserCreationShouldSuccess()
    {
        $faker = $this->fakerFactory();
        $ofw = factory(User::class)->states(['ofw'])->create();
        $family = factory(Family::class)->create();

        $data = [
            'email' => $faker->email,
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'type' => User::USER_TYPE_FAMILY_MEMBER,
            'middlename' => $faker->lastName, // no formatter for middlename
            'password' => $password = $faker->password,
            'confirmPassword' => $password,
            'familyInvitationCode' => $family->code,
        ];

        // expects events
        $this->expectsEvents([
            UserCreated::class,
        ]);

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'email' => $data['email'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'middlename' => $data['middlename'],
                'type' => $data['type'],
                'status' => config('app.default_user_status'),
            ]
        ]);
    }

    /**
     * asserts given response when type of the user being created is a family member
     * and familyInvitationCode is missing from the request
     *
     * @return void
     */
    public function testThrownErrorForRequiredFamilyInvitationCode()
    {
        $faker = $this->fakerFactory();

        $data = [
            'email' => $faker->email,
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'type' => User::USER_TYPE_FAMILY_MEMBER,
            'middlename' => $faker->lastName, // no formatter for middlename
            'password' => $password = $faker->password,
            'confirmPassword' => $password,
        ];

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(422);
        $response->assertJson([
            'familyInvitationCode' => [
                'The family invitation code field is required when type is F.'
            ]
        ]);
    }

    /**
     * asserts given response when type of the user being created is a family member
     * and familyInvitationCode is not valid or does not exists
     *
     * @return void
     */
    public function testThrownErrorForInvalidFamilyInvitationCode()
    {
        $faker = $this->fakerFactory();

        $data = [
            'email' => $faker->email,
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'type' => User::USER_TYPE_FAMILY_MEMBER,
            'middlename' => $faker->lastName, // no formatter for middlename
            'password' => $password = $faker->password,
            'confirmPassword' => $password,
            'familyInvitationCode' => str_random(8),
        ];

        $response = $this->callApi($data);

        // assert
        $response->assertStatus(422);
        $response->assertJson([
            'familyInvitationCode' => [
                'The selected family invitation code is invalid.'
            ]
        ]);
    }


    /**
     * call api
     *
     * @param  array $data
     *
     * @return \Illuminate\Http\Response
     */
    protected function callApi(array $data = [])
    {
        return $this->json(
            'POST',
            $this->endpoint,
            $data
        );
    }

    /**
     * initialize faker factory instance
     *
     * @return \Faker\Generator
     */
    protected function fakerFactory()
    {
        return (new FakerFactory())->create();
    }
}
