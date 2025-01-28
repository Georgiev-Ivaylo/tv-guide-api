<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    private array $showLength = [30, 60, 40, 20, 120];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $endTime = Carbon::now()->addDays(15)->endOfDay()->toDateTimeString();
        Channel::chunk(50, function ($channels) use ($endTime) {
            foreach ($channels as $channel) {
                // 30 days by 24 hours
                $startTime = Carbon::now()->subDays(15)->startOfDay();
                while ($startTime->lt($endTime)) {
                    $randomTimeFrame = random_int(0, 4);
                    $startTime->addMinutes($this->showLength[$randomTimeFrame]);
                    Program::factory()->create([
                        'channel_id' => $channel->id,
                        'start_datetime' => $startTime->toDateTimeString()
                    ]);
                }
            }
        });
    }
}
