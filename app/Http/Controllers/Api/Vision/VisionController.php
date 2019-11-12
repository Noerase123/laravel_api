<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Vision;

use Illuminate\Http\Request;
use App\Models\Vision;
use App\Models\VisionCategory;
use App\Models\VisionCategoryOthers;
use App\Repository\VisionRepository;
use App\Repository\VisionCategoryOthersRepository;
use App\Repository\VisionCategoryRepository;
use App\Repository\FamilyRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vision\VisionResource;
use App\Http\Requests\Api\Vision\StoreVisionRequest;
use App\Http\Requests\Api\Vision\StoreVisionCategoryOthersRequest;
use App\Transformers\Vision\VisionCategoryTransformer;
use App\Transformers\Vision\VisionCountTransformer;
use App\Transformers\Vision\VisionTransformer;
use App\Transformers\Vision\VisionDetailsTransformer;
use App\Transformers\Vision\VisionAmountTransformer;
use App\Transformers\Dashboard\DashboardTransformer;
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;

class VisionController extends Controller
{
    /**
     * create instance
     *
     * @param App\Repository\VisionRepository $visions
     * @param App\Repository\VisionCategoryOthersRepository $visionCategories
     * @param App\Repository\VisionCategoryRepository $categories
     */
    public function __construct(VisionRepository $visions, 
                                VisionCategoryOthersRepository $visionCategories,
                                VisionCategoryRepository $categories)
    {
        $this->middleware('auth:api');
        $this->visions = $visions;
        $this->visionCategories = $visionCategories;
        $this->categories = $categories;
    }

