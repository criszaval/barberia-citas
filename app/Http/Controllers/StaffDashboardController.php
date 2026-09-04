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
    public function index(Request $request)
    {
        $user = Auth::user();

        // Verificar que el usuario tenga perfil de empleado activo
        if (!$user->staffProfile) {
            abort(403, 'No tienes un perfil de empleado asignado.');
        }

        // Obtener la consulta base de las citas de este barbero/empleado
        $query = Appointment::where('staff_profile_id', $user->staffProfile->id)
            ->with(['service', 'client']);

        // Filtro opcional por fecha si el barbero lo selecciona
        if ($request->filled('appointment_date')) {
            $query->whereDate('appointment_date', $request->appointment_date);
        }

        // Ordenar citas por fecha y hora de inicio
        $appointments = $query->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('staff.dashboard', compact('appointments'));
    }

    /**
     * Actualiza el estado de una cita (pending, confirmed, completed, cancelled).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Seguridad: Verificar que la cita pertenezca al empleado actual o sea admin
        if ($appointment->staff_profile_id !== $user->staffProfile->id && $user->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar esta cita.');
        }

        // RESTRICCIÓN DE SEGURIDAD:
        // Impedir que se modifique una cita que ya ha sido completada o cancelada
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'No se puede modificar una cita que ya fue finalizada o cancelada.');
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