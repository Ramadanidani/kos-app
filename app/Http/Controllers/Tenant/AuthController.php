<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('tenant.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        $tenant = Tenant::where('phone', $request->phone)
            ->where('status', 'active')
            ->first();

        if (!$tenant || !Hash::check($request->password, $tenant->password)) {
            return back()->withErrors([
                'phone' => 'Nomor HP atau password salah.',
            ])->withInput();
        }

        Auth::guard('tenant')->login($tenant, $request->boolean('remember'));

        return redirect()->route('tenant.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenant.login');
    }

    public function showChangePassword()
    {
        return view('tenant.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $tenant = Auth::guard('tenant')->user();

        $tenant->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Password berhasil diperbarui!');
    }
}