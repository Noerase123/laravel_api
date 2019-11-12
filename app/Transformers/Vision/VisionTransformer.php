<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Vision;

use App\Models\VisionCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;
use App\Transformers\Vision\VisionDetailsTransformer;
use League\Fractal;

class VisionTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'details'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($vision)
    {
        $data = [];

        if (count($vision['visions']) === 1 ) {
            return [
                'id' =>  $vision['visions']->id,
                'category' => $vision["visions"]->category,
                'items' => $vision["visions"]->item,
    
                /**
                 * to do: getting total savings per category
                 */
                'total_savings' => (float)  number_format(($vision["visions"]->amount - 1000), 2, '.', ''),
                'total_vision_amount' => (double) number_format($vision["visions"]->amount, 2, '.', ''),
                'percentage' => (double) number_format(((($vision["visions"]->amount - 1000) / $vision["visions"]->amount) * 100) , 2, '.', '' )
            ];
        }
        
        foreach ($vision['visions'] as $key => $value) {
            $data[$key]= [
                "id" =>  $vision['visions'][$key]->id,
                'category' => $vision['visions'][$key]->category,
                'items' => $vision['visions'][$key]->item,

                /**
                * to do: getting total savings per category
                */
                'total_savings' => number_format(($vision['visions'][$key]->amount - 1000), 2, '.', ''),
                'total_vision_amount' => number_format($vision['visions'][$key]->amount, 2, '.', ''),
                'percentage' => number_format(((($vision['visions'][$key]->amount - 1000) / $vision['visions'][$key]->amount) * 100) , 2, '.', '' )
            ];
        }

        return $data;
    }

    public function includeDetails($vision)
    {
        return $this->item($vision, new VisionDetailsTransformer);
    }
}
