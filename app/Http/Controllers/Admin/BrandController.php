<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request) {
        $query = Brand::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $brands = $query->orderBy('id', 'desc')->paginate(5);

        // Giữ query string khi phân trang
        $brands->appends($request->all());

        return view('admin.pages.brands.index', compact('brands'));
    }

    public function trash(Request $request) {
        $query = Brand::onlyTrashed();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $brands = $query->orderBy('id', 'desc')->paginate(5);

        $brands->appends($request->all());

        return view('admin.pages.brands.trash', compact('brands'));
    }

    public function create() {
        return view("admin.pages.brands.create");
    }

    public function store(BrandRequest $request) {
        try {
            $imagePath = 'default.png';
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('brands', 'public');
            }

            $brand = Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brand->name}' created successfully.");

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while creating the brand.');
        }
    }

    public function edit($id) {
        $brand = Brand::findOrFail($id);
        return view('admin.pages.brands.edit', compact('brand'));
    }

    public function update(BrandRequest $request, $id) {
        $brand = Brand::findOrFail($id);

        try {
            $imagePath = $brand->image ?? 'default.png'; // giữ ảnh cũ làm mặc định

            if ($request->hasFile('image')) {
                if ($brand->image && $brand->image !== 'default.png') {
                    Storage::disk('public')->delete($brand->image);
                }

                $imagePath = $request->file('image')->store('brands', 'public');
            }

            $brand->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status,
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brand->name}' updated successfully.");

        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Something went wrong while updating the brand.');
        }
    }
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        try {
            $brand->delete();
            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brand->name}' deleted successfully (soft delete).");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while deleting the brand.');
        }
    }

    public function forceDelete($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);

        try {
            if ($brand->image && $brand->image !== 'default.png') {
                Storage::disk('public')->delete($brand->image);
            }

            $brand->forceDelete();
            return back()->with('success', "Brand '{$brand->name}' permanently deleted.");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while permanently deleting the brand.');
        }
    }

    public function restore($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);

        if ($brand->trashed()) {
            $brand->restore();
            return back()->with('success', "Brand '{$brand->name}' restored successfully.");
        }

        return back()->with('error', 'Brand is not deleted.');
    }
}
