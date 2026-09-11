<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
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

        // Verificar que el usuario tenga perfil de empleado
        if (!$user->staffProfile) {
            abort(403, 'No tienes un perfil de empleado asignado.');
        }

        // Obtener el perfil del empleado
        $staffProfile = $user->staffProfile;

        // Obtener el horario del empleado
        $schedules = Schedule::where('staff_profile_id', $staffProfile->id)
            ->orderBy('day_of_week')
            ->get();

        // Obtener las citas del empleado
        $query = Appointment::where('staff_profile_id', $staffProfile->id)
            ->with(['service', 'client']);

        // Filtro opcional por fecha
        if ($request->filled('appointment_date')) {
            $query->whereDate('appointment_date', $request->appointment_date);
        }

        // Ordenar citas por fecha y hora
        $appointments = $query
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('staff.dashboard', compact(
            'appointments',
            'schedules'
        ));
    }

    /**
     * Actualiza el estado de una cita.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Seguridad: verificar que la cita pertenezca al empleado actual o sea admin
        if (
            $user->role !== 'admin' &&
            (
                !$user->staffProfile ||
                $appointment->staff_profile_id !== $user->staffProfile->id
            )
        ) {
            abort(403, 'No tienes permiso para modificar esta cita.');
        }

        // No permitir modificar citas finalizadas o canceladas
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'No se puede modificar una cita que ya fue finalizada o cancelada.'
                );
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Estado de la cita actualizado correctamente.'
            );
    }
}