    /**
     * /api/vision/add POST
     *
     * Adding new vision
     *
     * @param App\Http\Requests\Api\Vision\StoreVisionRequest $request,
     * @param App\Repository\FamilyRepository $families
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addVision (StoreVisionRequest $request, FamilyRepository $families)
    {
        $user = $request->user();
        $family = $families->findFamily($user);

        // if user has already a code defined return a status of 302
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $categoryId = $request->targetCategoryId;

        //count categories under Others of Pangarap board
        $countOtherCategories = $this->visionCategories->countCategoryPerFamily($family->code);

        //current id of Others option
        $categoryLastId = config('app.vision_category_length') + $countOtherCategories;

        $otherCategoryId = 0;

        //if the target category id is equal to Others option id, it will execute the add vision category function
        if ($categoryId == $categoryLastId){

            $otherCategoryId = $categoryLastId;

            $dataOthers = [
                'author_id' => $user->id,
                'category_id' => $otherCategoryId,
                'category' => $request->category,
                'family_code' => $family->code,
            ];

            $this->addVisionCategory($dataOthers, $categoryId);
        }

        //map request data
        $data = [
            'author_id' => $user->id,
            'target_amount' => $request->targetAmount,
            'target_cat_id' => $request->targetCategoryId,
            'vision_cat_others_id' => $otherCategoryId,
            'target_date' => $request->targetDate,
            'description' => $request->description,
            'family_code' => $family->code,
        ];

        //storing new pangarap board
        $vision = $this->visions->store($data);

        return response()
            ->json([
                'message' => trans('api.success_add_vision'),
            ], 201);
    }

    /**
     * /api/vision/update/{id} POST
     *
     * Updating vision
     *
     * @param $id,
     * @param App\Http\Requests\Api\Vision\StoreVisionRequest $request,
     * @param App\Repository\FamilyRepository $family
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVision ($id, StoreVisionRequest $request, FamilyRepository $families)
    {
        $vision = $this->visions->checkId($id);

        if (is_null($vision)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $user = $request->user();
        $family = $families->findFamily($user);

        // if user has already a code defined return a status of 302
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $categoryId = $request->target_cat_id;

        //count categories under Others of Pangarap board
        $countOtherCategories = $this->visionCategories->countCategoryPerFamily($family->code);

        //current id of Others option
        $categoryLastId = config('app.vision_category_length') + $countOtherCategories;

        $otherCategoryId = 0;

        //if the target category id is equal to Others option id, it will execute the add vision category function
        if ($categoryId == $categoryLastId){

            $otherCategoryId = $categoryLastId;

            $dataOthers = [
                'author_id' => $user->id,
                'category_id' => $otherCategoryId,
                'category' => $request->category,
                'family_code' => $family->code,
            ];

            $this->addVisionCategory($dataOthers, $categoryId);
        }

        //map request data
        $data = [
            'author_id' => $user->id,
            'target_amount' => $request->targetAmount,
            'target_cat_id' => $request->targetCategoryId,
            'vision_cat_others_id' => $otherCategoryId,
            'target_date' => $request->targetDate,
            'description' => $request->description,
            'family_code' => $family->code,
        ];

        $vision->update($data);

        return response()->json([
            'message' =>  trans('api.success_update_vision'),
        ], 201);
    }

    /**
     * /api/vision/categories GET
     *
     * fetching all categories by family
     *
     * @param Request $request
     * @param App\Repository\FamilyRepository $families
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllCategories (Request $request, FamilyRepository $families)
    {
        $user = $request->user();
        $family = $families->findFamily($user);

        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        //show all categories under Others option by family
        $visionCat = $this->visionCategories->showAll($family->code);

        //will union $visionCat to default vision category table
        $visionCatOthers = VisionCategory::select("category_id", "category")->union($visionCat)->get();

        return fractal($visionCatOthers, new VisionCategoryTransformer)->respond(201);
    }

    /**
     * Counting all registered visions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllCount ()
    {
        //will count all visions
        $visionCount = $this->visions->countData();

        return fractal($visionCount, new VisionCountTransformer)->respond(201);
    }

    /**
     * Showing all categories with registered visions
     *
     * @param Request $request
     * @param FamilyRepository $families
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllVisions(Request $request, FamilyRepository $families)
    {
        //fetching the users info
        $user = $request->user();
        //fetching the user's family code
        $family = $families->findFamily($user);

        $visionArr = [];

        //check if the user has family code
        if (is_null($family)) {
            //if null
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        //will fetch all visions by family code
        $vision = $this->visions->showAll($family->code);

        $sumVision = $this->visions->sumAllVision($family->code);

        array_push(
            $visionArr,
            [
                'sumVision' => $sumVision,
                'visions' => $vision
            ]
        );

        return fractal($visionArr, new VisionAmountTransformer)->respond(201);
    }

    /**
     * Showing all visions under category
     *
     * @param $id
     * @param Request $request
     * @param FamilyRepository $families
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllVisionsPerCategory ($id, Request $request, FamilyRepository $families)
    {
        //fetching the users info
        $user = $request->user();
        //fetching the user's family code
        $family = $families->findFamily($user);
        $visionArr = [];
        //check if the user has family code
        if (is_null($family)) {
            //if null
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        //will fetch all visions by family code and category id
        $vision = $this->visions->showAll($family->code, $id);

        //check if the result is null
        if (is_null($vision)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        //fetch
        $visionDetails = $this->visions->showAllVisions($family->code, $id);

        if (is_null($visionDetails)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        array_push(
            $visionArr,
            [
                'visions' => $vision,
                'lists' => $visionDetails,
            ]
        );

        return fractal($visionArr, new VisionTransformer)
                    ->includeDetails()
                    ->respond(201);
    }

    /**
     * Showing overall visions
     *
     * @param Request $requst
     * @param FamilyRepository $families
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllVisionsLists (Request $request, FamilyRepository $families)
    {
        //fetching the users info
        $user = $request->user();
        //fetching the user's family code
        $family = $families->findFamily($user);

        //check if the user has family code
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $visionDetails = $this->visions->showAllVisions($family->code);

        if (is_null($visionDetails)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        return fractal($visionDetails, new VisionDetailsTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }

    public function showSumAllVisionByFamilyCode (Request $request, FamilyRepository $families)
    {
        //fetching the users info
        $user = $request->user();
        //fetching the user's family code
        $family = $families->findFamily($user);

        //check if the user has family code
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }

        $sumVision = $this->visions->sumAllVision($family->code);

        if (is_null($sumVision)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        return fractal($sumVision, new VisionAmountTransformer)->respond(201);
    }

    /**
     * Delete Vision
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteVision ($id)
    {
        $vision = $this->visions->checkId($id);

        if (is_null($vision)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $vision->delete();

        return response()->json([
            'message' =>  trans('api.success_delete_vision'),
        ], 201);
    }

    /**
     * insert new category
     *
     * @param array $data
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function addVisionCategory ($data, $id)
    {
        if ($id < config('app.vision_category_length')) {
            return false;
        }

        $visionCategory = $this->visionCategories->store($data);

        return $visionCategory;
    }

    public function countAll ()
    {
        $category = $this->categories->countData();
        $data = [];

        $overallTotal = $this->visions->countAllVision();

        foreach ($category as $key ) {

            $result = $this->visions->countAllVision($key->category_id);
            $data [] = [
                "title" => $key->category,
                "count" => $result->total
            ];
        }

        $countArray = count($data);
        $othersTotal = $this->visions->countDataOthers($countArray);

        array_push(
            $data,
            [
                "title" => "Others",
                "count" => $othersTotal->total,
            ]
        );

        return response()
            ->json([
                "total" => $overallTotal,
                "visions" => $data
            ]);
            
    }
}