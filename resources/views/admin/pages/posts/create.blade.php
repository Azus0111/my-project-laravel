@extends('admin.layouts.index')
@section('title', "Add Post")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Posts', 'url' => route('admin.posts.index')],
            ['title' => 'Create']
        ]" />
    </div>


    <form novalidate action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Add Post</legend>

            <label class="label">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="input validator w-full" required minlength="3"
                maxlength="128" placeholder="Post Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Content</label>
            <textarea name="content" class="textarea validator w-full" maxlength="512">{{ old('content') }}</textarea>

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