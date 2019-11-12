<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Http\Requests\Api\Investment;

use App\Models\InvestmentLevel;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentLevelRequest extends FormRequest
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
            'investmentLevel' => [
                'required',
            ],
            'investmentName' => [
                'required',
            ],
            'minimumRange' => [
                'required'
            ],
            'maximumRange' => [
                'required'
            ],

        ];
    }
}
