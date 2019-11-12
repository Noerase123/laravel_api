<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Contacts;

use App\Models\Contact;
use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;


class ContactTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($contact)
    {
        return [
            'id' => $contact->getKey(),
            'contact_name' => $contact->contact_name ,
            'hotline' => $contact->hotline,
            'address' => $contact->address,
        ];
    }

}
