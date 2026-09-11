<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Mostrar el horario del empleado autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        // Verificar que tenga perfil de empleado
        if (!$user->staffProfile) {
            abort(403, 'No tienes un perfil de empleado asignado.');
        }

        $schedules = Schedule::where(
            'staff_profile_id',
            $user->staffProfile->id
        )
        ->orderBy('day_of_week')
        ->get();

        return view('staff.schedule.index', compact('schedules'));
    }


    /**
     * Guardar o actualizar el horario semanal.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Verificar que tenga perfil de empleado
        if (!$user->staffProfile) {
            abort(403, 'No tienes un perfil de empleado asignado.');
        }

        $staffProfileId = $user->staffProfile->id;

        // Validar los datos recibidos
        $validated = $request->validate([
            'schedule' => 'required|array',

            'schedule.*.is_active' => 'required|boolean',

            'schedule.*.start_time' => 'nullable|date_format:H:i',

            'schedule.*.end_time' => 'nullable|date_format:H:i',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Guardar cada día
        |--------------------------------------------------------------------------
        */

        foreach ($validated['schedule'] as $dayOfWeek => $data) {

            $isActive = (bool) $data['is_active'];

            Schedule::updateOrCreate(
                [
                    'staff_profile_id' => $staffProfileId,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'is_active' => $isActive,

                    'start_time' => $isActive
                        ? ($data['start_time'] ?? '08:00')
                        : null,

                    'end_time' => $isActive
                        ? ($data['end_time'] ?? '17:00')
                        : null,
                ]
            );
        }

        return redirect()
            ->route('staff.schedule.index')
            ->with('success', 'Horario actualizado correctamente.');
    }
}

