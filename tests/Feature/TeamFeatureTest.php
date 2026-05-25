<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('manager registration generates and stores a team code', function () {
    $response = $this->post('/register', [
        'name' => 'Manager',
        'email' => 'manager@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'group',
        'role' => 'manager',
    ]);

    $response->assertRedirect('/');
    
    $user = User::where('email', 'manager@example.com')->first();
    $this->assertNotNull($user->team_id);
    
    $team = Team::find($user->team_id);
    $this->assertNotNull($team->code);
    $this->assertEquals($user->id, $team->manager_id);
});

test('member can join a team with a valid team code', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-9999',
        'manager_id' => $manager->id,
    ]);

    $response = $this->post('/register', [
        'name' => 'Member',
        'email' => 'member@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'group',
        'role' => 'member',
        'team_code' => 'TEAM-9999',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', [
        'email' => 'member@example.com',
        'team_id' => $team->id,
        'role' => 'member',
    ]);
});

test('member cannot join with an invalid team code', function () {
    $response = $this->post('/register', [
        'name' => 'Member',
        'email' => 'member2@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'group',
        'role' => 'member',
        'team_code' => 'INVALID-CODE',
    ]);

    $response->assertSessionHasErrors('team_code');
});

test('manager can assign a task to a team member', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $manager->update(['team_id' => $team->id]);
    
    $member = User::factory()->create([
        'use_type' => 'group',
        'role' => 'member',
        'team_id' => $team->id,
    ]);

    $response = $this->actingAs($manager)->post('/tasks', [
        'title' => 'Assigned Task',
        'priority' => 'high',
        'deadline' => now()->addDays(2)->format('Y-m-d'),
        'assigned_user_id' => $member->id,
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('tasks', [
        'title' => 'Assigned Task',
        'user_id' => $member->id,
    ]);
});

test('member can only see tasks assigned to them', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $manager->update(['team_id' => $team->id]);
    
    $member1 = User::factory()->create(['use_type' => 'group', 'role' => 'member', 'team_id' => $team->id]);
    $member2 = User::factory()->create(['use_type' => 'group', 'role' => 'member', 'team_id' => $team->id]);

    Task::create(['title' => 'Member 1 Task', 'priority' => 'low', 'deadline' => now()->addDay()->format('Y-m-d'), 'user_id' => $member1->id]);
    Task::create(['title' => 'Member 2 Task', 'priority' => 'low', 'deadline' => now()->addDay()->format('Y-m-d'), 'user_id' => $member2->id]);

    $response = $this->actingAs($member1)->get('/tasks');
    $response->assertOk();
    $response->assertSee('Member 1 Task');
    $response->assertDontSee('Member 2 Task');
});

test('member cannot access the team management page', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $member = User::factory()->create(['use_type' => 'group', 'role' => 'member', 'team_id' => $team->id]);
    
    $response = $this->actingAs($member)->get('/team');
    $response->assertStatus(403);
});

test('manager can access the team management page', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $manager->update(['team_id' => $team->id]);

    $response = $this->actingAs($manager)->get('/team');
    $response->assertOk();
});

test('manager can view individual member progress', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $manager->update(['team_id' => $team->id]);
    
    $member = User::factory()->create(['use_type' => 'group', 'role' => 'member', 'team_id' => $team->id]);

    $response = $this->actingAs($manager)->get('/team/members/' . $member->id);
    $response->assertOk();
});
