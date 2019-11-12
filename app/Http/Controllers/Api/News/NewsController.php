<?php

namespace App\Http\Controllers\Api\News;

use App\Models\NewsCategory;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\NewsRepository;
use App\Repository\NewsImagesRepository;
use App\Http\Requests\Api\News\StoreNewsRequest;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\News\NewsTransformer;
use App\Transformers\News\NewsAdminTransformer;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Carbon\Carbon;

class NewsController extends Controller
{
    private $news;

    public function __construct(NewsRepository $news,
                                NewsImagesRepository $newsImages,
                                ImageManager $manager) {

        // $this->middleware('auth:api');

        $this->news = $news;

        $this->newsImages = $newsImages;

        $this->manager = $manager;
    }

    /**
     * Display a blog type of news resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        if (isset($_GET['type'])) {
            $allNews = $this->news->getType($_GET['type']);
        }
        else {
            $allNews = $this->news->showAllType();
        }

            return fractal($allNews, new NewsTransformer)
                ->serializeWith(new ArraySerializer)
                // ->paginateWith(new IlluminatePaginatorAdapter($allNews))
                ->respond(201);
        
    }
    
    /**
     * Display a blog type of news resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllAdmin()
    {
        if (isset($_GET['type'])) {
            $allNews = $this->news->getType($_GET['type']);
        }
        else {
            $allNews = $this->news->showAllType();
        }

            return fractal($allNews, new NewsAdminTransformer)
                ->serializeWith(new ArraySerializer)
                // ->paginateWith(new IlluminatePaginatorAdapter($allNews))
                ->respond(201);
        
    }

    /**
     * Display viewing data from news.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewDataAdmin($id)
    {
        $singleNews = $this->news->showSingleAdmin($id);

        if (is_null($singleNews)) {

            return response()->json([
                'message' => trans('api.no_query_news')
            ],404);
        }

        return fractal($singleNews, new NewsAdminTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }
    
    /**
     * Display viewing data from news.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewData($slug)
    {
        $singleNews = $this->news->showSingle($slug);

        if (is_null($singleNews)) {

            return response()->json([
                'message' => trans('api.no_query_news')
            ],404);
        }

        return fractal($singleNews, new NewsTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addNews(StoreNewsRequest $request)
    {
        $news = News::all();
        
        foreach ($news as $item) {
            $id = $item->id + 1;
            $slug = $item->slug;
        }

        $allowedfileExtension=['pdf','jpg','png'];
        $file = $request->file('image_url'); 
        $currentTimestamp = Carbon::now()->timestamp;

            
            $imageFileRename = $id.'_'.$currentTimestamp.'.'.$file->getClientOriginalExtension();

            $imgPath = $file->storeAs('News/'.$slug,$imageFileRename);

            $resizeImg = $this->manager->make($file)->resize(200,200);

            $resizeImg->encode($file->getClientOriginalExtension());

            $url = Storage::put('News/'.$slug.'/thumbnails/'.$imageFileRename,$resizeImg->__toString());

        // request()->validate([
        //     'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        // $imageName = time().'.'.request()->image->getClientOriginalExtension();

        // request()->image->move(public_path('images'), $imageName);

        $newsCat = $this->news->newsCat();
        $slug = $this->news->timeStamp($request->title);
        $type = $this->news->strRep($request->type);
        $content = $this->news->removeTags($request->content);

        if (in_array($request->type, $newsCat)) {

            $image = [
                'news_id' => $id,
                'image_url' => $imageFileRename
            ];

            $this->newsImages->store($image);

            $newsData = array(
                'title' => $request->title,
                'author' => $request->author,
                'content' => $content,
                'type' => $type,
                'status' => 'D',
                'slug' => $slug,
                'is_deleted' => trans('api.data_not_deleted'),
            );

            $this->news->store($newsData);

            return response()->json([
                'message' => trans('api.success_added_news')
            ],201);
        }
        else {

            $data2 = [
                'title' => $request->title,
                'author' => $request->author,
                'content' => $content,
                'type' => $type,
                'status' => $status,
                'slug' => $slug,
                'is_deleted' => trans('api.data_not_deleted'),
            ];

            $this->news->store($data2);

            $slug = $this->news->slugData($request->type);

            $data_arr = [
                'name' => $request->type,
                'slug' => $slug,
                'is_deleted' => trans('api.data_not_deleted')
            ];

            $post = $this->news->addNewsCat($data_arr);

            return response()->json([
                'message' => trans('api.success_added_news')
            ],201);
               
        }


    }

    /**
     * Update data from news and store updated data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNewsRequest $request, $id)
    {
        $news = $this->news->checkId($id);

        $newsCat = $this->news->newsCat();
        $type = $this->news->strRep($request->type);
        $content = $this->news->removeTags($request->content);

        $status = $this->news->statusReq($request->status);

        if (is_null($news)) {

            return response()->json([
                'message' => trans('api.no_update_news')
            ],201);
        }

        if (in_array($request->type, $newsCat)) {
            
            $arr = [
                'title' => $request->title,
                'author' => $request->author,
                'content' => $content,
                'type' => $type,
                'status' => $status,
            ];

            $news->update($arr);

            return response()->json([
                'message' => trans('api.success_updated_news')
            ],201);

        }
        else {

            $data_arr = [
                'title' => $request->title,
                'author' => $request->author,
                'content' => $content,
                'type' => $type,
                'status' => $status,
            ];

            $news->update($data_arr);

            $slug = $this->news->slugData($request->type);

            $data_arr = [
                'name' => $request->type,
                'slug' => $slug,
                'is_deleted' => trans('api.data_not_deleted')
            ];

            $post = $this->news->addNewsCat($data_arr);

            return response()->json([
                'message' => trans('api.success_updated_news')
            ],201);
        }
    }

    /**
     * Remove data from the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = $this->news->checkId($id);

        if (is_null($news)) {

            return response()->json([
                'message' => trans('api.no_query_news')
            ],404);
        }

        $arr = [
            'is_deleted' => trans('api.data_deleted')
        ];

        $news->update($arr);
        // $news->delete();

        return response()->json([
            'message' => trans('api.success_deleted_news')
        ],201);
    }

    public function sample() 
    {
        $news = News::all();

        foreach ($news as $item) {
            $id = $item->id + 1;
        }

        return $id;
    }
    
}
