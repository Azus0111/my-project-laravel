@extends('admin.layouts.index')
@section('title', "Edit Product")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Products', 'url' => route('admin.products.index')],
            ['title' => 'Edit']
        ]" />
    </div>

    <form novalidate action="{{ route('admin.products.update', $product->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Edit Product</legend>

            <label class="label">Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="input validator w-full" required
                minlength="3" maxlength="45" placeholder="Product Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Price</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="input validator w-full"
                required min="10" max="100000000" placeholder="Product Price" />

            <label class="label">Discount Percent</label>
            <input type="number" name="discount_percent" value="{{ old('discount_percent', $product->discount_percent) }}"
                class="input validator w-full" required min="0" max="100" placeholder="Product Price" />

            <label class="label">Description</label>
            <textarea name="description"
                class="textarea h-24 w-full">{{ old('description', $product->description) }}</textarea>

            <label class="label">Brand</label>
            <select name="brand_id" class="select w-full">
                <option selected value="">Pick a brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>

            <label class="label">Category</label>
            <select name="category_id" class="select w-full">
                <option selected value="">Pick a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label class="label">Status</label>
            <select name="status" class="select w-full">
                <option disabled selected>Select Status</option>
                <option {{ ($product->status == 1) ? 'selected' : '' }} value="1">Active</option>
                <option {{ ($product->status == 0) ? 'selected' : '' }} value="0">Inactive</option>
            </select>

            <label class="label">Image</label>
            <input type="file" name="image" class="file-input w-full" />

            <button type="submit" class="btn btn-neutral mt-4">Add</button>
        </fieldset>
    </form>
    @if($errors->any())
        <x-toast type="error" :messages="$errors->all()" />
    @endif
@endsection