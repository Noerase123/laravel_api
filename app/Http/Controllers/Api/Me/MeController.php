<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Me;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Api\User\FetchingUserData;
use App\Transformers\User\UserTransformer;

class MeController extends Controller
{
    /**
     * create instance
     */
    public function __construct()
    {
        $this->middleware('auth.user:api');
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

        return fractal($user, new UserTransformer)->respond(200);
    }
}
