<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $xml = simplexml_load_file(database_path('/external/guide.xml'));

        $toInsert = [];
        $createdAt = Carbon::now()->toDateTimeString();
        $insertBy100 = 100;
        foreach ($xml->children() as $channel) {
            if (!isset($channel['id'])) {
                break;
            }
            // Log::debug($channel);

            $toInsert[] = [
                'title' => (string)$channel['id'],
                'description' => (string)$channel->{'display-name'},
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            --$insertBy100;
            if ($insertBy100 === 0) {
                Channel::insert($toInsert);

                $insertBy100 = 100;
                $toInsert = [];
            }
        }
        Log::debug($toInsert);
        Channel::insert($toInsert);

        return $toInsert;
    }
}
