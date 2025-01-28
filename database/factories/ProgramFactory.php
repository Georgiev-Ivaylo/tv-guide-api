<?php

namespace Database\Factories;

use App\Models\Program;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Xylis\FakerCinema\Provider\Movie;
use Xylis\FakerCinema\Provider\TvShow;

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
        $randTvType = random_int(Program::TYPE_MOVIE, Program::TYPE_SHOW);
        $faker = FakerFactory::create();

        if ($randTvType === Program::TYPE_MOVIE) {
            $faker->addProvider(new Movie($faker));
            $title = $faker->movie;
        } else {
            $faker->addProvider(new TvShow($faker));
            $title = $faker->tvShow;
        }

        return [
            'channel_id' => '',
            'title' => $title,
            'type' => $randTvType,
            'start_datetime' => '',
            // 'end_datetime' => $endDatetime,
        ];
    }
}
