<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\News;

use App\Models\News;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;


class NewsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(News $news)
    {
        return [
            'id' => $news->getKey(),
            'title' => $news->title,
            'author' => $news->author,
            'content' => $news->content,
            'created_at' => $news->created_at->format(config('app.default_datetime_format')),
        ];
    }

}
