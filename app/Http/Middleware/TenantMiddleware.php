<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Paksa ganti password jika masih default
        $tenant = Auth::guard('tenant')->user();
        if ($tenant->must_change_password &&
            !$request->routeIs('tenant.password.change') &&
            !$request->routeIs('tenant.password.update')) {
            return redirect()->route('tenant.password.change')
                ->with('warning', 'Silakan ganti password Anda terlebih dahulu.');
        }

        return $next($request);
    }
}