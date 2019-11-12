<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Investment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Investment\StoreInvestmentLevelRequest;
use App\Http\Requests\Api\Investment\StoreInvestmentRequest;
use App\Models\Investment;
use App\Models\InvestmentLevel;
use App\Repository\InvestmentLevelRepository;
use App\Repository\InvestmentRepository;
use App\Transformers\Investment\InvestmentLevelTransformer;
use App\Transformers\Investment\InvestmentLevelCountTransformer;
use App\Transformers\Investment\InvestmentCountTransformer;
use App\Transformers\Investment\InvestmentTransformer;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Carbon\Carbon;
use Intervention\Image\ImageManager;

class InvestmentController extends Controller
{
    /**
     * create instance
     */
    public function __construct(InvestmentLevelRepository $levels,
                                InvestmentRepository $investments,
                                ImageManager $manager)
    {
        $this->middleware('auth:api');
        $this->levels = $levels;
        $this->investments = $investments;
        $this->manager = $manager;
    }

    /**
     *  add level of investments
     *
     * @param  \App\Http\Requests\Api\User\StoreInvestmentLevelRequest  $request
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function addInvestmentLevel(StoreInvestmentLevelRequest $request)
    {
        //map request data
        $data = [
            'investment_level' => $request->investmentLevel,
            'investment_name' => $request->investmentName,
            'min_range' => $request->minimumRange,
            'max_range' => $request->maximumRange,
        ];

        //stores all the details required
        $level = $this->levels->store($data);

        return response()->json([
            'message' =>  trans('api.success_add_level'),
        ], 201);
    }

    /**
     *  add investment under investment levels
     *
     * @param  \App\Http\Requests\Api\User\StoreInvestmentRequest $request
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function addInvestment(StoreInvestmentRequest $request)
    {
        $imageFileRename = null;

        if ($request->hasFile('banner')) {
            $currentTimestamp = Carbon::now()->timestamp;

            $files = $request->file('banner'); 

            $imageFileRename = $currentTimestamp .'.'. $files->getClientOriginalExtension();

            $imgPath = $files->storeAs(
                'Investment/Banners', $imageFileRename
            );

            $resizeImg = $this->manager->make($files)->resize(200,200);

            $url = Storage::put('Investment/Banners/thumbnail/'. $imageFileRename, $resizeImg->__toString());
        }

        //map request data
        $data = [
            'id_investment_level' => $request->idInvestmentLevel,
            'initial_capital' => $request->initialCapital,
            'min_estimated_return' => $request->minEstimatedReturn,
            'max_estimated_return' => $request->maxEstimatedReturn,
            'risk_level' => $request->riskLevel,
            'description' => $request->description,
            'short_description' => $request->shortDescription,
            'url' => $request->url,
            'banner' => $imageFileRename,
        ];

        //stores all the details required
        $investment = $this->investments->store($data);

        return response()->json([
            'message' =>  trans('api.success_add_investment'),
        ], 201);
    }

    /**
     *
     * show all investment levels
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showInvestmentLevel()
    {
        //fetch all levels
        $level = $this->levels->showAll();

        return fractal($level, new InvestmentLevelTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($level))
                ->serializeWith(new ArraySerializer)
                ->respond(201);
    }

    /**
     * show investments under investment levels
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllInvestment($id)
    {
        $investment = $this->investments->showAll($id);

        if (is_null($investment) || $investment->isEmpty()) {
            return response()
                ->json([
                    'message' => trans('api.no_investment_found'),
                ], 404);
        }

        return fractal($investment, new InvestmentTransformer)
                ->serializeWith(new ArraySerializer)
                ->respond(201);
    }

    /**
     * show investment details
     *
     *
     * @param $id, $id_inv
     * @return \Illuminate\Http\JsonResponse
     */
    public function showInvestmentDetails($id, $id_inv)
    {
        $investment = $this->investments->showDetails($id, $id_inv);

        if (is_null($investment)) {
            return response()
                ->json([
                    'message' => trans('api.no_investment_found'),
                ], 404);
        }

        return fractal($investment, new InvestmentTransformer)->respond(201);
    }

    /**
     * update investment level
     *
     * @param Illuminate\Http\Request $request, $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvestmentLevel(StoreInvestmentLevelRequest $request, $id)
    {
        $level = $this->levels->checkId($id);

        if (is_null($level)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        //map request data
        $data = [
            'investment_level' => $request->investmentLevel,
            'investment_name' => $request->investmentName,
            'min_range' => $request->minimumRange,
            'max_range' => $request->maximumRange,
        ];

        $level->update($data);

        return response()->json([
            'message' =>  trans('api.update_investment_level'),
        ], 201);

        //return fractal($level, new InvestmentLevelTransformer)->respond(201);
    }

    /**
     * update investment
     *
     * @param Illuminate\Http\Request $request, $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvestment(StoreInvestmentRequest $request, $id)
    {
        $investment = $this->investments->checkId($id);

        if (is_null($investment)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        //map request data
        $data = [
            'id_investment_level' => $request->idInvestmentLevel,
            'initial_capital' => $request->initialCapital,
            'min_estimated_return' => $request->minEstimatedReturn,
            'max_estimated_return' => $request->maxEstimatedReturn,
            'risk_level' => $request->riskLevel,
            'description' => $request->description,
            'url' => $request->url,
        ];

        $investment->update($data);

        return response()->json([
            'message' =>  trans('api.update_investment'),
        ], 201);

        //return fractal($investment, new InvestmentTransformer)->respond(201);
    }

    /**
     * show number of investment level
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countInvestment()
    {
        $investmentsCount = $this->investments->countData();

        return fractal($investmentsCount, new InvestmentCountTransformer)->respond(201);
    }

    public function countInvestmentLevel()
    {
        $levelCount = $this->levels->countData();

        return fractal($levelCount, new InvestmentCountTransformer)->respond(201);
    }

    /**
     * delete investment level
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInvestmentLevel($id)
    {
        $level = $this->levels->checkId($id);

        if (is_null($level)) {
            return response()
                ->json([
                    'message' => trans('api.unable_to_delete'),
                ], 404);
        }

        $level->delete();

        return response()->json([
            'message' =>  trans('api.delete_investment_level'),
        ], 201);
        //return response()->json(null,204);
    }

    /**
     * delete investment
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInvestment($id)
    {
        $investment = $this->investments->checkId($id);

        if (is_null($investment)) {
            return response()
                ->json([
                    'message' => trans('api.unable_to_delete'),
                ], 404);
        }

        $investment->delete();

        return response()->json([
            'message' =>  trans('api.delete_investment'),
        ], 201);
        //return response()->json(null,204);
    }
}