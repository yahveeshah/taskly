<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can update their name and it is saved in the database', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'New Name',
        'email' => $user->email,
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
    ]);
});

test('authenticated user can update their email and it is saved in the database', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => $user->name,
        'email' => 'newemail@example.com',
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'newemail@example.com',
    ]);
});

test('updating to an email already used by another user is rejected', function () {
    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user2 = User::factory()->create(['email' => 'user2@example.com']);

    $response = $this->actingAs($user1)->patch('/profile', [
        'name' => 'My Name',
        'email' => 'user2@example.com',
    ]);

    $response->assertSessionHasErrors('email');
});

test('authenticated user can change their password successfully', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile/password', [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHas('success');
});

test('password change fails if confirmation does not match', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile/password', [
        'password' => 'newpassword123',
        'password_confirmation' => 'wrongconfirmation',
    ]);

    $response->assertSessionHasErrors('password');
});

test('password change fails if new password is under minimum length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile/password', [
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertSessionHasErrors('password');
});

test('unauthenticated user cannot access the profile edit page', function () {
    $response = $this->get('/profile/edit');
    $response->assertRedirect('/login');
});
