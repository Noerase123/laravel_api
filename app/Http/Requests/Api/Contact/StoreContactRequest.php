<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Http\Requests\Api\Contact;

use App\Models\Contact;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contactName' => [
                'required',
            ],
            'hotline' => [
                'required',
            ],
            'address' => [
                'required'
            ],

        ];
    }
}
