<?php

namespace Database\Seeders;

use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xml = simplexml_load_file(database_path('/external/guide.xml'));

        $toInsert = [];
        $createdAt = Carbon::now()->toDateTimeString();
        $insertBy100 = 100;
        foreach ($xml->children() as $channel) {
            if (!isset($channel['id'])) {
                break;
            }

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
        Channel::insert($toInsert);
    }
}
