<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        // Filtramos a los usuarios que sean clientes (ajusta la condición según cómo manejes los roles en tu BD)
        // Por ejemplo, si tienes una columna 'role' o buscas los que tengan citas:
        $clients = User::where('role', 'client') // o with('clientAppointments')
            ->withCount('clientAppointments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.clients.index', compact('clients'));
    }
}