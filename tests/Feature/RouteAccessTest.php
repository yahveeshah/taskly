<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public routes return 200 homepage login register', function () {
    $this->get('/')->assertOk();
    $this->get('/login')->assertOk();
    $this->get('/register')->assertOk();
});

test('all protected routes redirect unauthenticated users to login', function () {
    $routes = [
        '/dashboard',
        '/tasks',
        '/progress',
        '/graph',
        '/track',
        '/team',
        '/profile/edit',
    ];

    foreach ($routes as $route) {
        $this->get($route)->assertRedirect('/login');
    }
});

test('authenticated personal user can access dashboard tasks progress graph track', function () {
    $user = User::factory()->create(['use_type' => 'personal']);

    $this->actingAs($user)->get('/dashboard')->assertOk();
    $this->actingAs($user)->get('/tasks')->assertOk();
    $this->actingAs($user)->get('/progress')->assertOk();
    $this->actingAs($user)->get('/graph')->assertOk();
    $this->actingAs($user)->get('/track')->assertOk();
});

test('authenticated manager can access the team page', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $manager->update(['team_id' => $team->id]);

    $this->actingAs($manager)->get('/team')->assertOk();
});

test('authenticated member cannot access the team management page', function () {
    $manager = User::factory()->create(['use_type' => 'group', 'role' => 'manager']);
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1111',
        'manager_id' => $manager->id,
    ]);
    $member = User::factory()->create(['use_type' => 'group', 'role' => 'member', 'team_id' => $team->id]);
    
    $this->actingAs($member)->get('/team')->assertStatus(403);
});
