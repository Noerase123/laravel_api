<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Http\Requests\Api\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Repository\FamilyRepository;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'firstname' => [
                'required',
            ],
            'lastname' => [
                'required'
            ],
            'birthDate' => [
                'required'
            ],
            'password' => [
                'required',
                'min:' . config('app.min_password_length'),
            ],
            'confirmPassword' => [
                'required',
                'same:password'
            ],
            'type' => [
                'required',
                Rule::in(User::USER_TYPE_FAMILY_MEMBER, User::USER_TYPE_OFW, User::USER_TYPE_ADMIN)
            ],
            'owwaId' => [
                'nullable'
                //'required_if:type,' . User::USER_TYPE_OFW
            ],
            'contactNo' => [
                'required'
            ],
            'middlename' => [
                'nullable'
            ],
            'familyInvitationCode' => [
                'nullable',
                //'required_if:type,' . User::USER_TYPE_FAMILY_MEMBER,
                Rule::exists(app(FamilyRepository::class)->table(), 'code')
            ],
        ];
    }
}