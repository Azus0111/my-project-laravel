<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(Request $request) {
        if (Auth::check()) {
            return redirect()->route("admin.dashboard");
        }
        return view('admin.pages.login');
    }

    public function login(AuthRequest $request) {
        $user = User::where('name', $request->email)
        ->orWhere('email', $request->email)
        ->withTrashed()
        ->first();

        if (!$user) {
            return back()->withInput()->with('error', 'Account does not exist');
        }

        $checkPassword = password_verify($request->password, $user->password);

        if (!$checkPassword) {
            return back()->withInput()->with('error', 'Incorrect password');
        }

        $remember = $request->has('remember') ? true : false;

        Auth::login($user, $remember);

        return redirect()->intended(route('admin.dashboard'))
        ->with('success', 'Login successful');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Logout successful');
    }
}
