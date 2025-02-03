<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\LoginRequest;
use App\Models\User;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function user(LoginRequest $credentials)
    {
        sleep(2);
        if (Auth::attempt(['email' => $credentials->email, 'password' => $credentials->password])) {
            /** @var User */
            $user = Auth::user();
            $success['token'] =  $user->createToken('User-' . $user->email, ['publish'], now()->addHours(12))->plainTextToken;
            $success['name'] =  $user->name;
            $success['redirect'] =  '/admin';

            return ApiResponse::success($success, 'User login successfully.');
        }

        return ApiResponse::error(Response::HTTP_UNPROCESSABLE_ENTITY, 'Bad credentials.', []);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function client(LoginRequest $credentials)
    {
        if (Auth::guard('client')->attempt(['email' => $credentials->email, 'password' => $credentials->password])) {
            $activeHours = 12;
            /** @var Client */
            $client = Auth::guard('client')->user();
            $success = [];
            $name = $client->lastname . " " . $client->firstname;
            $success['token'] =  $client->createToken('Client-' . $client->username, ['publish'], now()->addHours($activeHours))->plainTextToken;
            $success['name'] =  $name;
            $success['redirect'] =  '/';

            return ApiResponse::success($success,  [], __('auth.success', ['hours' => $activeHours]));
        }

        return ApiResponse::error(Response::HTTP_UNPROCESSABLE_ENTITY, __('auth.failure'), []);
    }
}
