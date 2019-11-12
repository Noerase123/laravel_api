<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Repository;

use App\Models\Contact;
use App\Support\Repository\EloquentRepository;

/**
 * eloquent repository class for contact model
 */
class ContactRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\Contact $contact
     */
    public function __construct(Contact $contact)
    {
        parent::__construct($contact);
    }

    /**
     * store newly created contact to the storage
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

    public function showAll()
    {
        $model = $this->newModel();

        return $model->paginate( 10, ['*']);
    }

    public function checkId($id)
    {
        $model = $this->newModel();

        $result = $model->where('id',$id)->first();

        //check if the  $model is null
        if (is_null($result)) {
            return;
        }

        return $result;
    }
}