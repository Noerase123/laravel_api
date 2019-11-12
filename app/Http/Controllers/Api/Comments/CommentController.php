<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use App\Http\Requests\Api\Comment\StoreCommentRequest;
use App\Repository\CommentRepository;
use App\Transformers\Comment\CommentTransformer;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class CommentController extends Controller
{
    /**
     * create instance
     */
    public function __construct(CommentRepository $comments)
    {
        $this->middleware('auth:api');
        $this->comments = $comments;
    }

    /**
     * Inserting new comment/suggestions
     *
     * @param App\Http\Requests\Api\Comment\StoreCommentRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment (StoreCommentRequest $request)
    {
        $user = $request->user();

        $id = $user->id;

        $data = [
            'author_id' => $id,
            'content' => $request->content,
        ];

        $comment = $this->comments->store($data);

        return response()
            ->json([
                'message' => trans('api.success_add_comment'),
            ], 201);
    }

    /**
     * Show all comments
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showComment()
    {
        $comment = $this->comments->showAll();

        return fractal($comment, new CommentTransformer)
                    ->paginateWith(new IlluminatePaginatorAdapter($comment))
                    ->serializeWith(new ArraySerializer)
                    ->respond(201);        
    }

    public function showCommentById($id)
    {
        $comment = $this->comments->showAll($id);

        return fractal($comment, new CommentTransformer)->respond(201);       
    }

    /**
     * Delete comment
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment($id)
    {
        $comment = $this->comments->checkId($id);

        if (is_null($comment)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $comment->delete();

        return response()
            ->json([
                'message' => trans('api.success_delete_comment'),
            ], 201);
    }
}