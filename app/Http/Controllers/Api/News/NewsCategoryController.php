<?php

namespace App\Http\Controllers\Api\News;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
// model
use App\Models\NewsCategory;
// transformer
use App\Transformers\News\NewsCategoryTransformer;
// request
use App\Http\Requests\Api\News\StoreNewsCategoryRequest;
// repository
use App\Repository\NewsCategoryRepository;

class NewsCategoryController extends Controller
{
    /**
     * Constructor of the controller
     *
     * @param App\Repository\NewsCategoryRepository
     */
    public function __construct(NewsCategoryRepository $newsCategory) {

        $this->middleware('auth:api');

        $this->newsCateg = $newsCategory;
    }

    /**
     * Display all data from the model
     *
     * @return App\Models\NewsCategory
     */
    public function allData() 
    {

        $allData = $this->newsCateg->showAll();

        return fractal($allData, new NewsCategoryTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);

    }

    /**
     * Display all data from the model
     *
     * @return App\Models\NewsCategory
     */
    public function viewCat($id) 
    {
        
        $select_id = $this->newsCateg->checkId($id);

        if (is_null($select_id)) {
            
            return response()->json([
                'message' => trans('api.no_data_news_cat')
            ],404);
        }

        return fractal($select_id, new NewsCategoryTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }

    public function addNewsCateg(StoreNewsCategoryRequest $storeNewsCat) 
    {
        $display_slug = $this->newsCateg->slugData($storeNewsCat->name);

        $data = [
            'name' => $storeNewsCat->name,
            'slug' => $display_slug,
            'is_deleted' => trans('api.data_not_deleted')
        ];

        $this->newsCateg->store($data);

        return response()->json([
            'message' => trans('api.added_data_news_cat')
        ],201);
    }

    public function updateNewsCateg(StoreNewsCategoryRequest $storeNewsCat, $id)
    {
        $selected_data = $this->newsCateg->checkId($id);

        if (is_null($selected_data)) {
            return response()->json([
                'message' => trans('api.no_data_news_cat')
            ],404);
        }

        $display_slug = $this->newsCateg->slugData($storeNewsCat->name);

        $data = [
            'name' => $storeNewsCat->name,
            'slug' => $display_slug,
        ];

        $selected_data->update($data);

        return response()->json([
            'message' => trans('api.updated_data_news_cat')
        ],201);
    }

    public function deleteNewsCateg($id) 
    {
        $delete_id = $this->newsCateg->checkId($id);

        if (is_null($delete_id)) {
            
            return response()->json([
                'message' => trans('api.no_data_news_cat')
            ],404);
        }

        $arr = [
            'is_deleted' => trans('api.data_deleted')
        ];

        $delete_id->update($arr);

        return response()->json([
            'message' => trans('api.deleted_data_news_cat')
        ]);
    }
}
