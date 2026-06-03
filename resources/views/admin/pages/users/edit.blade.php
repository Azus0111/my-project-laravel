@extends('admin.layouts.index')
@section('title', "Edit User")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Users', 'url' => route('admin.users.index')],
            ['title' => 'Edit']
        ]" />
    </div>

    <form novalidate action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Edit User</legend>

            <label class="label">Name</label>
            <input type="text" name="name" pattern="^[A-Za-z0-9_]+$" value="{{ old('name', $user->name) }}"
                class="input validator w-full" required minlength="3" maxlength="30" placeholder="User Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Full Name</label>
            <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" class="input validator w-full"
                required minlength="3" maxlength="30" placeholder="Full Name" />

            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input validator w-full"
                required minlength="3" maxlength="45" placeholder="Email" />

            <label class="label">New Password</label>
            <input type="password" name="password" value="{{ old('password') }}" class="input validator w-full"
                minlength="6" maxlength="15" placeholder="New Password" />

            <label class="label">Confirm New Password</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                class="input validator w-full" minlength="6" maxlength="15" placeholder="Confirm New Password" />

            <label class="label">Role</label>
            <select name="role" class="select w-full">
                <option disabled selected>Select Role</option>
                <option {{ ($user->role == '1') ? 'selected' : '' }} value="1">Admin</option>
                <option {{ ($user->role == '0') ? 'selected' : '' }} value="0">User</option>
            </select>

            <label class="label">Avatar</label>
            <input type="file" name="avatar" class="file-input w-full" />

            <button type="submit" class="btn btn-neutral mt-4">Update</button>
        </fieldset>
    </form>
    @if($errors->any())
        <x-toast type="error" :messages="$errors->all()" />
    @endif
@endsection