<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Vision;

use App\Models\VisionCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;
use App\Transformers\Vision\VisionTransformer;


class VisionAmountTransformer extends TransformerAbstract
{
    protected $defaultIncludes  = [
        'visions'
    ];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($vision)
    {
        return [
            'overall_amount_vision' => $vision['sumVision']->totalAmount,
            /**
             * to do: total savings amount
             * overall_amount_savings is just an example
             */
            'overall_amount_savings' => ($vision['sumVision']->totalAmount - 2000),
            'percentage' => number_format((($vision['sumVision']->totalAmount - 2000) / $vision['sumVision']->totalAmount) * 100,  2, '.', '')
        ];
    }

    public function includeVisions($vision)
    {       
        return $this->item($vision, new VisionTransformer);
    }
}
