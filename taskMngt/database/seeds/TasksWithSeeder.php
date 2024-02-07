<?php

use App\Task;
use Illuminate\Database\Seeder;

class TasksWithSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = \Faker\Factory::create();

        // dummy data
        for ($i = 0; $i < 50; $i++) {
            Task::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'completed' => $faker->boolean
            ]);
        }
    }
}
