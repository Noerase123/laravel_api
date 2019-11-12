<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Vision;

use App\Models\VisionCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class VisionDetailsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($details)
    {
        $data = [];

        foreach ($details['lists'] as $key => $value) {
            $data[$key]= [
                'id' => $details['lists'][$key]->id,
                'description' => $details['lists'][$key]->description,
                'amount' => number_format($details['lists'][$key]->target_amount, 2, '.', ''),
                'target_date' => $details['lists'][$key]->target_date->format(config('app.default_datetime_format')),
                'allocated' => number_format($details['lists'][$key]->target_amount, 2, '.', '')
            ];
        }

        return $data;
    }
}
