<?php

namespace Database\Factories;

use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
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
        $lastChannel = null;
        $endDatetime = null;
        $channel = null;
        $insertBy100 = 100;
        foreach ($xml->children() as $program) {
            if (!isset($program['start'])) {
                continue;
            }

            $channel = (string) $program['channel'];

            if (is_null($lastChannel)) {
                $lastChannel = $channel;
            }

            if ($channel !== $lastChannel) {
                $lastChannel = $program['channel'];
                $endDatetime = Carbon::parse($program['stop'])->toDateTimeString();
            }

            $toInsert[] = [
                'channel' => $channel,
                'title' => (string)$program->title,
                'start_datetime' => Carbon::parse($program['start'])->toDateTimeString(),
                'end_datetime' => $endDatetime,
            ];
            $endDatetime = null;

            --$insertBy100;
            if ($insertBy100 === 0) {
                // $clearedFromXml = json_encode($toInsert);
                // Program::insert(json_decode($clearedFromXml, 1));
                Program::insert($toInsert);

                $insertBy100 = 100;
                $toInsert = [];
            }
        }
        // $clearedFromXml = json_encode($toInsert);
        // Program::insert(json_decode($clearedFromXml, 1));
        Program::insert($toInsert);

        return $toInsert;
    }
}
