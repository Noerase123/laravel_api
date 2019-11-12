<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Family;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\FamilyRepository;
use App\Transformers\Family\FamilyTransformer;
use App\Http\Resources\User\UserResource;
use App\Events\Api\User\FetchingUserData;
use App\Transformers\User\UserTransformer;
use App\Support\Contracts\ServerErrorType;
use App\Http\Requests\Api\User\StoreUserRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Repository\UserRepository;

class FamilyController extends Controller
{
    /**
     * create instance
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * show family resource
     *
     * @param  \Illuminate\Http\Request         $request
     * @param  \App\Repository\FamilyRepository $families
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, FamilyRepository $families)
    {
        $user = $request->user();
        $family = $families->findFamily($user);

        // if user has already a code defined return a status of 302
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.resource_not_found'),
                ], 404);
        }

        return fractal($family, new FamilyTransformer)->respond(200);
    }
}
