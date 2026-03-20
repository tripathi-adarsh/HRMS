<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class RoleMiddleware {
    public function handle(Request $request, Closure $next, ...$roles) {
        if (!auth()->check()) return redirect()->route('login');
        $userRole = auth()->user()->role;
        foreach ($roles as $role) {
            $allowed = explode('|', $role);
            if (in_array($userRole, $allowed)) return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}