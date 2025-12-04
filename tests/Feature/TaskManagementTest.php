<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'New Test Task',
            'description' => 'This is a test task description.',
            'status' => 'not_started',
            'priority' => 'normal',
            'start_date' => now()->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'assignee_id' => $user->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'New Test Task']);
    }

    public function test_admin_can_see_all_tasks()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/tasks', [
            'title' => 'User Task',
            'description' => 'Description',
            'status' => 'not_started',
            'priority' => 'normal',
            'start_date' => now(),
            'end_date' => now()->addDay(),
            'assignee_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/tasks');
        $response->assertStatus(200);
        $response->assertSee('User Task');
    }
}
