<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FormClientController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to our API service',
        'data' => [],
    ]);
});

// Route::post('/login/user', [AuthController::class, 'user']);
Route::post('/admin/login', [AuthController::class, 'user']);

Route::apiResource('programs', ProgramController::class);
Route::get('/admin/programs/form', [ProgramController::class, 'form']);

Route::apiResource('channels', ChannelController::class);

Route::prefix('admin')
    ->middleware(['auth:sanctum'])
    ->as('admin.')
    ->group(
        function () {
            Route::apiResource('programs', ProgramController::class);
        }
    );


// Route::group(['admin'], [
//     Route::apiResource('programs', ProgramController::class),
// ])->middleware(['auth:sanctum', 'publish']);


Route::post('/login', [AuthController::class, 'client']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/forms/client', [FormClientController::class, 'store']);
Route::get('/forms/client/update', [FormClientController::class, 'update']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('/clients', ClientController::class, ['except' => ['store']]);
    Route::post('/logout', [ClientController::class, 'logout']);
});
