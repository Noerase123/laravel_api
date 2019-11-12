<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Http\Requests\Api\Investment;

use App\Models\Investment;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentRequest extends FormRequest
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
            'idInvestmentLevel' => [
                'required',
            ],
            'initialCapital' => [
                'required',
            ],
            'minEstimatedReturn' => [
                'required'
            ],
            'maxEstimatedReturn' => [
                'required'
            ],
            'riskLevel' => [
                'required'
            ],
            'description' => [
                'required'
            ],
            'url' => [
                'required'
            ],
        ];
    }
}
