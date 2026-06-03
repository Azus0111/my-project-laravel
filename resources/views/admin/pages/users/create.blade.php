@extends('admin.layouts.index')
@section('title', "Add User")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Users', 'url' => route('admin.users.index')],
            ['title' => 'Create']
        ]" />
    </div>

    <form novalidate action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Add User</legend>

            <label class="label">Name</label>
            <input type="text" name="name" pattern="^[A-Za-z0-9_]+$" value="{{ old('name') }}"
                class="input validator w-full" required minlength="3" maxlength="30" placeholder="User Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Full Name</label>
            <input type="text" name="fullname" value="{{ old('fullname') }}" class="input validator w-full" required
                minlength="3" maxlength="30" placeholder="Full Name" />

            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input validator w-full" required
                minlength="3" maxlength="45" placeholder="Email" />

            <label class="label">Password</label>
            <input type="password" name="password" value="{{ old('password') }}" class="input validator w-full" required
                minlength="3" maxlength="20" placeholder="Password" />

            <label class="label">Confirm Password</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                class="input validator w-full" required minlength="3" maxlength="20" placeholder="Confirm Password" />

            <label class="label">Avatar</label>
            <input type="file" name="avatar" class="file-input w-full" />

            <button type="submit" class="btn btn-neutral mt-4">Add</button>
        </fieldset>
    </form>
    @if($errors->any())
        <x-toast :type="'error'" :messages="$errors->all()" />
    @endif
@endsection