<?php

namespace App\Http\Controllers;

use App\Models\RegisteredDevice;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function registerDevice(Request $request)
    {
        $macAddr = substr(exec('getmac'), 0, 17);
        $device = RegisteredDevice::where('mac_address', $macAddr)->first();

        if(!$device){
            $newDevice = new RegisteredDevice;
            $newDevice->mac_address = $macAddr;
            $newDevice->expires_at = now()->addDays(30);
            $newDevice->save();
        }

        $request->session()->flash('success', 'Deze device is nu geregistreerd. De registratie verloopt op: '.now()->addDays(30)->format('d F Y').'.');
        return redirect()->route('manageProducts');
    }
}
