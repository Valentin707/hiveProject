<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Application::query()->exists()) {
            return;
        }

        $applications = [
            ['name' => 'HiveTasks', 'description' => 'Task orchestration'],
            ['name' => 'HiveCode', 'description' => 'Code workspace'],
            ['name' => 'HiveNews', 'description' => 'Network briefings'],
            ['name' => 'HiveLinks', 'description' => 'Saved destinations'],
            ['name' => 'HiveVault', 'description' => 'Secure storage'],
        ];

        foreach ($applications as $sortOrder => $application) {
            Application::query()->create([
                ...$application,
                'url' => 'https://hive.test',
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        }
    }
}
