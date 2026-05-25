<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    if ($user->team_id == $teamId) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    return false;
});

Broadcast::channel('dm.{userA}.{userB}', function ($user, $userA, $userB) {
    $isParticipant = $user->id == $userA || $user->id == $userB;
    if (!$isParticipant) return false;
    $otherId = $user->id == $userA ? $userB : $userA;
    $other = \App\Models\User::find($otherId);
    if (!$other) return false;
    return $user->team_id === $other->team_id && $user->team_id !== null;
});
