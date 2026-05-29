<?php

use App\Models\User;
use App\Models\Message;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;

uses(RefreshDatabase::class);

test('guest can view forgot password page', function () {
    $response = $this->get('/forgot-password');
    $response->assertStatus(200);
    $response->assertSee('Forgot Password');
});

test('forgot password form triggers notification and token generation', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertSessionHas('status');
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('user can reset password with a valid token', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);

    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('success');

    // Attempt login with new password
    $loginResponse = $this->post('/login', [
        'email' => $user->email,
        'password' => 'new-password123',
    ]);
    $loginResponse->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('user can delete their own message', function () {
    $user = User::factory()->create();
    $message = Message::create([
        'sender_id' => $user->id,
        'body' => 'Hello World',
        'type' => 'group',
    ]);

    $response = $this->actingAs($user)->delete("/chat/messages/{$message->id}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});

test('user cannot delete another user message', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $message = Message::create([
        'sender_id' => $userA->id,
        'body' => 'Hello World',
        'type' => 'group',
    ]);

    $response = $this->actingAs($userB)->delete("/chat/messages/{$message->id}");
    $response->assertStatus(403);
    $this->assertDatabaseHas('messages', ['id' => $message->id]);
});

test('only manager can clear group chat history', function () {
    $manager = User::factory()->create([
        'use_type' => 'group',
        'role' => 'manager',
    ]);

    $team = Team::create([
        'name' => 'Test Team',
        'code' => 'TEAM-1234',
        'manager_id' => $manager->id,
    ]);

    $manager->update(['team_id' => $team->id]);

    $member = User::factory()->create([
        'team_id' => $team->id,
        'use_type' => 'group',
        'role' => 'member',
    ]);

    Message::create([
        'sender_id' => $manager->id,
        'team_id' => $team->id,
        'body' => 'Group message',
        'type' => 'group',
    ]);

    // Member trying to clear -> forbidden
    $response = $this->actingAs($member)->delete('/chat/clear', ['type' => 'group']);
    $response->assertStatus(403);
    $this->assertEquals(1, Message::count());

    // Manager clearing -> success
    $response = $this->actingAs($manager)->delete('/chat/clear', ['type' => 'group']);
    $response->assertStatus(200);
    $this->assertEquals(0, Message::count());
});

test('dm clear only hides messages for that user', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $msg = Message::create([
        'sender_id' => $userA->id,
        'receiver_id' => $userB->id,
        'body' => 'Private message',
        'type' => 'dm',
    ]);

    // User A clears their copy
    $response = $this->actingAs($userA)->delete('/chat/clear', [
        'type' => 'dm',
        'with' => $userB->id,
    ]);
    $response->assertStatus(200);

    // Message should still exist in DB but marked as cleared by sender
    $this->assertDatabaseHas('messages', [
        'id' => $msg->id,
        'cleared_by_sender' => true,
        'cleared_by_receiver' => false,
    ]);

    // Query DM as User A -> should be empty
    $dmMessagesA = Message::dm($userA->id, $userB->id)->get();
    $this->assertCount(0, $dmMessagesA);

    // Query DM as User B -> should still have it
    // Acting as userB to let auth()->id() evaluate as userB
    $this->actingAs($userB);
    $dmMessagesB = Message::dm($userA->id, $userB->id)->get();
    $this->assertCount(1, $dmMessagesB);

    // User B clears their copy
    $response = $this->actingAs($userB)->delete('/chat/clear', [
        'type' => 'dm',
        'with' => $userA->id,
    ]);
    $response->assertStatus(200);

    // Since both cleared, it should be deleted from DB entirely
    $this->assertDatabaseMissing('messages', ['id' => $msg->id]);
});
