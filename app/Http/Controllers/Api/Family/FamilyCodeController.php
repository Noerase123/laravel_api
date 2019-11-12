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
use App\Http\Requests\Api\Family\CheckFamilyRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Repository\UserRepository;

class FamilyCodeController extends Controller
{
    /**
     * create instance
     */
    public function __construct(FamilyRepository $families)
    {
        $this->families = $families;
    }

    public function checkFamilyCode (CheckFamilyRequest $request)
    {
        $family = $this->families->findFamilyByCode($request->familyInvitationCode);

        if ($family){
            return response()
                ->json([
                    'familyCode' => $request->familyInvitationCode,
                    //'familyType' => User::USER_TYPE_FAMILY_MEMBER,
                ], 201);
        }

        return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 409);
    }
}
