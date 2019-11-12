<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Events\Api\User\FetchingUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Transformers\User\UserTransformer;
use App\Transformers\User\UserCountTransformer;
use App\Support\Contracts\ServerErrorType;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Repository\UserRepository;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\ArraySerializer;

class UserController extends Controller
{
    /**
     * create instance
     */
    public function __construct(UserRepository $users)
    {
        $this->middleware('auth:api');
        $this->users = $users;
    }

    /**
     * show current authenticated user information
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // get user from request
        $user = $request->user();

        /**
         * trigger event
         *
         * @param \App\Models\User $user
         */
        event(new FetchingUserData($user));

        return fractal($user, new UserTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(200);
    }

    /**
     * show all user information
     *
     *  @return \Illuminate\Http\JsonResponse
    */
    public function showAllUsers()
    {
        $user = $this->users->showAll(User::USER_TYPE_OFW);

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.failed_to_fetch'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_FETCH_USER
                    ], 500)
            );
        }

        return  fractal($user, new UserTransformer)
                        ->serializeWith(new ArraySerializer)
                        ->paginateWith(new IlluminatePaginatorAdapter($user))
                        ->respond(201);
    }

    /**
     * show all admin information
     *
     *  @return \Illuminate\Http\JsonResponse
    */
    public function showAllAdmin()
    {
        $user = $this->users->showAll(User::USER_TYPE_ADMIN);

        return fractal($user, new UserTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }

    /**
     * show user information by id
     *
     *  @return \Illuminate\Http\JsonResponse
    */
    public function showUser($id)
    {
        //fetch user's details using $id
        $user = $this->users->showUser($id, User::USER_TYPE_OFW);

        //check if the $user is null
        if (is_null($user)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        return fractal($user, new UserTransformer)->respond(201);
    }

    /**
     * show admin information by id
     *
     *  @return \Illuminate\Http\JsonResponse
    */
    public function showAdmin($id)
    {
        //fetch admins's details using $id
        $user = $this->users->showUser($id, User::USER_TYPE_ADMIN);

        //check if the $user is null
        if (is_null($user)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        return fractal($user, new UserTransformer)->respond(201);
    }

    /**
     * show active user count
     *
     *  @return \Illuminate\Http\JsonResponse
     */
    public function showAllCount()
    {
        $userCount = $this->users->countData();

        if (! $userCount) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.unable_to_count'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_COUNT_USER
                    ], 500)
            );
        }

        return fractal($userCount, new UserCountTransformer)->respond(201);
    }

    /**
     * update user information
     *
     *
     * @param Illuminate\Http\Request $request, $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //check user id if existing
        $user = $this->users->checkId($id);

        // checks email
        if ($this->users->isEmailExists($request->email)) {
            return response()
                ->json([
                    'message' => trans('validation.unique', ['attribute' => 'email']),
                    'type' => ServerErrorType::CLIENT_ERROR_CONFLICT_EMAIL,
                ], 409);
        }

        //check $user if null
        if (is_null($user)) {
            return response()
                ->json([
                    'message' => trans('api.no_user_found'),
                ], 404);
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

        //normalize contact number format
        $contactNo = $this->users->normalizeCellphone($request->contactNo);

        //map request data
        $data = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'contact_no' => $contactNo,
            'extension_name' => $request->extensionName,
            'birth_date' => $request->birthDate,
        ];

        $user->update($data);

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.unable_to_update_user'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_USER_UPDATE
                    ], 500)
            );
        }

        return fractal($user, new UserTransformer)->respond(201);
    }

    /**
     * update login user information
     *
     *  @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(UpdateUserRequest $request)
    {
        // get user from request
        $user = $request->user();

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

        //normalize contact number format
        $contactNo = $this->users->normalizeCellphone($request->contactNo);

        //map request data
        $data = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_no' => $contactNo,
            'middlename' => $request->middlename,
            'extension_name' => $request->extensionName,
            'birth_date' => $request->birthDate,
        ];

        $user->update($data);

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.unable_to_update_user'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_USER_UPDATE
                    ], 500)
            );
        }

       //return fractal($user, new UserTransformer)->respond(201);
       return response()
                ->json([
                    'message' =>  trans('api.update_user'),
                ], 201);
    }

    /**
     * update user status
     *
     *
     * @param Illuminate\Http\Request $request, $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {

        $user = $this->users->checkId($id);

        if (is_null($user)) {
            return response()
                ->json([
                    'message' => trans('api.no_user_found'),
                ], 404);
        }

        $user->update(array('status' => $request->status));

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.unable_to_update_user'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_USER_UPDATE
                    ], 500)
            );
        }

        return response()
                ->json([
                    'message' =>  trans('api.update_user_status'),
                ], 201);
    }

    /**
     * delete user
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        //check the user if existing
        $user = $this->users->checkId($id);

        if (is_null($user)) {
            return response()
                ->json([
                    'message' => trans('api.no_user_found'),
                ], 404);
        }

        //update column is_deleted to Y
        $user->update(array('is_deleted' => trans('api.is_deleted_yes')));

        if (! $user) {
            throw new HttpResponseException(
                response()
                    ->json([
                        'message' => trans('api.unable_to_delete'),
                        'type' => ServerErrorType::SERVER_ERROR_FAILED_DELETE_USER
                    ], 500)
            );
        }

        return response()
                ->json([
                    'message' =>  trans('api.delete_user'),
                ], 201);
    }

    public function searchUser(Request $request)
    {
        //check contact number
        if ($request->contactNo != null && ! $this->users->isValidCellphoneFormat($request->contactNo))
        {
            return response()
                ->json([
                    'message' => trans('api.invalid_contact_number'),
                    'type' => ServerErrorType:: CLIENT_ERROR_INVALID_CONTACT_NUMBER,
                ], 409);
        }

        //normalize contact number format
        $contactNo = $this->users->normalizeCellphone($request->contactNo);

        $data = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email' => $request->email,
            'contact_no' => $contactNo,
            'middlename' => $request->middlename,
        ];

        $dataFilter = array_filter($data);  

        $userType = null;

        if (ucfirst($request->type) == 'O') {
            $userType = User::USER_TYPE_OFW;
            
        } elseif (ucfirst($request->type) == 'A') {
            $userType = User::USER_TYPE_ADMIN;
        }

        if (is_null($userType)) {
            return response()
                ->json([
                    'message' => trans('api.invalid_user_type'),
                ], 404);
        }

        $checkUserIfExisting = $this->users->checkAccountIfExisting($dataFilter, $userType ,true);

        if (is_null($checkUserIfExisting)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        return fractal($checkUserIfExisting, new UserTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->paginateWith(new IlluminatePaginatorAdapter($checkUserIfExisting))
                    ->respond(201);
    }
}