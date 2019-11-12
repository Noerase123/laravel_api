<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Investment;

use App\Models\Investment;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class InvestmentCountTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($investment)
    {
        return [
            'number_of_investment' => $investment,
        ];
    }
}
