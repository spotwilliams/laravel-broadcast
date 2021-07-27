<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});


Broadcast::channel('private-note.{slug}', function ($user, $slug) {

    return [
        'id'   => $user->id,
        'name' => $user->name
    ];

    /** @var \Illuminate\Database\Eloquent\Collection $editorsOfNote */
    $editorsOfNote = \App\Models\Note::where('slug', $slug)
        ->whereHas('editors', function (\Illuminate\Database\Eloquent\Builder $query) use ($user) {
            $query->whereIn('user_id', [$user->id]);
        })->get();

    // is the user is one editor the the particular note
    return $editorsOfNote->isNotEmpty();
});
