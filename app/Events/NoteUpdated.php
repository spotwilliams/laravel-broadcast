<?php

namespace App\Events;

use App\Models\Note;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NoteUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Note
     */
    private $note;
    /**
     * @var User
     */
    private $user;

    public function __construct(Note $note, User $user)
    {
        $this->note = $note;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PresenceChannel("note.{$this->note->slug}");
    }

    public function broadcastWith()
    {
        return [
            'slug' => $this->note->id,
            'user_id' => $this->user->id,
        ];
    }
}
