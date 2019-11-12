<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Vision;

use App\Models\VisionCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class VisionCategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($category)
    {
        return [
            'id' => $category->category_id,
            'category' => $category->category,
        ];
    }

}
