<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@taskmanagement.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Requested Admin User
        $requestedAdmin = User::create([
            'name' => 'Abdullah Alashrah',
            'email' => 'a.s.alashrah@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Manager User
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@taskmanagement.local',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Regular Users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'user1@taskmanagement.local',
            'password' => Hash::make('password'),
            'role' => 'user',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'user2@taskmanagement.local',
            'password' => Hash::make('password'),
            'role' => 'user',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Sample Tasks
        Task::create([
            'title' => 'Design Database Schema',
            'description' => 'Create the initial database schema for the application.',
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(1),
            'priority' => 'high',
            'status' => 'in_progress',
            'assignee_id' => $user1->id,
            'reporter_id' => $manager->id,
        ]);

        Task::create([
            'title' => 'Setup Authentication',
            'description' => 'Implement login and registration using Laravel Breeze.',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(2),
            'priority' => 'normal',
            'status' => 'not_started',
            'assignee_id' => $user2->id,
            'reporter_id' => $manager->id,
        ]);

        Task::create([
            'title' => 'Create API Documentation',
            'description' => 'Document all API endpoints.',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(5),
            'priority' => 'low',
            'status' => 'not_started',
            'assignee_id' => $user1->id,
            'reporter_id' => $manager->id,
        ]);

        Task::create([
            'title' => 'Overdue Task Example',
            'description' => 'This task should appear as overdue.',
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(1),
            'priority' => 'high',
            'status' => 'in_progress',
            'assignee_id' => $user2->id,
            'reporter_id' => $manager->id,
        ]);
    }
}
