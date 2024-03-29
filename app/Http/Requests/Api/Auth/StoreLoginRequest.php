<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoginRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'min:' . config('app.min_password_length'),
            ]
        ];
    }
}