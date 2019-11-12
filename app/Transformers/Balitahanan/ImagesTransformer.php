<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Balitahanan;

use App\Models\Balitahanan;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

class ImagesTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($post)
    {
        $data = [];

        foreach ($post->balitahananImg as $key => $value) {
            $data[$key]= [
                "imageUrl" => Storage::url('Balitahanan/'. $post->family_code .'/'. $post->balitahananImg[$key]->image_url),
                "thumbnail" => Storage::url('Balitahanan/'. $post->family_code .'/thumbnails/'. $post->balitahananImg[$key]->image_url),
            ];
        }

        return $data;
    }

}
