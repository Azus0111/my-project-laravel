@extends('admin.layouts.index')
@section('title', "Edit Brand")

@section('content')
    <div class="mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Brands', 'url' => route('admin.brands.index')],
            ['title' => 'Edit']
        ]" />
    </div>

    <form novalidate action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-sm border p-4 mx-auto">
            <legend class="fieldset-legend">Edit Brand</legend>

            <label class="label">Name</label>
            <input type="text" name="name" value="{{ old('name', $brand->name) }}" class="input validator w-full" required
                minlength="3" maxlength="45" placeholder="Brand Name" />
            {{-- <p class="validator-hint"></p> --}}

            <label class="label">Status</label>
            <select name="status" class="select w-full">
                <option disabled selected>Select Status</option>
                <option {{ ($brand->status == 1) ? 'selected' : '' }} value="1">Active</option>
                <option {{ ($brand->status == 0) ? 'selected' : '' }} value="0">Inactive</option>
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