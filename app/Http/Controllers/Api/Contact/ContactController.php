<?php
/**
 * @author Jeselle Bacosmo <jeselle@hallohallo.ph>
 */

namespace App\Http\Controllers\Api\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\Api\Contact\StoreContactRequest;
use App\Repository\ContactRepository;
use App\Transformers\Contacts\ContactTransformer;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ContactController extends Controller
{
    /**
     * create instance
     */
    public function __construct(ContactRepository $contacts)
    {
        $this->middleware('auth:api');
        $this->contacts = $contacts;
    }

    /**
     * inserting new contacts
     *
     * @param App\Http\Requests\Api\Contact\StoreContactRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function addContacts (StoreContactRequest $request)
    {

        $data = [
            'contact_name' => $request->contactName,
            'hotline' => $request->hotline,
            'address' => $request->address,
        ];

        $contact = $this->contacts->store($data);

        return response()
            ->json([
                'message' => trans('api.success_add_contact'),
            ], 201);
    }

    /**
     * Showing all contacts
     *
     * @return \Illuminate\Http\Response
     */
    public function showContacts ()
    {
        $contact = $this->contacts->showAll();

        return fractal($contact, new ContactTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($contact))
                ->serializeWith(new ArraySerializer)
                ->respond(201);
    }

    /**
     * Updating Contacts
     *
     * @param $id
     * @param App\Http\Requests\Api\Contact\StoreContactRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateContacts ($id, StoreContactRequest $request)
    {
        $contact = $this->contacts->checkId($id);

        if (is_null($contact)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $data = [
            'contact_name' => $request->contactName,
            'hotline' => $request->hotline,
            'address' => $request->address,
        ];

        $contact->update($data);
        
        return response()
            ->json([
                'message' => trans('api.success_update_contact'),
            ], 201);
    }

    /**
     * Deleting Contacts
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteContacts ($id)
    {
        $contact = $this->contacts->checkId($id);

        if (is_null($contact)) {
            return response()
                ->json([
                    'message' => trans('api.no_query_result'),
                ], 404);
        }

        $contact->delete();

        return response()
            ->json([
                'message' => trans('api.success_delete_contact'),
            ], 201);
    }
}