<?php

namespace App\Http\Requests\Api\Web;

use App\Models\Web;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebRequest extends FormRequest
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
            'category' => [
                'required',
            ],
            'title' => [
                'required',
            ],
            'content' => [
                'required'
            ],
        ];

    }
}
