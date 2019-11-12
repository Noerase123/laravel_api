<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Repository;

use App\Models\Balitahanan;
use App\Models\User;
use App\Support\Repository\EloquentRepository;

/**
 * eloquent repository class for comment model
 */
class BalitahananRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\Balitahanan $post
     */
    public function __construct(Balitahanan $post)
    {
        parent::__construct($post);
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

    public function showAll($familyCode)
    {
        $model = $this->newModel();

        $join = $model->leftJoin('users', 'balitahanan.author_id', '=', 'users.id')
        ->select([
            'balitahanan.id',
            'users.firstname', 
            'users.lastname', 
            'users.extension_name',
            'balitahanan.image_url',
            'balitahanan.newsfeed_msg',
            'balitahanan.created_at',
            'balitahanan.family_code'
        ]);

        $join->where('balitahanan.family_code',$familyCode );

        return $join->orderBy('balitahanan.created_at', 'desc')
                    ->paginate(6, ['*']);
    }

    public function countData()
    {
        $model = $this->newModel();

        return $model->all()->count();
    }
}