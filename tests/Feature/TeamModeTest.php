<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_registration_creates_team_and_member_can_join_with_code(): void
    {
        $this->post('/register', [
            'name' => 'Mina Manager',
            'email' => 'manager@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'use_type' => 'group',
            'role' => 'manager',
        ])->assertRedirect('/');

        $manager = User::where('email', 'manager@example.com')->firstOrFail();
        $team = Team::firstOrFail();

        $this->assertSame('group', $manager->use_type);
        $this->assertSame('manager', $manager->role);
        $this->assertSame($team->id, $manager->team_id);
        $this->assertStringStartsWith('TEAM-', $team->code);

        $this->post('/register', [
            'name' => 'Milo Member',
            'email' => 'member@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'use_type' => 'group',
            'role' => 'member',
            'team_code' => $team->code,
        ])->assertRedirect('/');

        $member = User::where('email', 'member@example.com')->firstOrFail();

        $this->assertSame('group', $member->use_type);
        $this->assertSame('member', $member->role);
        $this->assertSame($team->id, $member->team_id);
    }

    public function test_member_must_enter_valid_team_code(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Milo Member',
            'email' => 'member@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'use_type' => 'group',
            'role' => 'member',
            'team_code' => 'TEAM-0000',
        ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors('team_code');
    }

    public function test_manager_can_assign_tasks_and_member_can_only_update_their_status(): void
    {
        [$manager, $member] = $this->createTeamUsers();

        $this->actingAs($manager)
            ->post(route('tasks.store'), [
                'title' => 'Prepare report',
                'description' => 'Weekly status report.',
                'priority' => 'high',
                'deadline' => '2026-06-01',
                'assigned_user_id' => $member->id,
            ])
            ->assertRedirect();

        $task = Task::firstOrFail();
        $this->assertSame($member->id, $task->user_id);

        $this->actingAs($member)->get(route('tasks'))
            ->assertOk()
            ->assertSee('Prepare report')
            ->assertDontSee('Add Task');

        $this->actingAs($member)
            ->post(route('tasks.store'), [
                'title' => 'Member-created task',
                'priority' => 'low',
                'deadline' => '2026-06-01',
            ])
            ->assertForbidden();

        $this->actingAs($member)
            ->patch(route('tasks.status', $task), ['status' => 'completed'])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_manager_team_pages_and_team_wide_graph_render(): void
    {
        [$manager, $member] = $this->createTeamUsers();

        Task::create([
            'user_id' => $member->id,
            'title' => 'Member task',
            'priority' => 'medium',
            'status' => 'in_progress',
            'deadline' => '2026-06-01',
        ]);

        Task::create([
            'user_id' => $manager->id,
            'title' => 'Manager task',
            'priority' => 'low',
            'status' => 'completed',
            'deadline' => '2026-06-02',
        ]);

        $this->actingAs($manager)->get(route('team.index'))
            ->assertOk()
            ->assertSee($member->name)
            ->assertSee($manager->team->code);

        $this->actingAs($manager)->get(route('team.member', $member))
            ->assertOk()
            ->assertSee('Member task')
            ->assertSee('Progress Graph');

        $this->actingAs($manager)->get(route('graph'))
            ->assertOk()
            ->assertSee('Total: 2 tasks');
    }

    private function createTeamUsers(): array
    {
        $manager = User::factory()->create([
            'use_type' => 'group',
            'role' => 'manager',
        ]);

        $team = Team::create([
            'name' => 'Product Team',
            'code' => 'TEAM-4821',
            'manager_id' => $manager->id,
        ]);

        $manager->update(['team_id' => $team->id]);

        $member = User::factory()->create([
            'use_type' => 'group',
            'role' => 'member',
            'team_id' => $team->id,
        ]);

        return [$manager->fresh(), $member->fresh()];
    }
}
