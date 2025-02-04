<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseTemplates = [
            [
                'action' => 'client_email_verification',
                'subject' => 'Pending verification',
                'params' => 'client',
                'content' => <<<HTML
                    <!DOCTYPE html>
                        <html lang="en-US">
                            <head>
                                <meta charset="utf-8">
                                <title>Pending verification</title>
                            </head>

                            <body>
                                Dear {{\$client->lastname}} !
                                <p>Please follow this link to complete your verification process -
                                    <a href="{{\$verification['url']}}"> {{\$verification['pretty_url']}}</p>
                                <p></p>
                                Thank You,
                                <br/>
                                    TvGuide Team
                                <br/>
                            </body>
                        </html>
                HTML,
            ],
            [
                'action' => 'client_welcome_email',
                'subject' => 'Welcome to our service',
                'params' => 'client',
                'content' => <<<HTML
                    <!DOCTYPE html>
                        <html lang="en-US">
                            <head>
                                <meta charset="utf-8">
                                <title>Welcome to our service</title>
                            </head>
                            <body>
                                Dear {{\$client->lastname}},
                                <p>Thank You for for the interest in our service.</p>
                                <p>Please follow this link to complete your registration process - <a href="{{\$verification['url']}}">{{\$verification['pretty_url']}}</p>
                                <p></p>
                                Thank You,
                                <br/>
                                    TvGuide Team
                                <br/>
                            </body>
                        </html>
                HTML,
            ],
        ];

        foreach ($baseTemplates as $baseTemplate) {
            $template = Template::where('action', $baseTemplate['action'])->first();
            if (!$template) {
                $template = new Template;
                $template->action = $baseTemplate['action'];
            }

            $template->active = true;
            $template->subject = $baseTemplate['subject'];
            $template->params = $baseTemplate['params'];
            $template->content = $baseTemplate['content'];

            $template->save();
        }
    }
}
