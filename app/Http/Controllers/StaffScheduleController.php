<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffScheduleController extends Controller
{
    /**
     * Mostrar el horario del barbero.
     */
    public function index()
    {
        $staffProfile = Auth::user()->staffProfile;

        if (!$staffProfile) {
            abort(403, 'El usuario no tiene un perfil de barbero.');
        }

        $schedules = Schedule::where('staff_profile_id', $staffProfile->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('staff.schedule', compact('schedules'));
    }


    /**
     * Guardar o actualizar el horario.
     */
    public function update(Request $request)
    {
        $staffProfile = Auth::user()->staffProfile;

        if (!$staffProfile) {
            abort(403, 'El usuario no tiene un perfil de barbero.');
        }

        $validated = $request->validate([
            'schedule' => 'required|array',

            'schedule.*.day_of_week' => 'required|integer|min:0|max:6',

            'schedule.*.is_active' => 'nullable|boolean',

            'schedule.*.start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.end_time' => [
                'nullable',
                'date_format:H:i',
            ],
        ]);


        foreach ($validated['schedule'] as $day) {

            $dayOfWeek = (int) $day['day_of_week'];

            $isActive = isset($day['is_active']) && $day['is_active'] == 1;

            /*
            |--------------------------------------------------------------------------
            | Si el barbero NO trabaja ese día
            |--------------------------------------------------------------------------
            */

            if (!$isActive) {

                Schedule::updateOrCreate(
                    [
                        'staff_profile_id' => $staffProfile->id,
                        'day_of_week' => $dayOfWeek,
                    ],
                    [
                        'start_time' => '08:00',
                        'end_time' => '17:00',
                        'is_active' => false,
                    ]
                );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Validar que tenga horario
            |--------------------------------------------------------------------------
            */

            if (empty($day['start_time']) || empty($day['end_time'])) {

                return back()
                    ->withInput()
                    ->withErrors([
                        "schedule.$dayOfWeek.start_time" =>
                            'Debe indicar la hora de entrada y salida.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validar que salida sea después de entrada
            |--------------------------------------------------------------------------
            */

            if ($day['start_time'] >= $day['end_time']) {

                return back()
                    ->withInput()
                    ->withErrors([
                        "schedule.$dayOfWeek.end_time" =>
                            'La hora de salida debe ser posterior a la hora de entrada.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Guardar horario
            |--------------------------------------------------------------------------
            */

            Schedule::updateOrCreate(
                [
                    'staff_profile_id' => $staffProfile->id,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'start_time' => $day['start_time'],
                    'end_time' => $day['end_time'],
                    'is_active' => true,
                ]
            );
        }


        return redirect()
            ->route('staff.schedule.index')
            ->with('success', 'Su horario se actualizó correctamente.');
    }
}