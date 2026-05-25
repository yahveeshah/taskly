<?php

namespace App\Models;

use Database\Factories\UserFactory;
use App\Models\Idea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'use_type', 'role', 'team_id', 'streak', 'last_streak_date'];

    protected $hidden = ['password', 'remember_token'];

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_streak_date' => 'date',
        ];
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function aiChatHistories()
    {
        return $this->hasMany(AiChatHistory::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function managedTeam()
    {
        return $this->hasOne(Team::class, 'manager_id');
    }

    public function isManager(): bool
    {
        return $this->use_type === 'group' && $this->role === 'manager';
    }

    public function isMember(): bool
    {
        return $this->use_type === 'group' && $this->role === 'member';
    }
}
