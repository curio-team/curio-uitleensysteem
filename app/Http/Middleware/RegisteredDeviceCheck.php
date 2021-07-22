<?php

namespace App\Http\Middleware;

use App\Models\RegisteredDevice;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class RegisteredDeviceCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->cookie('registered_device')){
            $request->session()->flash('error', 'Deze device is nog niet geregistreerd, of de registratie is verlopen (30 dagen). Log in het adminpaneel om deze device te authenticeren.');
            return redirect()->route('searchProducts');
        }

        return $next($request);
    }
}
