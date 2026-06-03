@extends('admin.layouts.index')
@section('title', "Categories")


@section('content')
    <div class="flex justify-between mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Categories', 'url' => route('admin.categories.index')],
            ['title' => 'Trash'],
        ]" />

        <x-toolbar backRoute="admin.categories.index" />
    </div>

    <form class="join mb-2" method="GET">
        <label class="input join-item">
            <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </g>
            </svg>
            <input type="search" name="search" class="w-3xs" value="{{ request('search')  }}" placeholder="Search" />
        </label>
        <select class="select join-item" name="status">
            <option value="" @selected(request('status', '') === '')>All</option>
            <option value="1" @selected(request('status') === '1')>Active</option>
            <option value="0" @selected(request('status') === '0')>Inactive</option>
        </select>
        <button class="btn join-item">Search</button>
    </form>

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($categories->isEmpty())
                    <tr>
                        <td class="text-center font-bold text-2xl" colspan="4">Danh sách trống</td>
                    </tr>
                @else
                    @foreach ($categories as $category)
                        <tr>
                            <th>{{ $category->id }}</th>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $category->name }}</div>
                                        {{-- <div class="text-sm opacity-50">United States</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-status-badge :status="$category->status" />
                            </td>
                            <td>
                                <x-action-buttons type="trash" :id="$category->id" :name="$category->name" routeName="categories" />
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
    @if(session('success'))
        <x-toast :messages="[session('success')]" />
    @endif
    @if(session('error'))
        <x-toast type="error" :messages="[session('error')]" />
    @endif
@endsection