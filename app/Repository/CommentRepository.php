<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Repository;

use App\Models\Comment;
use App\Models\User;
use App\Support\Repository\EloquentRepository;

/**
 * eloquent repository class for comment model
 */
class CommentRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    /**
     * store newly created user to the storage
     *
     * @param  array $attributes
     *
     * @return void
     */
    public function store(array $attributes)
    {
        $model = $this->newModel();

        // place attribute values to the model
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        return ($model->save()) ? $model : null;
    }

    public function showAll($id = null)
    {
        $model = $this->newModel();

        $join = $model->leftJoin('users', 'comments.author_id', '=', 'users.id')
        ->select(['comments.id','users.firstname', 'users.lastname', 'comments.content']);

        if (!empty($id)){
           return $join->where('comments.id', '=', $id)->first();
        }

        return $join->paginate( 10, ['*']);
    }

    public function checkId($id)
    {
        $model = $this->newModel();

        $result = $model->where('id',$id)->first();

        //check if the  $model is null
        if (is_null($result)) {
            return;
        }

        return $result;
    }
}