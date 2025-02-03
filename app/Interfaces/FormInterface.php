<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface FormInterface
{
    public function store(): JsonResponse;
    public function update(): JsonResponse;
}
