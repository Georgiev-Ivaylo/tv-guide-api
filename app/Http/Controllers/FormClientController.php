<?php

namespace App\Http\Controllers;

use App\Interfaces\FormInterface;
use App\Utils\ApiResponse;
use Illuminate\Http\JsonResponse;

class FormClientController extends Controller implements FormInterface
{
    public function store(): JsonResponse
    {
        return ApiResponse::success([
            [
                'name' => 'firstname',
                'label' => __('client.firstname'),
                'type' => 'text',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'lastname',
                'label' => __('client.lastname'),
                'type' => 'text',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'username',
                'label' => __('client.username'),
                'type' => 'text',
                'validation' => ['nullable', 'string'],
            ],
            [
                'name' => 'email',
                'label' => __('client.email'),
                'type' => 'email',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'password',
                'label' => __('client.password'),
                'type' => 'password',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'confirm_password',
                'label' => __('client.confirm_password'),
                'type' => 'password',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => '2fa',
                'label' => __('client.2fa'),
                'type' => 'boolean',
                'validation' => ['required', 'boolean'],
            ],
        ]);
    }

    public function update(): JsonResponse
    {
        return ApiResponse::success([
            [
                'name' => 'firstname',
                'label' => __('client.firstname'),
                'type' => 'text',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'lastname',
                'label' => __('client.lastname'),
                'type' => 'text',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'username',
                'label' => __('client.username'),
                'type' => 'text',
                'validation' => ['nullable', 'string'],
            ],
            [
                'name' => 'email',
                'label' => __('client.email'),
                'type' => 'email',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'old_password',
                'label' => __('client.old_password'),
                'type' => 'password',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'password',
                'label' => __('client.password'),
                'type' => 'password',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => 'confirm_password',
                'label' => __('client.confirm_password'),
                'type' => 'password',
                'validation' => ['required', 'string'],
            ],
            [
                'name' => '2fa',
                'label' => __('client.2fa'),
                'type' => 'boolean',
                'validation' => ['required', 'boolean'],
            ],
        ]);
    }
}
