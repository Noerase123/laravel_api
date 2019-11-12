<?php

namespace App\Http\Controllers\Api\Balitahanan;

use App\Http\Controllers\Controller;
use App\Models\Balitahanan;
use App\Models\BalitahananImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Carbon\Carbon;
use App\Repository\FamilyRepository;
use App\Repository\BalitahananRepository;
use App\Repository\BalitahananImagesRepository;
use Intervention\Image\ImageManager;
use App\Transformers\Balitahanan\BalitahananTransformer;
use League\Fractal\Serializer\ArraySerializer;
use App\Repository\UserRepository;
use App\Models\User;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class BalitahananController extends Controller
{
    public function __construct(BalitahananRepository $posts, 
                                BalitahananImagesRepository $balitahananFile,
                                ImageManager $manager, 
                                UserRepository $users)
    {
        $this->middleware('auth:api');

        $this->posts = $posts;

        $this->manager = $manager;

        $this->users = $users;

        $this->balitahananFile = $balitahananFile;
    }

    public function addPost(Request $request, FamilyRepository $families)
    {
        $user = $request->user();
        $id = $user->id;

        $family = $families->findFamily($user);

        // if user has already a code defined return a status of 302
        if (is_null($family)) {
            return response()
                ->json([
                    'message' => trans('api.no_family_code_found'),
                ], 404);
        }
      /*
        if(!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        */

        $allowedfileExtension=['pdf','jpg','png'];
        $files = $request->file('image'); 

        $currentTimestamp = Carbon::now()->timestamp;
        
        $postCount = $this->posts->countData();
        
        foreach ($files as $file) {  

            $imageFileRename = $id .'_'. $currentTimestamp .'.'. $file->getClientOriginalExtension();

            $imgPath = $file->storeAs(
                'Balitahanan/'. $family->code, $imageFileRename
            );
    
            /*$resizeImg = $this->manager->make($file)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            */

            $resizeImg = $this->manager->make($file)->resize(200,200);
    
            $resizeImg->encode($file->getClientOriginalExtension());
    
            $url = Storage::put('Balitahanan/'. $family->code .'/thumbnails/'. $imageFileRename, $resizeImg->__toString());

            $dataImg = [
                'balitahanan_id' => $postCount + 1,
                'image_url' => $imageFileRename,
            ];
            
            $this->balitahananFile->store($dataImg);

            $currentTimestamp = $currentTimestamp + 60;
        }
        
        $data = [
            'author_id' => $id,
            'newsfeed_msg' => $request->message,
            'family_code' => $family->code,
        ];

        $this->posts->store($data);

        return response()->json([
            'message' =>  trans('api.insert_new_post_success'),
        ], 201);
    }

    public function showPost (Request $request, FamilyRepository $families) 
    {
     
        $user = $request->user();
        $id = $user->id;

        $family = $families->findFamily($user);

        $post = $this->posts->showAll($family->code);

        return fractal($post, new BalitahananTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->paginateWith(new IlluminatePaginatorAdapter($post))
                    ->respond(201);
    } 

    public function showPostByAdmin ($id, FamilyRepository $families) 
    {
        $user = $this->users->showUser($id, User::USER_TYPE_OFW);

        $family = $families->findFamily($user);

        $posts = $this->posts->showAll($family->code);

        return fractal($posts, new BalitahananTransformer)
                    ->serializeWith(new ArraySerializer)
                    ->paginateWith(new IlluminatePaginatorAdapter($posts))
                    ->respond(201);
    }
}