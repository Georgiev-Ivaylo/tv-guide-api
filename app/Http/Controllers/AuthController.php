<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Utils\ApiResponse;
use Illuminate\Support\Facades\Auth;
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
            $success['token'] =  $user->createToken('User-' . $user->name, ['publish'], now()->addHours(12))->plainTextToken;
            $success['name'] =  $user->name;

            return ApiResponse::success($success, 'User login successfully.');
        }

        return ApiResponse::error(Response::HTTP_UNPROCESSABLE_ENTITY, 'Bad credentials.', []);
    }
}
