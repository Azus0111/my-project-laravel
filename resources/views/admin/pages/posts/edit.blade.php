@extends('admin.layouts.index')
@section('title', "Edit Post")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Posts', 'url' => route('admin.posts.index')],
            ['title' => 'Edit']
        ]" />
    </div>


    <form novalidate action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Add Post</legend>

            <label class="label">Title</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="input validator w-full" required minlength="3"
                maxlength="128" placeholder="Post Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Content</label>
            <textarea name="content" class="textarea validator w-full" maxlength="512">{{ old('content', $post->content) }}</textarea>

            <label class="label">Status</label>
            <select name="status" class="select w-full">
                <option disabled selected>Select Status</option>
                <option @selected($post->status == 1) value="1">Active</option>
                <option {{ ($post->status == 0) ? 'selected' : '' }} value="0">Inactive</option>
            </select>

            <label class="label">Image</label>
            <input type="file" name="image" class="file-input w-full" />

            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <button type="submit" class="btn btn-neutral mt-4">Add</button>
        </fieldset>
    </form>
    @if($errors->any())
        <x-toast type="error" :messages="$errors->all()" />
    @endif
@endsection