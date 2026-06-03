<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request) {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('fullname', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('role', 'desc')->orderBy('id', 'desc')->paginate(5);

        // Giữ query string khi phân trang
        $users->appends($request->all());

        return view('admin.pages.users.index', compact('users'));
    }

    public function trash(Request $request)
    {
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(5);

        $users->appends($request->all());

        return view('admin.pages.users.trash', compact('users'));
    }

    public function create() {
        return view("admin.pages.users.create");
    }

    public function store(UserRequest $request) {
        try {
            $avatarPath = 'default.png';
            
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('users', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'avatar' => $avatarPath,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', "User '{$user->name}' created successfully.");

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while creating the user.');
        }
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.pages.users.edit', compact('user'));
    }

    public function update(UserRequest $request, $id) {
        $user = User::findOrFail($id);

        try {
            $avatarPath = $user->avatar;
            
            if ($request->hasFile('avatar')) {
                if ($user->avatar && $user->avatar !== 'default.png') {
                    Storage::disk('public')->delete($user->avatar);
                }
                $avatarPath = $request->file('avatar')->store('users', 'public');
            }

            $user->update([
                'name' => $request->name,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'role' => $request->role,
                'avatar' => $avatarPath,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', "User '{$user->name}' updated successfully.");

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while updating the user.');
        }
    }

    public function destroy($id) {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return back()->with('success', "User '{$user->name}' deleted successfully (soft delete).");

        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while deleting the user.');
        }
    }

    public function restore(Request $request, $id) {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
            return redirect()->route('admin.users.trash')
                ->with('success', "User '{$user->name}' restored successfully.");
        }

        return redirect()->route('admin.users.trash')
            ->with('error', 'User is not deleted.');
    }

    public function forceDelete(Request $request, $id) {
        $user = User::withTrashed()->findOrFail($id);

        try {
            if ($user->avatar && $user->avatar !== 'default.png') {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->forceDelete();
            return redirect()->route('admin.users.trash')
                ->with('success', "User '{$user->name}' permanently deleted.");
        } catch (Exception $e) {
            return redirect()->route('admin.users.trash')
                ->with('error', 'Something went wrong while permanently deleting the user.');
        }
    }
}