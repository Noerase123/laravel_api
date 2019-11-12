<?php
/**
 * @author John Isaac Caasi <isaac@circus.ac>
 */

namespace App\Http\Requests\Api\PauwiNako;

use App\Models\PauwiNako;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PauwiNakoRequest extends FormRequest
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
            'date_of_return' => [
                'required',
            ],
        ];
    }
}