<?php

namespace App\Http\Resources\Investment;

use App\Models\Investment;
use App\Support\Resource\ResourceItem;


class InvestmentResource extends ResourceItem
{
    /**
     * create instance
     *
     * @param \App\Models\User $user
     */
    public function __construct(Investment $investment)
    {
        parent::__construct($investment);
    }

    /**
     * {@inheritDoc}
     */
    public function transform($investment)
    {
        return [
            'id' => $investment->getKey(),
            'id_investment_level' => $investment->id_investment_level,
            'initial_capital' => $investment->initial_capital,
            'min_estimated_return' => $investment->min_estimated_return,
            'max_estimated_return' => $investment->max_estimated_return,
            'risk_level' => $investment->risk_level,
            'description' => $investment->description,
        ];
    }
}
