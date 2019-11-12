<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Transformers\Family;

use App\Models\Family;
use League\Fractal\TransformerAbstract;

class FamilyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Family $family)
    {
        return [
            'id' => $family->getKey(),
            //'name' => $family->name,
            'code' => $family->code,
           // 'data' => $family->data,
            'dateAdded' => $family->{Family::CREATED_AT}->format(config('app.default_datetime_format')),
            //'links' => [
            //    'self' => route('me.show_family'),
            //    'members' => ''
           // ]
        ];
    }
}
