<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses Ditolak: Halaman ini hanya dapat diakses oleh Administrator Diskominfo.');
        }

        return $next($request);
    }
}
