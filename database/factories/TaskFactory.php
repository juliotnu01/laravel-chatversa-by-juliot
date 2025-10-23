<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+6 months'),
            'estimated_hours' => $this->faker->numberBetween(1, 24),
            'actual_hours' => $this->faker->numberBetween(0, 30),
            'project_id' => Project::factory(),
            'assigned_to' => User::factory(),
        ];
    }
}