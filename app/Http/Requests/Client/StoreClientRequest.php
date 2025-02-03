<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string',],
            'lastname' => ['required', 'string',],
            'username' => ['nullable', 'string',],
            'email' => ['required', 'email',],
            'password' => ['required', 'min:6',],
            'confirm_password' => ['required', 'same:password',],
            '2fa' => ['required', 'boolean',],
        ];
    }
}
