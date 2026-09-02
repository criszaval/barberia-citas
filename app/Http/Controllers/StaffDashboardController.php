<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    /**
     * Muestra la agenda de citas del empleado autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        // Verificar que el usuario tenga perfil de empleado activo
        if (!$user->staffProfile) {
            abort(403, 'No tienes un perfil de empleado asignado.');
        }

        // Obtener solo las citas correspondientes a este barbero/empleado
        $appointments = Appointment::where('staff_profile_id', $user->staffProfile->id)
            ->with('service')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('staff.dashboard', compact('appointments'));
    }

    /**
     * Actualiza el estado de una cita (pending, confirmed, completed, cancelled).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Seguridad: Verificar que la cita pertenezca al empleado actual
        if ($appointment->staff_profile_id !== $user->staffProfile->id && $user->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar esta cita.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Estado de la cita actualizado correctamente.');
    }
}