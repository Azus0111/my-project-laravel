@extends('admin.layouts.index')
@section('title', "Add Product")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Products', 'url' => route('admin.products.index')],
            ['title' => 'Create']
        ]" />
    </div>

    <form novalidate action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Add Product</legend>

            <label class="label">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input validator w-full" required minlength="3"
                maxlength="45" placeholder="Product Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Price</label>
            <input type="number" name="price" value="{{ old('price') }}" class="input validator w-full" required min="10"
                max="100000000" placeholder="Product Price" />

            <label class="label">Description</label>
            <textarea name="description" class="textarea h-24 w-full">{{ old('description') }}</textarea>

            <label class="label">Brand</label>
            <select name="brand_id" class="select w-full">
                <option selected value="">Pick a brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>

            <label class="label">Category</label>
            <select name="category_id" class="select w-full">
                <option selected value="">Pick a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label class="label">Main Image</label>
            <input type="file" name="image" class="file-input w-full" />

            <label class="label">Additional Images</label>
            <input type="file" name="images[]" class="file-input w-full" multiple />

            <button type="submit" class="btn btn-neutral mt-4">Add</button>
        </fieldset>
    </form>
    @if($errors->any())
        <x-toast type="error" :messages="$errors->all()" />
    @endif
@endsection