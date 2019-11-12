<?php

namespace App\Http\Resources\Investment;

use App\Models\InvestmentLevel;
use App\Support\Resource\ResourceItem;


class InvestmentLevelResource extends ResourceItem
{
    /**
     * create instance
     *
     * @param \App\Models\User $user
     */
    public function __construct(InvestmentLevel $level)
    {
        parent::__construct($level);
    }

    /**
     * {@inheritDoc}
     */
    public function transform($level)
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
