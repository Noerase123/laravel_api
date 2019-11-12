<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Investment;

use App\Models\InvestmentLevel;
use App\Support\Resource\ResourceItem;
use App\Models\SocialAccount;
use League\Fractal\TransformerAbstract;


class InvestmentLevelTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(InvestmentLevel $level)
    {
        return [
            'id' => $level->getKey(),
            'investment_level' => $level->investment_level,
            'investment_name' => $level->investment_name,
            'min_range' => $level->min_range,
            'max_range' => $level->max_range,
        ];
    }

}
