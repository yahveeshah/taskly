<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender:id,name');
    }

    public function broadcastOn()
    {
        if ($this->message->type === 'group') {
            return new PresenceChannel('team.' . $this->message->team_id);
        }

        $ids = [$this->message->sender_id, $this->message->receiver_id];
        sort($ids);
        return new PrivateChannel('dm.' . $ids[0] . '.' . $ids[1]);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'receiver_id' => $this->message->receiver_id,
                'team_id' => $this->message->team_id,
                'body' => $this->message->body,
                'type' => $this->message->type,
                'created_at' => $this->message->created_at->format('Y-m-d H:i:s'),
                'sender' => [
                    'id' => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                ]
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
