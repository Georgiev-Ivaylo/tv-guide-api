<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['string',],
            'lastname' => ['string',],
            'username' => ['string',],
            'email' => ['email',],
            'password' => ['required_with:old_password', 'min:6',],
            'confirm_password' => ['required_with:password', 'same:password',],
            'old_password' => ['required_with:password', 'min:6',],
            '2fa' => ['boolean',],
        ];
    }
}
