<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {
        $notes = Note::orderBy('updated_at', 'DESC')
            ->paginate(10);

        return Inertia::render('Notes/Index', [
            'notes' => $notes
        ]);
    }
}
