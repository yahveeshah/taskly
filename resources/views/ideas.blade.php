<x-layout title="Ideas">
    <h1>Submit an Idea</h1>

    <form method="POST" action="/ideas">
        @csrf

        <div>
            <label for="description">Create New Idea</label>
            <div>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                ></textarea>
            </div>

            @if ($errors->has('description'))
                <p>{{ $errors->first('description') }}</p>
            @endif

            <p>Have an idea you want to save for later?</p>
        </div>

        <button type="submit">Save</button>
    </form>

    <h2>All Ideas</h2>
    @foreach ($ideas as $idea)
        <p>
            {{ $idea->description }}
            <a href="/ideas/{{ $idea->id }}/edit">Edit</a>
            <form method="POST" action="/ideas/{{ $idea->id }}">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </p>
    @endforeach

</x-layout>