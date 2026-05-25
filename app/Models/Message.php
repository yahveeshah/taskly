<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'team_id', 'body', 'type'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeGroupChat($query, $teamId)
    {
        return $query->where('team_id', $teamId)->where('type', 'group');
    }

    public function scopeDm($query, $userA, $userB)
    {
        return $query->where('type', 'dm')
            ->where(function ($q) use ($userA, $userB) {
                $q->where('sender_id', $userA)->where('receiver_id', $userB)
                  ->orWhere('sender_id', $userB)->where('receiver_id', $userA);
            });
    }
}
