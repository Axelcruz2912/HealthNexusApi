<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class CheckFinancePin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('finance_verified')) {
            return redirect()->route('superadmin.finanzas.auth');
        }
        return $next($request);
    }
}
