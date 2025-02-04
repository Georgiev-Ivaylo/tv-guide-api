<?php

namespace App\Listeners;

use App\Events\ClientVerification;
use App\Models\ClientEmailVerification;
use App\Models\Template;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SendClientVerificationNotification extends Notification
{
    /**
     * Handle the event.
     */
    public function handle(ClientVerification $event): void
    {
        $template = Template::where('action', 'client_email_verification')->firstOrFail();
        ClientEmailVerification::where('client_id', $event->client->id)
            ->where('is_used', false)
            ->update(['expires_at' => now()->subYears(1)->toDateTimeString(), 'is_used' => true]);

        $verification = new ClientEmailVerification;
        $verification->client_id = $event->client->id;
        $verification->email = $event->client->email;
        $verification->token = md5(Str::random(6) . $verification->email . Str::random(6));
        $verification->expires_at = now()->addHours(4)->toDateTimeString();

        $verification->save();
        Mail::html(Blade::render(
            $template->content,
            [
                'client' => $event->client,
                'verification' => [
                    'url' => 'http://localhost:3000/verify?token=' . urlencode($verification->token) . '&email=' . urlencode($verification->email),
                    'pretty_url' => 'Follow me...',
                ],
            ],
            deleteCachedView: true
        ), function ($message) use ($event, $template) {
            $message->to($event->client->email)->subject($template->subject);
        });
        // Mail::send([
        //     'html' => $html,
        //     'text' => 'Hello {{ $user }}, welcome to Laravel!',
        //     'raw'  => 'Hello, welcome to Laravel!'
        // ], [
        //     'user' => 'John Doe'
        // ], function ($message) {
        //   $message
        //     ->to(...)
        //     ->subject(...);
        // });
        // Mail::to($request->user())->send(new Mailable([]));
        // Mail::send([], $client->toArray(), function (Message $message) use ($request) {
        //     // $message->from($sender->from, $sender->from_safe);
        //     $message->to($request->email);
        //     $message->subject('New email');
        //     $message->setBody('Follow the link for verification', 'text/html');
        // });
    }

    public function failed(ClientVerification $event, Throwable $exception): void
    {
        // ...
    }
}
