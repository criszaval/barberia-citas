<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirige al usuario según su rol tras iniciar sesión o tocar 'Dashboard'.
     */
    public function index()
    {
        $user = Auth::user();

        // Si es administrador
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Si es empleado/barbero
        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        // Si es un cliente registrado, lo enviamos directamente a su lista de citas
        return redirect()->route('client.appointments.index');
    }
}