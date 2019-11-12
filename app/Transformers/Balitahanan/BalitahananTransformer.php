<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Balitahanan;

use App\Models\Balitahanan;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;
use App\Transformers\Balitahanan\ImagesTransformer;

class BalitahananTransformer extends TransformerAbstract
{
    
    protected $defaultIncludes  = [
        'images'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Balitahanan $post)
    {
        return [
            'id' => $post->getKey(),
            'name' => ucfirst($post->firstname). ' '. ucfirst($post->lastname) ,
            'alias' => ucfirst($post->extension_name),
            'message' => $post->newsfeed_msg,
            'created_at' => $post->created_at->format(config('app.default_datetime_format')),
        ];
    }

    public function includeImages($post)
    {       
        return $this->item($post, new ImagesTransformer);
    }
}
