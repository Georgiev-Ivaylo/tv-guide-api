<?php

namespace App\Listeners;

use App\Events\ClientWelcome;
use App\Models\ClientEmailVerification;
use App\Models\Template;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SendClientWelcomeNotification extends Notification
{
    /**
     * Handle the event.
     */
    public function handle(ClientWelcome $event): void
    {
        $template = Template::where('action', 'client_welcome_email')->firstOrFail();
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
    }

    public function failed(ClientWelcome $event, Throwable $exception): void
    {
        // ...
    }
}
