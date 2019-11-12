<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Repository\UserRepository;
use App\Events\Api\User\UserCreated;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Transformers\User\UserTransformer;
use App\Support\Contracts\ServerErrorType;
use App\Http\Requests\Api\User\StoreUserRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\Api\CustomTraits\IssueTokenTrait;
use Laravel\Passport\Client;
use App\Http\Requests\Api\User\CheckUserRequest;

/**
 * Controller class for the /users api route
 * methods defined as the ff request methods used
 *
 * POST = store
 */
class RegisterController extends Controller
{
    /**
     * users repository instance
     *
     * @var App\Repository\UserRepository
     */
    protected $users;

    private $client;

    use IssueTokenTrait;

    /**
     * create instance
     *
     * @param App\Repository\UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
        $this->client = Client::find(1);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\User\StoreUserRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        // checks email
        if ($this->users->isEmailExists($request->email)) {
            return response()
                ->json([
                    'message' => trans('validation.unique', ['attribute' => 'email']),
                    'type' => ServerErrorType::CLIENT_ERROR_CONFLICT_EMAIL,
                ], 409);
        }

        //check contact number
        if (! $this->users->isValidCellphoneFormat($request->contactNo))
        {
            return response()
                ->json([
                    'message' => trans('api.invalid_contact_number'),
                    'type' => ServerErrorType:: CLIENT_ERROR_INVALID_CONTACT_NUMBER,
                ], 409);
        }

        $contactNo = $this->users->normalizeCellphone($request->contactNo);

        // map request data
        $data = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => ucfirst($request->type),
            'contact_no' => $contactNo,
            'middlename' => $request->middlename,
            'extension_name' => $request->extensionName,
            'birth_date' => $request->birthDate,
            'owwa_id' => $request->owwaId,
            'status' => config('app.default_user_status'),
        ];

        $user = $this->users->store($data);

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.unable_to_create_user'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_USER_CREATE
                    ], 500)
            );
        }

        /**
         * trigger event
         *
         * @param \App\Models\User
         */
        event(new UserCreated($user));

        //return fractal($user, new UserTransformer)->respond(201);
        return response()
                ->json([
                    'message' =>  trans('api.success_add_user'),
                ], 201);
    }

    public function checkUser (CheckUserRequest $request)
    {

        //check contact number
        if (! $this->users->isValidCellphoneFormat($request->contactNo))
        {
            return response()
                ->json([
                    'message' => trans('api.invalid_contact_number'),
                    'type' => ServerErrorType:: CLIENT_ERROR_INVALID_CONTACT_NUMBER,
                ], 409);
        }

        $contactNo = $this->users->normalizeCellphone($request->contactNo);

        // map request data
        $data = [
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname'  => $request->lastname,
            'contact_no' => $contactNo,
            'birth_date' => $request->birthDate,
        ];

        $checkUserIfExisting = $this->users->checkAccountIfExisting($data);

        if (! $checkUserIfExisting) {
            return response()
                    ->json([
                        'message' => trans('api.no_user_found'),
                        'status' => trans(config('app.status_not_found')),
                        'data' => $data,
                    ], 409);
        }

        return response()
                ->json([
                   'message' => trans('api.user_found'),
                   'status' => trans(config('app.status_found')),
                ], 201);
    }

    /**
     * Associate social account to user
     * @param Request $request [description]
     * @param User    $user    [description]
     */
    private function addSocialAccountToUser(Request $request, User $user)
    {
        $this->validate($request, [
            'provider' => ['required', Rule::unique('social_accounts')->where(function($query) use ($user) {
                    return $query->where('user_id', $user->id);
            })],
                'provider_user_id' => 'required'
        ]);

        $user->socialAccounts()->create([
            'provider' => $request->provider,
            'provider_user_id' => $request->provider_user_id
        ]);

        return response()
            ->json(new UserResource($user), 201);
    }

    /**
     * Create user accound and Social account
     * @param  StoreUserRequest $request [description]
     * @return [type]           [description]
     */
    public function createUserAccountWithSocialAccount(StoreUserRequest $request)
    {
        // checks email
        if ($this->users->isEmailExists($request->email)) {
            return response()
                ->json([
                    'message' => trans('validation.unique', ['attribute' => 'email']),
                    'type' => ServerErrorType::CLIENT_ERROR_CONFLICT_EMAIL,
                ], 409);
        }

        // map request data
        $data = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'contact_no' => $request->contactNo,
            'extension_name' => $request->extensionName,
            'birth_date' => $request->birthDate,
            'owwa_id' => $request->owwaId,
            'status' => config('app.default_user_status'),
        ];

        $user = $this->users->store($data);

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('app.unable_to_create_user'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_USER_CREATE
                    ], 500)
            );
        }

        /**
         * trigger event
         *
         * @param \App\Models\User
        */
        event(new UserCreated($user));

        $this->addSocialAccountToUser($request, $user);

        return response()
            ->json(new UserResource($user), 201);
    }
}
