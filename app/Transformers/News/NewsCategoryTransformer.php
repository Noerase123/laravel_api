<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\News;

use App\Models\NewsCategory;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;


class NewsCategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(NewsCategory $newsCat)
    {
        return [
            'id' => $newsCat->getKey(),
            'slug' => $newsCat->slug,
            'name' => $newsCat->name,
            'created_at' => $newsCat->created_at->format(config('app.default_datetime_format')),
        ];
    }

}
