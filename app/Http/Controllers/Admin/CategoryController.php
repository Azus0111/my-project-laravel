<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('id', 'desc')->paginate(5);

        // Giữ query string khi phân trang
        $categories->appends($request->all());

        return view('admin.pages.categories.index', compact('categories'));
    }

    public function trash(Request $request) {
        $query = Category::onlyTrashed();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('id', 'desc')->paginate(5);

        $categories->appends($request->all());

        return view('admin.pages.categories.trash', compact('categories'));
    }

    public function create() {
        return view("admin.pages.categories.create");
    }

    public function store(CategoryRequest $request) {
        try {
            $imagePath = 'default.png';
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
            }

            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', "Category '{$category->name}' created successfully.");

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while creating the category.');
        }
    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.pages.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id) {
        $category = Category::findOrFail($id);

        try {
            $imagePath = $category->image ?? 'default.png'; // giữ ảnh cũ làm mặc định

            if ($request->hasFile('image')) {
                if ($category->image && $category->image !== 'default.png') {
                    Storage::disk('public')->delete($category->image);
                }

                $imagePath = $request->file('image')->store('categories', 'public');
            }

            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status,
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', "category '{$category->name}' updated successfully.");

        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Something went wrong while updating the category.');
        }
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        try {
            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', "category '{$category->name}' deleted successfully (soft delete).");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while deleting the category.');
        }
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        try {
            if ($category->image && $category->image !== 'default.png') {
                Storage::disk('public')->delete($category->image);
            }

            $category->forceDelete();
            return back()->with('success', "category '{$category->name}' permanently deleted.");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while permanently deleting the category.');
        }
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        if ($category->trashed()) {
            $category->restore();
            return back()->with('success', "category '{$category->name}' restored successfully.");
        }

        return back()->with('error', 'category is not deleted.');
    }
}
