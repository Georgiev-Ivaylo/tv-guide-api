<?php

namespace App\Http\Requests;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    // /**
    //  * Determine if the user is authorized to make this request.
    //  */
    // public function authorize(User $user): bool
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $buildingDataRequirement = 'required_unless:type,' . Program::TYPE_LAND . ',' . Program::TYPE_REGULATED_LAND;
        return [
            'price' => ['required', 'numeric'],
            'currency_code' => ['required', 'in:BGN,EUR,USD'],
            'region' => ['required', 'string'],
            'city' => ['required', 'string'],
            'village' => ['required', 'string'],
            'district' => ['required_with:city', 'string'],
            'type' => ['required', 'in:' . implode(',', Program::AVAILABLE_CONSTRUCTION_TYPES)],
            'construction_type' => [$buildingDataRequirement, 'in:' . implode(',', Program::AVAILABLE_CONSTRUCTION_TYPES)],
            'land_size' => ['required_unless:type,' . Program::TYPE_APARTMENT, 'integer'],
            'building_size' => [$buildingDataRequirement, 'integer'],
            'rooms' => ['required_with:building_size', 'integer'],
            'bathrooms' => ['required_with:building_size', 'integer'],
            'floors' => ['required_with:building_size', 'integer'],
            'floor_number' => ['required_if:type,' . Program::TYPE_APARTMENT, 'integer'],
            'description' => ['required', 'string'],
            'construction_date' => ['required_with:building_size', 'date_format:Y-m-d'],
        ];
    }
}
