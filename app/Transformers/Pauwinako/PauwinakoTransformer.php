<?php
/**
 * @author John Isaac Caasi <isaac@circus.ac>
 */

namespace App\Transformers\PauwiNako;

use App\Models\PauwiNako;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class PauwinakoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(PauwiNako $pn)
    {
        return [
            'id' => $pn->getKey(),
            'ofw_id' => $pn->ofw_id,
            'family_code' => $pn->family_code,
            'date_of_return' => $pn->date_of_return,
            'created_at' => $pn->created_at->format(config('app.default_datetime_format')),
        ];
    }

}
