<?php
/**
 * @author John isaac Caasi <isaac@circus.ac>
 */

namespace App\Repository;

use App\Models\NewsImages;
use App\Models\User;
use App\Support\Repository\EloquentRepository;

/**
 * eloquent repository class for comment model
 */
class NewsImagesRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\NewsImages $post
     */
    public function __construct(NewsImages $images)
    {
        parent::__construct($images);
    }

    /**
     * store newly created user to the storage
     *
     * @param  array $attributes
     *
     * @return void
     */
    public function store(array $attributes)
    {
        $model = $this->newModel();

        // place attribute values to the model
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        return ($model->save()) ? $model : null;
    }
    
}