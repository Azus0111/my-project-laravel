@extends('admin.layouts.index')
@section('title', "Categories")


@section('content')
    <div class="flex justify-between mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Categories']
        ]" />

        <x-toolbar createRoute="admin.categories.create" trashRoute="admin.categories.trash" />
    </div>

    <x-filters :fields="[
            ['type' => 'search', 'name' => 'search', 'placeholder' => 'Search by name'],
            [
                'type' => 'select',
                'name' => 'status',
                'options' => [
                    ['value' => '1', 'label' => 'Active'],
                    ['value' => '0', 'label' => 'Inactive']
                ],
                'label' => 'Status'
            ],
        ]" />

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
                                <x-action-buttons type="index" :id="$category->id" :name="$category->name" routeName="categories" />
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