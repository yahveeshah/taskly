<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_status_and_delete_a_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'title' => 'Write project notes',
                'description' => 'Summarize the latest tasks.',
                'priority' => 'high',
                'deadline' => '2026-06-01',
            ])
            ->assertRedirect();

        $task = Task::query()->firstOrFail();

        $this->assertTrue($task->user->is($user));
        $this->assertSame('pending', $task->status);

        $this->actingAs($user)
            ->patch(route('tasks.update', $task), [
                'title' => 'Write better project notes',
                'description' => 'Summarize the latest tasks and blockers.',
                'priority' => 'medium',
                'deadline' => '2026-06-02',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Write better project notes',
            'priority' => 'medium',
        ]);

        $this->actingAs($user)
            ->patch(route('tasks.status', $task), ['status' => 'completed'])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);

        $this->actingAs($user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_users_cannot_update_or_delete_tasks_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::create([
            'user_id' => $owner->id,
            'title' => 'Private task',
            'description' => 'Belongs to someone else.',
            'priority' => 'low',
            'deadline' => '2026-06-01',
        ]);

        $this->actingAs($otherUser)
            ->patch(route('tasks.update', $task), [
                'title' => 'Hijacked task',
                'description' => 'Nope.',
                'priority' => 'high',
                'deadline' => '2026-06-02',
            ])
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Private task',
        ]);
    }

    public function test_task_priority_and_status_are_validated(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Validate me',
            'priority' => 'medium',
            'status' => 'pending',
            'deadline' => '2026-06-01',
        ]);

        $this->actingAs($user)
            ->from(route('tasks'))
            ->post(route('tasks.store'), [
                'title' => 'Invalid priority',
                'description' => null,
                'priority' => 'urgent',
                'deadline' => '2026-06-01',
            ])
            ->assertRedirect(route('tasks'))
            ->assertSessionHasErrors('priority');

        $this->actingAs($user)
            ->from(route('tasks'))
            ->patch(route('tasks.status', $task), ['status' => 'blocked'])
            ->assertRedirect(route('tasks'))
            ->assertSessionHasErrors('status');
    }

    public function test_authenticated_task_pages_render(): void
    {
        $user = User::factory()->create();

        Task::create([
            'user_id' => $user->id,
            'title' => 'Rendered task',
            'description' => 'Shows up on dashboard pages.',
            'priority' => 'medium',
            'status' => 'in_progress',
            'deadline' => '2026-06-01',
        ]);

        foreach ([route('tasks'), route('progress'), route('graph'), route('track'), route('profile.edit')] as $url) {
            $this->actingAs($user)
                ->get($url)
                ->assertOk();
        }
    }
}
