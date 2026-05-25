<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register with valid data and is redirected to dashboard', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'personal',
    ]);

    $response->assertRedirect('/');
});

test('registered user data is saved correctly in the users table', function () {
    $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'use_type' => 'personal',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
});

test('registration fails with missing name missing email invalid email format mismatched passwords and password under minimum length', function () {
    $response = $this->post('/register', [
        // missing name, email, etc.
    ]);
    $response->assertSessionHasErrors(['name', 'email', 'password']);

    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'not-an-email',
        'password' => 'pass',
        'password_confirmation' => 'pass2',
        'use_type' => 'personal',
    ]);
    $response->assertSessionHasErrors(['email', 'password']);
});

test('login with correct credentials redirects to dashboard', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('login with wrong password fails with a validation error', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login with non-existent email fails', function () {
    $response = $this->post('/login', [
        'email' => 'missing@example.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('unauthenticated user visiting tasks is redirected to login', function () {
    $this->get('/tasks')->assertRedirect('/login');
});

test('unauthenticated user visiting dashboard is redirected to login', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('unauthenticated user visiting progress is redirected to login', function () {
    $this->get('/progress')->assertRedirect('/login');
});

test('unauthenticated user visiting graph is redirected to login', function () {
    $this->get('/graph')->assertRedirect('/login');
});

test('unauthenticated user visiting track is redirected to login', function () {
    $this->get('/track')->assertRedirect('/login');
});

test('logout works and redirects away from protected pages', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('after logout protected routes are inaccessible without logging in again', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});
