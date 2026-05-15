<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Gate;

class IdeaController extends Controller
{
    public function index()
    {
        $ideas = auth()->user()->ideas;
        return view('ideas', ['ideas' => $ideas]);
    }

    public function store()
    {
        $validated = request()->validate([
            'description' => ['required', 'min:3', 'max:255']
        ]);

        $validated['user_id'] = auth()->id();

        Idea::create($validated);
        return redirect('/ideas');
    }

    public function edit($id)
    {
        $idea = Idea::find($id);
        Gate::authorize('edit-idea', $idea);
        return view('ideas-edit', ['idea' => $idea]);
    }

    public function update($id)
    {
        $idea = Idea::find($id);
        Gate::authorize('edit-idea', $idea);
        $idea->update(['description' => request('description')]);
        return redirect('/ideas');
    }

    public function destroy($id)
    {
        $idea = Idea::find($id);
        Gate::authorize('edit-idea', $idea);
        $idea->delete();
        return redirect('/ideas');
    }
}