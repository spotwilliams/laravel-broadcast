<?php

namespace App\Http\Controllers\Notes;

use App\Events\NoteUpdated;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EditController extends Controller
{
    public function edit(Note $note)
    {
        return Inertia::render('Notes/Edit', [
            'note' => $note
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $note->title = $request->title;
        $note->body = $request->body;

        $note->editors()->sync(Auth::user()->id, false);

        NoteUpdated::dispatch($note, Auth::user());

        $note->save();

        return response()->json(['note' => $note, 'success' => 'Note updated']);
    }
}
