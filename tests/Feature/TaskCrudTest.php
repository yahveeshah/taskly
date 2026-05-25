<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can create a task saved with correct title priority deadline and user_id', function () {
    $user = User::factory()->create(['use_type' => 'personal']);

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'My new task',
        'priority' => 'high',
        'deadline' => now()->addDays(2)->format('Y-m-d'),
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('tasks', [
        'title' => 'My new task',
        'priority' => 'high',
        'deadline' => now()->addDays(2)->format('Y-m-d 00:00:00'),
        'user_id' => $user->id,
    ]);
});

test('creating a task with missing title fails validation', function () {
    $user = User::factory()->create(['use_type' => 'personal']);

    $response = $this->actingAs($user)->post('/tasks', [
        'priority' => 'high',
        'deadline' => now()->addDays(2)->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('title');
});

test('creating a task with missing deadline fails validation', function () {
    $user = User::factory()->create(['use_type' => 'personal']);

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'My task',
        'priority' => 'high',
    ]);

    $response->assertSessionHasErrors('deadline');
});

test('creating a task with invalid priority fails validation', function () {
    $user = User::factory()->create(['use_type' => 'personal']);

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'My task',
        'priority' => 'super_high',
        'deadline' => now()->addDays(2)->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('priority');
});

test('authenticated user can update their own task', function () {
    $user = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->patch('/tasks/' . $task->id, [
        'title' => 'Updated title',
        'priority' => 'medium',
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated title',
        'priority' => 'medium',
    ]);
});

test('authenticated user cannot update another users task expects 403', function () {
    $user1 = User::factory()->create(['use_type' => 'personal']);
    $user2 = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user2->id,
    ]);

    $response = $this->actingAs($user1)->patch('/tasks/' . $task->id, [
        'title' => 'Hacked title',
        'priority' => 'high',
    ]);

    $response->assertStatus(403);
});

test('authenticated user can delete their own task and it is removed from the database', function () {
    $user = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete('/tasks/' . $task->id);

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

test('authenticated user cannot delete another users task expects 403', function () {
    $user1 = User::factory()->create(['use_type' => 'personal']);
    $user2 = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user2->id,
    ]);

    $response = $this->actingAs($user1)->delete('/tasks/' . $task->id);

    $response->assertStatus(403);
});

test('task status can be updated to in_progress', function () {
    $user = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user->id,
        'status' => 'pending'
    ]);

    $response = $this->actingAs($user)->patch('/tasks/' . $task->id . '/status', [
        'status' => 'in_progress',
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'in_progress',
    ]);
});

test('task status can be updated to completed', function () {
    $user = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user->id,
        'status' => 'in_progress'
    ]);

    $response = $this->actingAs($user)->patch('/tasks/' . $task->id . '/status', [
        'status' => 'completed',
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'completed',
    ]);
});

test('user cannot update the status of a task they do not own', function () {
    $user1 = User::factory()->create(['use_type' => 'personal']);
    $user2 = User::factory()->create(['use_type' => 'personal']);
    $task = Task::create([
        'title' => 'Task',
        'priority' => 'low',
        'deadline' => now()->addDays(1)->format('Y-m-d'),
        'user_id' => $user2->id,
        'status' => 'pending'
    ]);

    $response = $this->actingAs($user1)->patch('/tasks/' . $task->id . '/status', [
        'status' => 'completed',
    ]);

    $response->assertStatus(403);
});
