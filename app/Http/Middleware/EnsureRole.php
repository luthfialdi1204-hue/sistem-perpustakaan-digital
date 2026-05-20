<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login.form');
        }

        if ($user->role !== $role) {
            return redirect($user->isAdmin() ? '/Dashboard_Admin' : '/Beranda_Mahasiswa');
        }

        return $next($request);
    }
}
