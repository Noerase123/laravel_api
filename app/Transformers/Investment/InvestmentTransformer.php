<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Investment;

use App\Models\Investment;
use App\Support\Resource\ResourceItem;
use App\Models\SocialAccount;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;


class InvestmentTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Investment $investment)
    {
        return [
            'id' => $investment->getKey(),
            'id_investment_level' => $investment->id_investment_level,
            'initial_capital' => $investment->initial_capital,
            'min_estimated_return' => $investment->min_estimated_return,
            'max_estimated_return' => $investment->max_estimated_return,
            'risk_level' => $investment->risk_level,
            'description' => $investment->description,
            'short_description' => $investment->short_description,
            'url' => $investment->url,
            'banner' => !empty($investment->banner) ?  Storage::url('Investment/Banners/'. $investment->banner) : null,
            'banner_thumbnail' => !empty($investment->banner) ?  Storage::url('Investment/Banners/Thumbnails/'. $investment->banner) : null,
        ];
    }

}
