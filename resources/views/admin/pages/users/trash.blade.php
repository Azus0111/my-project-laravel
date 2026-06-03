@extends('admin.layouts.index')
@section('title', "Users")


@section('content')
    <div class="flex justify-between">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Users', 'url' => route('admin.users.index')],
            ['title' => 'Trash']
        ]" />

        <x-toolbar backRoute="admin.users.index" />
    </div>

    <x-filters :fields="[
            ['type' => 'search', 'name' => 'search', 'placeholder' => 'Username, Fullname or Email'],
            [
                'type' => 'select',
                'name' => 'role',
                'options' => [
                    ['value' => '1', 'label' => 'User'],
                    ['value' => '0', 'label' => 'Admin']
                ],
                'label' => 'Role'
            ]
        ]" />


    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($users->isEmpty())
                    <tr>
                        <td class="text-center font-bold text-2xl" colspan="5">Danh sách trống</td>
                    </tr>
                @else
                    @foreach ($users as $user)
                        <tr>
                            <th>{{ $user->id }}</th>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $user->name }}</div>
                                        <div class="text-sm opacity-50">{{ $user->fullname }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>
                                <x-status-badge :role="$user->role" />
                            </td>
                            <td>
                                <x-action-buttons type="trash" :id="$user->id" :name="$user->name" routeName="users" />
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    @if(session('success'))
        <x-toast :messages="[session('success')]" />
    @endif
    @if(session('error'))
        <x-toast type="error" :messages="[session('error')]" />
    @endif
@endsection