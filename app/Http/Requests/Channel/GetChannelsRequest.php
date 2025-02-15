<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

class GetChannelsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'query' => ['string'],
            'get_pages' => ['boolean'],
            'page_size' => ['integer'],
            'page' => ['integer'],
            'date' => ['date_format:Y-m-d'],
        ];
    }

    /**
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->get_pages) {
            $this->merge([
                'get_pages' => boolval($this->get_pages),
            ]);
        }
    }
}
