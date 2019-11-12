<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Comment;

use App\Models\Comment;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($comment)
    {
        return [
            'id' => $comment->getKey(),
            'name' => ucfirst($comment->firstname). ' '. ucfirst($comment->lastname) ,
            'content' => $comment->content,
        ];
    }


}
