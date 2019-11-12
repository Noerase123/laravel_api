<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Requests\Api\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'firstname' => [
                'required',
            ],
            'lastname' => [
                'required',
            ],
            'middlename' => [
                'nullable'
            ],
            'email' => [
                'required'
            ],
            'password' => [
                'required'
            ],
            'extension_name' => [
                'nullable'
            ],
            'birthDate' => [
                'required'
            ],
            'contactNo' => [
                'required'
            ],
        ];
    }
}