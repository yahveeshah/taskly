<x-layout title="Edit Idea">
    <h1>Edit Idea</h1>

    <form method="POST" action="/ideas/{{ $idea->id }}">
        @csrf
        @method('PATCH')
        <input type="text" name="description" value="{{ $idea->description }}">
        <button type="submit">Update</button>
    </form>

</x-layout>