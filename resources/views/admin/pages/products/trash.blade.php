@extends('admin.layouts.index')
@section('title', "Products")


@section('content')
    <div class="flex justify-between mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Products', 'url' => route('admin.products.index')],
            ['title' => 'Trash']
        ]" />

        <x-toolbar backRoute="admin.products.index" />
    </div>

    <x-filters :fields="[
            ['type' => 'search', 'name' => 'search'],
            [
                'type' => 'select',
                'name' => 'status',
                'options' => [
                    ['label' => 'Active', 'value' => '1'],
                    ['label' => 'Inactive', 'value' => '0'],
                ]
            ],
            [
                'type' => 'select',
                'name' => 'category_id',
                'label' => 'Category',
                'options' => $categoryOptions
            ],
            [
                'type' => 'select',
                'name' => 'brand_id',
                'label' => 'Brand',
                'options' => $brandOptions
            ]
        ]" />

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Discount Percent</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($products->isEmpty())
                    <tr>
                        <td class="text-center font-bold text-2xl" colspan="10">Danh sách trống</td>
                    </tr>
                @else
                    @foreach ($products as $product)
                        <tr>
                            <th>{{ $product->id }}</th>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $product->name }}</div>
                                        {{-- <div class="text-sm opacity-50">United States</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->description ?? '-' }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}$</td>
                            <td>{{ $product->discount_percent }}% => {{ number_format($product->final_price, 0, ',', '.')}}$</td>
                            <td>{{ $product->views }}</td>
                            <td>
                                <x-status-badge :status="$product->status" />
                            </td>
                            <td class="">
                                <x-action-buttons type="trash" :id="$product->id" :name="$product->name" routeName="products" />
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    @if(session('success'))
        <x-toast :messages="[session('success')]" />
    @endif
    @if(session('error'))
        <x-toast type="error" :messages="[session('error')]" />
    @endif
@endsection