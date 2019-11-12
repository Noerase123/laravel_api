<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Vision;

use App\Models\VisionCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class VisionCountTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($category)
    {
        return [
            'no_of_personal' => $category->personal,
            'no_of_car' => $category->car,
            'no_of_housing' => $category->housing,
            'no_of_educ' => $category->education,
            'no_of_debt' => $category->debt,
            'no_of_others' => $category->others,
            'total_count' => $category->total,
        ];
    }

}
