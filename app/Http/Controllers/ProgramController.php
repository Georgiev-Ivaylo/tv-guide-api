<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use App\Utils\ApiResponse;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // sleep(3);
        $user = $request->user();

        $programs = Program::query();
        // if ($user) {
        //     $programs->where('user_id', $user->id);
        // }

        // if ($request->input('order_by')) {
        //     $orderRules = explode(',', $request->input('order_by'));
        //     foreach ($orderRules as $orderRule) {
        //         if (Str::contains($orderRule, '-')) {
        //             $programs->orderBy(Str::replace('-', '', $orderRule), 'DESC');
        //             continue;
        //         }
        //         $programs->orderBy($orderRule, 'ASC');
        //     }
        // }
        // if ($request->input('query')) {
        //     $programs->where(
        //         'title',
        //         'ILIKE',
        //         "%{$request->input('query')}%"
        //     );
        // }

        if ($request->input('get_pages')) {
            return ProgramResource::collection($programs->paginate(9));
        }
        return ProgramResource::collection($programs->simplePaginate(9));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProgramRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $programId)
    {
        // dd(Program::with(['author'])->findOrFail($programId));
        return new ProgramsResource(Program::with(['author'])
            ->findOrFail($programId));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        Gate::authorize('forceDelete', $program);

        $program->delete();

        return ApiResponse::success([], [], 'Record deleted');
    }

    public function form()
    {
        $reader = new Reader(database_path('/external/GeoLite2-Country.mmdb'));

        $record = $reader->country('128.101.101.101');

        Log::debug(json_encode($record));
        $country = '';
        $buildingDataRequirement = 'required_unless:type,' . Program::TYPE_LAND . ',' . Program::TYPE_REGULATED_LAND;
        $typeOptions = [];
        foreach (Program::AVAILABLE_TYPES as $type) {
            $typeOptions[$type] = __('program.' . $type);
        }
        $constructionTypeOptions = [null => ''];
        foreach (Program::AVAILABLE_CONSTRUCTION_TYPES as $type) {
            $constructionTypeOptions[$type] = __('program.' . $type);
        }
        return ApiResponse::success([
            [
                'name' => 'price',
                'label' => __('program.price'),
                'type' => 'number',
                'validation' => ['required', 'float'],
            ],
            [
                'name' => 'currency_code',
                'label' => __('program.currency'),
                'type' => 'select',
                'options' => [
                    'BGN' => 'BGN',
                    'EUR' => 'EUR',
                    'USD' => 'USD',
                ],
                'validation' => ['required', 'in:BGN,EUR,USD'],
            ],
            [
                'name' => 'region',
                'label' => __('program.region'),
                'type' => 'text',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'city',
                'label' => 'City',
                'type' => 'text',
                'validation' => ['required_without:village', 'string'],
            ],
            [
                'name' => 'village',
                'label' => 'Village',
                'type' => 'text',
                'validation' => ['required_without:city', 'string'],
            ],
            [
                'name' => 'district',
                'label' => 'District',
                'type' => 'text',
                'validation' => ['required_with:city', 'string'],
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'select',
                'options' => $typeOptions,
                'validation' => ['required', 'in:' . implode(',', Program::AVAILABLE_TYPES)],
            ],
            [
                'name' => 'construction_type',
                'label' => 'Construction',
                'type' => 'select',
                'options' => $constructionTypeOptions,
                'validation' => [$buildingDataRequirement, 'in:' . implode(',', Program::AVAILABLE_CONSTRUCTION_TYPES)],
            ],
            [
                'name' => 'land_size',
                'label' => 'Land',
                'type' => 'number',
                'validation' => ['required_unless:type,' . Program::TYPE_APARTMENT, 'integer'],
            ],
            [
                'name' => 'building_size',
                'label' => 'Building',
                'type' => 'number',
                'validation' => [$buildingDataRequirement, 'integer'],
            ],
            [
                'name' => 'rooms',
                'label' => 'Rooms',
                'type' => 'number',
                'validation' => ['required_with:building_size', 'integer'],
            ],
            [
                'name' => 'bathrooms',
                'label' => 'Bathrooms',
                'type' => 'number',
                'validation' => ['required_with:building_size', 'integer'],
            ],
            [
                'name' => 'floors',
                'label' => 'Floors',
                'type' => 'number',
                'validation' => ['required_with:building_size', 'integer'],
            ],
            [
                'name' => 'floor_number',
                'label' => 'Floor number',
                'type' => 'number',
                'validation' => ['required_if:type,' . Program::TYPE_APARTMENT, 'integer'],
            ],
            [
                'name' => 'construction_date',
                'label' => 'Construction date',
                'type' => 'date',
                'validation' => ['required_with:building_size', 'date_format:Y-m-d'],
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'validation' => ['required', 'string'],
            ],
        ], [], 'Form structure');
    }
}
