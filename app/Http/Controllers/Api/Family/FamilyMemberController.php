<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Family;

use Carbon\Carbon;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Repository\FamilyRepository;
use App\Transformers\Family\FamilyMemberListTransformer;
use App\Transformers\Family\FamilyMemberTransformer;
use App\Transformers\User\UserTransformer;
use App\Repository\UserRepository;

class FamilyMemberController extends Controller
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
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request         $request
     * @param  \App\Repository\FamilyRepository $families
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FamilyRepository $families)
    {
        $user = $request->user();
        $family = $families->findFamily($user);

        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $jsonResult = $this->jsonFamilyList($family, $user, $families);

        return response()->json($jsonResult, 200);
    }

    public function memberList(Request $request, FamilyRepository $families, $id)
    {
        $user = $this->users->showUser($id, User::USER_TYPE_OFW);
        $family = $families->findFamily($user);

        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $jsonResult = $this->jsonFamilyList($family, $user, $families);

        return response()->json($jsonResult, 200);
    }

    protected function jsonFamilyList($family, $user, $families)
    {
        $jsonResult = array();

        // fetch members of the family
        $members = $families->findFamilyMembersWithId($family,$user->id)->sortByDesc(function ($member) use ($user) {
            $member = $member->user;

            /**
             * we will give priority to the current user requesting the resource
             * if the current authenticated user is a ofw then he/she must he first in the list
             * else if a family member he/she must be first in the list followed by the ofw of the
             * family
             */

            if ($member->isOfw()) {
                return 0;
            } elseif ($member->getKey() === $user->getKey()) {
                return 1;
            } else {
                return -1;
            }
        });

        $jsonResult["user"] = fractal($user, new UserTransformer);
        $jsonResult["members"] = fractal($members, new FamilyMemberListTransformer);

        return $jsonResult;
    }
}
