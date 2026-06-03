@extends('admin.layouts.index')
@section('title', "Posts")


@section('content')
    <div class="flex justify-between mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Posts']
        ]" />

        <x-toolbar createRoute="admin.posts.create" trashRoute="admin.posts.trash" />
    </div>

    <x-filters :fields="[
            ['type' => 'search', 'name' => 'search', 'placeholder' => 'Title or @author'],
            [
                'type' => 'select',
                'name' => 'status',
                'label' => 'Status',
                'options' => [
                    ['value' => '1', 'label' => 'Active'],
                    ['value' => '0', 'label' => 'Inactive'],
                ]
            ]
        ]" />

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($posts->isEmpty())
                    <tr>
                        <td class="text-center font-bold text-2xl" colspan="5">Danh sách trống</td>
                    </tr>
                @else
                    @foreach ($posts as $post)
                        <tr>
                            <th>{{ $post->id }}</th>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $post->title }}</div>
                                        <div class="text-sm opacity-50">{{ $post->user ? "@" . $post->user->name : "Unknown" }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="max-w-96 truncate">
                                {{ $post->content }}
                            </td>
                            <td>
                                <x-status-badge :status="$post->status" />
                            </td>
                            <td>
                                <x-action-buttons type="index" :id="$post->id" :name="$post->title" routeName="posts" />
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
    @if(session('success'))
        <x-toast :messages="[session('success')]" />
    @endif
    @if(session('error'))
        <x-toast type="error" :messages="[session('error')]" />
    @endif
@endsection