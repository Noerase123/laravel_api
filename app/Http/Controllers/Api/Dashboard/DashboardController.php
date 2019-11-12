<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Transformers\Dashboard\DashboardTransformer;
use App\Repository\VisionRepository;
use App\Repository\VisionCategoryRepository;
use App\Repository\UserRepository;
use App\Repository\InvestmentRepository;
use App\Models\User;
use League\Fractal\Serializer\ArraySerializer;

class DashboardController extends Controller
{

    /**
     * create instance
     *
     * @param App\Repository\VisionRepository $visions
     * @param App\Repository\VisionCategoryRepository $categories
     * @param App\Repository\InvestmentRepository @investment
     * @param App\Repository\UserRepository $users
     */
    public function __construct(VisionRepository $visions,
                                VisionCategoryRepository $categories,
                                InvestmentRepository $investments,
                                UserRepository $users)
    {
        $this->middleware('auth:api');
        $this->visions = $visions;
        $this->categories = $categories;
        $this->users = $users;
        $this->investments = $investments;
    }

    /**
     *  /api/dashboard  GET
     *
     * This function will show the overall total of users, investments
     * and visions for Dashboard view in Backend
     * 
     * @return \Illuminate\Http\JsonResponse     
     */
    public function countAll ()
    {
        // will get the total number of category
        $category = $this->categories->countData();

        // array for category
        $categoryArr = [];

        // array for total users
        $totalUsers = [];

        // get the total vision
        $overallTotal = $this->visions->countAllVision();

        /** 
         * get the total count of each category
         * then append on $categoryArr[]
         */
        foreach ($category as $key ) {

            $result = $this->visions->countAllVision($key->category_id);
            $categoryArr [] = [
                "title" => $key->category,
                "count" => $result->total
            ];
        }

        // get the count of data in $categoryArr
        $countArray = count($categoryArr);

        /**
         * get the total number of visions in Others category
         * then append on $categoryArr[]
         */
        $othersTotal = $this->visions->countDataOthers($countArray);

        array_push(
            $categoryArr,
            [
                "title" => "Others",
                "count" => $othersTotal->total,
            ]
        );

        // get the total number of overall users
        $userCount = $this->users->countData();

        // get the total number of OFW users
        $userOfwCount = $this->users->countData(User::USER_TYPE_OFW);

        // get the total number of Admin users
        $userAdminCount = $this->users->countData(User::USER_TYPE_ADMIN);

        // get the total number of Family members users
        $userFamilyCount = $this->users->countData(User::USER_TYPE_FAMILY_MEMBER);

        /**
         * All users count in OFW, Admin and Family members
         * will append on $totalUsers array
         */
        array_push(
            $totalUsers,
            [
                "ofw" => $userOfwCount,
                "admin" => $userAdminCount,
                "family_members" => $userFamilyCount
            ]
        );

        // get the total number of registered investments
        $investmentsCount = $this->investments->countData();

        return response()
            ->json([
                "total_users" => $userCount,
                "users" => $totalUsers,
                "total_investments" => $investmentsCount,
                "total_visions" => $overallTotal,
                "visions" => $categoryArr
            ], 201);
    }
}