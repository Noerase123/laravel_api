<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Tips;

use App\Models\Tips;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class TipsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($tips)
    {
        return [
            'id' => $tips->getKey(),
            'category' => $tips->category ,
            'content' => $tips->content,
        ];
    }

}
