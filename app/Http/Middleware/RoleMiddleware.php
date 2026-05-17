<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response 
    {
        // 1. Pastikan user sudah login
        if (!$request->user()) {
            return redirect('login');
        }

        // 2. Antisipasi jika $roles dikirim sebagai string (pustakawan,admin)
        // Beberapa versi Laravel mengirimkan parameter middleware sebagai string tunggal
        if (count($roles) === 1 && str_contains($roles[0], ',')) {
            $roles = explode(',', $roles[0]);
        }

        // 3. Cek apakah role user ada di dalam daftar yang diizinkan
        if (!in_array($request->user()->role, $roles)) {
            return redirect('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
