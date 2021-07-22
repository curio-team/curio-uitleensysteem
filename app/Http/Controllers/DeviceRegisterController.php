<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class DeviceRegisterController extends Controller
{
    public function registerDevice(Request $request)
    {
        if (!$request->cookie('registered_device')){
            Cookie::queue('registered_device', true, 1440);
            $request->session()->flash('success', 'Deze device is nu geregistreerd tot '. now()->addDays(1)->format('d F Y'));
        }

        return redirect()->route('manageProducts');
    }

    public function longRegisterDevice(Request $request)
    {
        if (!Auth::user()->super_admin) {
            $request->session()->flash('error', 'Gebruiker is geen super admin.');

            return redirect()->back();
        }

        Cookie::queue('registered_device', true, 525600);
        $request->session()->flash('success', 'Deze device is nu geregistreerd tot '. now()->addYears(1)->format('d F Y'));

        return redirect()->back();
    }
}
