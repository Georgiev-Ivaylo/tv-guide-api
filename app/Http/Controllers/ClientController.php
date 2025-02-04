<?php

namespace App\Http\Controllers;

use App\Events\ClientVerification;
use App\Http\Requests\Client\ClientVerifyRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\ClientEmailVerification;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mime\Message;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $errors = [];
        $client = new Client;

        if ($request->username && $this->isClientExistsByUsername($request->username)) {
            $errors['username'] = [__('client.validation.unique_username')];
        }
        $client->username = $request->username ?? $request->email;

        if ($this->isClientExistsByEmail($request->email)) {
            $errors['email'] = [__('client.validation.unique_email')];
        }
        $client->email = $request->email;

        if (count($errors) >= 1) {
            throw ValidationException::withMessages($errors);
        }

        $client->firstname = $request->firstname;
        $client->lastname = $request->lastname;
        $client->password = $request->password;
        $client->{'2fa'} = $request->{'2fa'};

        $client->save();

        ClientVerification::dispatch($client);

        return ApiResponse::success(['redirect' => '/login'], [], __('client.created'));
    }

    protected function isClientExistsByEmail(string $email): bool
    {
        return Client::where('email', $email)->exists();
    }

    protected function isClientExistsByUsername(string $username): bool
    {
        return Client::where('username', $username)->exists();
    }

    /**
     * Display the specified resource.
     */
    // public function show(Client $client)
    public function show($request)
    {
        if (Auth::guard('sanctum')->user()) {
            // return ApiResponse::success(Auth::guard('client')->user());
            return new ClientResource(Auth::guard('sanctum')->user());
        }
        // Log::debug("Client session Show: " . (auth('client')->user() ? 'true' : 'false'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $errors = [];
        $needsEmailVerification = false;
        $client->firstname = $request->firstname ?? $client->firstname;
        $client->lastname = $request->lastname ?? $client->lastname;
        $client->{'2fa'} = $request->{'2fa'} ?? $client->{'2fa'};

        if ($request->username && $request->username !== $client->username && $this->isClientExistsByUsername($request->username)) {
            $errors['username'] = [__('client.validation.unique_username')];
        }
        $client->username = $request->username ?? $client->username;
        if ($request->email && $request->email !== $client->email && $this->isClientExistsByEmail($request->email)) {
            $errors['email'] = [__('client.validation.unique_email')];
        }
        if ($request->email && $request->email !== $client->email) {
            $needsEmailVerification = true;
        }
        $client->email = $request->email ?? $client->email;
        if ($request->password && !Hash::check($request->old_password, $client->password)) {
            $errors['old_password'] = [__('client.validation.old_password')];
        }
        $client->password = $request->password ?? $client->password;
        if (count($errors) >= 1) {
            throw ValidationException::withMessages($errors);
        }

        $client->save();
        if ($needsEmailVerification) {
            ClientVerification::dispatch($client);
        }

        return ApiResponse::success(['redirect' => '/account'], [], __('client.changed'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function resetVerification(UpdateClientRequest $request, Client $client)
    {
        Mail::raw('Test email', function ($message) {
            $message->to('test@example.com')->subject('Test Email');
        });

        return ApiResponse::success(['redirect' => '/login'], [], __('client.reset_verification'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(['redirect' => '/'], [], __('client.logout'));
    }

    public function verify(ClientVerifyRequest $request)
    {
        $verificationRequest = ClientEmailVerification::where('token', $request->token)
            ->where('email', $request->email)
            ->where('is_used', false)
            ->first();
        if (!$verificationRequest) {
            return ApiResponse::error(409, __('client.bad_verification'));
        }

        $verificationRequest->is_used = true;
        $verificationRequest->save();

        $date = now()->toDateTimeString();
        Client::where('id', $verificationRequest->client_id)
            ->update(['email_verified_at' => $date, 'updated_at' => $date]);

        return ApiResponse::success(['redirect' => '/'], [], __('client.verified'));
    }
}
