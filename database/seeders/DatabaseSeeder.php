<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Project::factory(5)->create(['owner_id' => $user->id])
            ->each(function ($project) use ($user) {
                Task::factory(rand(3, 8))->create([
                    'project_id' => $project->id,
                    'assigned_to' => rand(0, 1) ? $user->id : null,
                ]);
            });
    }
}
