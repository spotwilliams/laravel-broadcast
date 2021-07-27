<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateController extends Controller
{
    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $note = Note::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . Str::random(10),
            'body' => $request->body
        ]);

        return redirect('/');
    }
}
