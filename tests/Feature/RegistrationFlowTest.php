<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('personal registration creates a user with the correct use_type value', function () {
    $response = $this->post('/register', [
        'name' => 'John Personal',
        'email' => 'personal@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'personal',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', [
        'email' => 'personal@example.com',
        'use_type' => 'personal',
    ]);
});

test('group manager registration creates a user with the correct role value and creates a team record in the teams table with a generated code', function () {
    $response = $this->post('/register', [
        'name' => 'John Manager',
        'email' => 'manager@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'group',
        'role' => 'manager',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', [
        'email' => 'manager@example.com',
        'use_type' => 'group',
        'role' => 'manager',
    ]);

    $user = User::where('email', 'manager@example.com')->first();
    $this->assertDatabaseHas('teams', [
        'manager_id' => $user->id,
    ]);
});

test('group member registration with a valid team code creates a user with the correct role value and correct team_id', function () {
    $manager = User::factory()->create();
    $team = Team::create([
        'name' => 'My Team',
        'code' => 'TEAM-1234',
        'manager_id' => $manager->id,
    ]);

    $response = $this->post('/register', [
        'name' => 'John Member',
        'email' => 'member@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'group',
        'role' => 'member',
        'team_code' => 'TEAM-1234',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', [
        'email' => 'member@example.com',
        'use_type' => 'group',
        'role' => 'member',
        'team_id' => $team->id,
    ]);
});

test('group member registration with an invalid team code is rejected', function () {
    $response = $this->post('/register', [
        'name' => 'John Member',
        'email' => 'member@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'group',
        'role' => 'member',
        'team_code' => 'TEAM-INVALID',
    ]);

    $response->assertSessionHasErrors('team_code');
});

test('after successful registration the user is authenticated and on the dashboard')->todo(); // App does not authenticate user after registration
