<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::with(['brand', 'category']);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('id', 'desc')->paginate(5)->appends($request->all());

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $categoryOptions = $categories->map(fn($c) => [
            'label' => $c->name,
            'value' => $c->id
        ]);

        $brandOptions = $brands->map(fn($b) => [
            'label' => $b->name,
            'value' => $b->id
        ]);

        return view('admin.pages.products.index', compact('products', 'brands', 'brandOptions', 'categories', 'categoryOptions'));
    }

    public function trash(Request $request)
    {
        $query = Product::with(['brand', 'category'])->onlyTrashed();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('id', 'desc')->paginate(5);
        $products->appends($request->all());

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $categoryOptions = $categories->map(fn($c) => [
            'label' => $c->name,
            'value' => $c->id
        ]);

        $brandOptions = $brands->map(fn($b) => [
            'label' => $b->name,
            'value' => $b->id
        ]);

        return view('admin.pages.products.trash', compact('products', 'brands', 'brandOptions', 'categoryOptions', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // Truyền vào view
        return view('admin.pages.products.create', compact('categories', 'brands'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $imagePath = 'default.png';
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'discount_percent' => $request->discount_percent ?? 0,
                'brand_id' => $request->brand_id ?: null,
                'category_id' => $request->category_id ?: null,
                'description' => $request->description,
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$product->name}' created successfully.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while creating the product.');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.pages.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        try {
            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                if ($product->image && $product->image !== 'default.png') {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'discount_percent' => $request->discount_percent ?? 0,
                'brand_id' => $request->brand_id ?: null,
                'category_id' => $request->category_id ?: null,
                'status' => $request->status,
                'description' => $request->description,
                'image' => $imagePath,
            ]);

            return redirect()
                ->route('admin.products.index')
                ->with('success', "Product '{$product->name}' updated successfully.");
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating product.');
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            $product->delete();
            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$product->name}' deleted successfully (soft delete).");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while deleting the product.');
        }
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        try {
            if ($product->image && $product->image !== 'default.png') {
                Storage::disk('public')->delete($product->image);
            }

            $product->forceDelete();
            return back()->with('success', "Product '{$product->name}' permanently deleted.");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while permanently deleting the product.');
        }
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->trashed()) {
            $product->restore();
            return back()->with('success', "Product '{$product->name}' restored successfully.");
        }

        return back()->with('error', 'product is not deleted.');
    }
}
