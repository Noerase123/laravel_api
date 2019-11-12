<?php

namespace App\Http\Controllers\Api\Pauwinako;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
//models
use App\Models\PauwiNako;
use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
//request
use App\Http\Requests\Api\PauwiNako\PauwiNakoRequest;
//transformer
use App\Transformers\PauwiNako\PauwinakoTransformer;
//repository
use App\Repository\PauwinakoRepository;
use App\Repository\FamilyRepository;

class PauwinakoController extends Controller
{

    public function __construct(PauwinakoRepository $pauwinako) 
    {
        $this->middleware('auth:api');

        $this->pauwinako = $pauwinako;
    }
    /**
     * display data from the model
     *
     * @return $model
     */
    public function getAll()
    {
        $getData = $this->pauwinako->showAll();

        return fractal($getData, new PauwinakoTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->paginateWith(new IlluminatePaginatorAdapter($getData))
                    ->respond(201);
    }
    
    /**
     * get single data from model
     *
     * @param int id
     *
     * @return $model
     */
    public function getSingle($id)
    {
        $single = $this->pauwinako->showSingle($id);

        if (is_null($single)) {

            return response()->json([
                'message' => trans('api.no_query_pauwinako')
            ],404);
        }

        return fractal($single, new PauwinakoTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }

    /**
     * Set date 
     *
     * @param App\Http\Requests\Api\PauwiNako\PauwiNakoRequest
     * @param App\Repository\FamilyRepository
     *
     * @return $model
     */
    public function setDate(PauwiNakoRequest $request, FamilyRepository $families) 
    {
        $user = $request->user();
        $id = $user->id;

        $family = $families->findFamily($user);

        $fam_code = $family->code;

        $reqData = [
            'ofw_id' => $id,
            'family_code' => $fam_code,
            'date_of_return' => $request->date_of_return
        ];

        $this->pauwinako->store($reqData);

        $alert = $this->pauwinako->notif($fam_code);

        return response()->json([
            // 'message_id' => $alert,
            'message' => trans('api.success_added_pauwinako')
        ],201);
    }

    /**
     * edit date
     *
     * @param App\Http\Requests\Api\PauwiNako\PauwiNakoRequest
     * @param App\Repository\FamilyRepository
     * @param int $id
     *
     * @return $model
     */
    public function edit(PauwiNakoRequest $request, FamilyRepository $families, $id) 
    {
        $user = $request->user();
        $family = $families->findFamily($user);
        $fam_code = $family->code;

        $pn_id = $this->pauwinako->checkId($id);

        if (is_null($pn_id)) {

            return response()->json([
                'message' => trans('api.no_update_pauwinako')
            ],404);
        }

        $reqData = [
            'date_of_return' => $request->date_of_return
        ];

        $pn_id->update($reqData);

        $alert = $this->pauwinako->notif($fam_code);

        return response()->json([
            // 'message_id' => $alert,
            'message' => trans('api.success_updated_pauwinako')
        ],201);

    }

}