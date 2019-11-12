<?php

namespace App\Http\Controllers\Api\Tips;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\TipsRepository;
use App\Transformers\Tips\TipsTransformer;
use App\Transformers\Tips\TipsCategoryTransformer;
use App\Models\TipsCategory;
use App\Http\Requests\Api\Tips\StoreTipsRequest;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TipsController extends Controller
{

    /**
     * create instance
     */
    public function __construct(TipsRepository $tips)
    {
        $this->middleware('auth:api');
        $this->tips = $tips;
    }

    /**
     * Inserting new tips
     *
     * @param Illuminate\Http\Request       $request
     * @param App\Repository\TipsRepository $tips
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTips(StoreTipsRequest $request, TipsRepository $tips)
    {
        $data = [
            'category' => $request->category,
            'content' => $request->content,
        ];

        $tip = $this->tips->store($data);

        return response()
            ->json([
                'message' => trans('api.success_added_tips'),
            ], 201);
    }

    public function showAllTips()
    {
        $tip = $this->tips->showAll();

        return fractal($tip, new TipsTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }

    public function showTipsDetails($code)
    {
        $tip = $this->tips->checkId($code, null);

        if (is_null($tip)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        return fractal($tip, new TipsTransformer)->respond(201);
    }

    public function updateTips($id, StoreTipsRequest $request)
    {
        $tip = $this->tips->checkId(null, $id);

        if (is_null($tip)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $data = [
            'category' => $request->category,
            'content' => $request->content,
        ];

        $tip->update($data);

        return response()->json([
            'message' =>  trans('api.success_update_tips'),
        ], 201);
    }

    public function deleteTips($id)
    {
        $tip = $this->tips->checkId(null, $id);

        if (is_null($tip)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $tip->delete();

        return response()->json([
            'message' =>  trans('api.success_delete_tips'),
        ], 201);
    }

    public function showAllCategory()
    {
        $tipCategory = TipsCategory::all();

        return fractal($tipCategory, new TipsCategoryTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }
}