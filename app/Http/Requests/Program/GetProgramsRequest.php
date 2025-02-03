<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;

class GetProgramsRequest extends FormRequest
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
            'channel_id' => ['integer'],
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
