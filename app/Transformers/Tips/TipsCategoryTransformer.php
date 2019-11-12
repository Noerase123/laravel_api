<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Tips;

use App\Models\TipsCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class TipsCategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($tips)
    {
        return [
            'code' => $tips->code ,
            'name' => $tips->name,
        ];
    }

}
