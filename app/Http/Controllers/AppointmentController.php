<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Muestra el formulario público para agendar una cita.
     */
    public function create()
    {
        $services = Service::all();
        $staffMembers = StaffProfile::with('user')->get();

        return view('appointments.create', compact('services', 'staffMembers'));
    }

    /**
     * Devuelve los días de la semana en los que trabaja un profesional.
     */
    public function availableDates(StaffProfile $staffProfile)
    {
        $workingDays = Schedule::where('staff_profile_id', $staffProfile->id)
            ->where('is_active', true)
            ->pluck('day_of_week')
            ->unique()
            ->values();

        return response()->json([
            'working_days' => $workingDays,
        ]);
    }

    /**
     * Devuelve los horarios disponibles para un profesional,
     * una fecha y un servicio determinados.
     */
    public function availableTimes(Request $request)
    {
        $request->validate([
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
        ]);

        $staffProfileId = $request->staff_profile_id;
        $service = Service::findOrFail($request->service_id);

        $date = Carbon::parse($request->appointment_date);
        $dayOfWeek = $date->dayOfWeek;

        /*
         * Obtener los horarios de trabajo del profesional
         * para ese día de la semana.
         */
        $schedules = Schedule::where('staff_profile_id', $staffProfileId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json([
                'available_times' => [],
                'message' => 'El profesional no trabaja este día.',
            ]);
        }

        /*
         * Obtener las citas existentes del profesional
         * para la fecha seleccionada.
         *
         * Las citas canceladas NO bloquean horarios.
         */
        $appointments = Appointment::where('staff_profile_id', $staffProfileId)
            ->whereDate('appointment_date', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $availableTimes = [];

        /*
         * Generamos horarios cada 30 minutos.
         */
        foreach ($schedules as $schedule) {

            $scheduleStart = Carbon::parse(
                $date->format('Y-m-d') . ' ' . $schedule->start_time
            );

            $scheduleEnd = Carbon::parse(
                $date->format('Y-m-d') . ' ' . $schedule->end_time
            );

            $currentTime = $scheduleStart->copy();

            while (
                $currentTime->copy()
                    ->addMinutes($service->duration_minutes)
                    ->lte($scheduleEnd)
            ) {

                $candidateStart = $currentTime->copy();

                $candidateEnd = $candidateStart->copy()
                    ->addMinutes($service->duration_minutes);

                /*
                 * Si la fecha seleccionada es hoy,
                 * no mostrar horarios que ya pasaron.
                 */
                if (
                    $date->isToday() &&
                    $candidateStart->lte(now())
                ) {
                    $currentTime->addMinutes(30);
                    continue;
                }

                /*
                 * Verificar si el horario se cruza
                 * con alguna cita existente.
                 */
                $hasOverlap = $appointments->contains(function ($appointment) use (
                    $candidateStart,
                    $candidateEnd,
                    $date
                ) {

                    $appointmentStart = Carbon::parse(
                        $date->format('Y-m-d') . ' ' . $appointment->start_time
                    );

                    $appointmentEnd = Carbon::parse(
                        $date->format('Y-m-d') . ' ' . $appointment->end_time
                    );

                    return $candidateStart->lt($appointmentEnd)
                        && $candidateEnd->gt($appointmentStart);
                });

                if (!$hasOverlap) {
                    $availableTimes[] = $candidateStart->format('H:i');
                }

                $currentTime->addMinutes(30);
            }
        }

        return response()->json([
            'available_times' => array_values(array_unique($availableTimes)),
        ]);
    }

    /**
     * Almacena una nueva cita verificando:
     * - horario laboral
     * - duración del servicio
     * - citas existentes
     * - cruces de horario
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'guest_name'       => 'required|string|max:255',
            'guest_email'      => 'required|email|max:255',
            'guest_phone'      => 'required|string|max:20',
        ]);

        $service = Service::findOrFail($request->service_id);

        $date = Carbon::parse($request->appointment_date);
        $dayOfWeek = $date->dayOfWeek;

        /*
         * Verificar que el profesional trabaje ese día.
         */
        $schedules = Schedule::where(
            'staff_profile_id',
            $request->staff_profile_id
        )
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        if ($schedules->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'appointment_date' =>
                        'El profesional seleccionado no trabaja ese día.'
                ]);
        }

        /*
         * Calcular inicio y final de la cita.
         */
        $startTime = Carbon::parse(
            $date->format('Y-m-d') . ' ' . $request->start_time
        );

        $endTime = $startTime->copy()
            ->addMinutes($service->duration_minutes);

        /*
         * Verificar que la cita esté completamente
         * dentro de uno de los horarios laborales.
         */
        $insideSchedule = $schedules->contains(function ($schedule) use (
            $date,
            $startTime,
            $endTime
        ) {

            $scheduleStart = Carbon::parse(
                $date->format('Y-m-d') . ' ' . $schedule->start_time
            );

            $scheduleEnd = Carbon::parse(
                $date->format('Y-m-d') . ' ' . $schedule->end_time
            );

            return $startTime->gte($scheduleStart)
                && $endTime->lte($scheduleEnd);
        });

        if (!$insideSchedule) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'start_time' =>
                        'El horario seleccionado está fuera del horario laboral del profesional.'
                ]);
        }

        /*
         * No permitir reservar una hora que ya pasó.
         */
        if ($date->isToday() && $startTime->lte(now())) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'start_time' =>
                        'No puede seleccionar una hora que ya pasó.'
                ]);
        }

        $startTimeStr = $startTime->format('H:i:s');
        $endTimeStr = $endTime->format('H:i:s');

        /*
         * Verificar nuevamente si existe una cita
         * que choque con el nuevo horario.
         */
        $hasOverlap = Appointment::where(
            'staff_profile_id',
            $request->staff_profile_id
        )
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startTimeStr, $endTimeStr) {
                $query->where('start_time', '<', $endTimeStr)
                    ->where('end_time', '>', $startTimeStr);
            })
            ->exists();

        if ($hasOverlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'start_time' =>
                        'El horario seleccionado ya no está disponible con este profesional. Por favor seleccione otro horario.'
                ]);
        }

        /*
         * Generar un token único para que el usuario
         * pueda ver su cita sin iniciar sesión.
         */
        $token = Str::uuid();

        /*
         * Crear la reserva.
         */
        $appointment = Appointment::create([
            'client_id'        => Auth::check() ? Auth::id() : null,
            'staff_profile_id' => $request->staff_profile_id,
            'service_id'       => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'start_time'       => $startTimeStr,
            'end_time'         => $endTimeStr,
            'status'           => 'pending',
            'guest_name'       => $request->guest_name,
            'guest_email'      => $request->guest_email,
            'guest_phone'      => $request->guest_phone,
            'token'            => $token,
            'notes'            => $request->notes,
        ]);

        return redirect()
            ->route('appointments.show', ['token' => $token])
            ->with('success', '¡Tu cita ha sido reservada con éxito!');
    }

    /**
     * Muestra los detalles de la cita mediante el token único
     * sin exigir autenticación.
     */
    public function show($token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Devuelve el estado actual de la cita en formato JSON
     * para actualización en tiempo real.
     */
    public function jsonStatus($token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();

        return response()->json([
            'status' => $appointment->status
        ]);
    }

    /**
     * Muestra el formulario para editar la cita por token
     * (solo si está pendiente).
     */
    public function editByToken($token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();

        if ($appointment->status !== 'pending') {
            return redirect()
                ->route('appointments.show', $token)
                ->with(
                    'error',
                    'Esta cita ya no se puede modificar porque su estado ha cambiado.'
                );
        }

        $services = Service::all();
        $staffMembers = StaffProfile::with('user')->get();

        return view(
            'appointments.edit',
            compact('appointment', 'services', 'staffMembers')
        );
    }

    /**
     * Actualiza la cita mediante el token
     * (solo si está pendiente).
     */
    public function updateByToken(Request $request, $token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();

        if ($appointment->status !== 'pending') {
            return redirect()
                ->route('appointments.show', $token)
                ->with(
                    'error',
                    'Esta cita ya no se puede modificar porque su estado ha cambiado.'
                );
        }

        $request->validate([
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
        ]);

        $service = Service::findOrFail($request->service_id);

        $startTime = Carbon::parse($request->start_time);

        $endTime = $startTime->copy()
            ->addMinutes($service->duration_minutes);

        $startTimeStr = $startTime->format('H:i:s');
        $endTimeStr = $endTime->format('H:i:s');

        /*
         * Validar cruces excluyendo la cita actual.
         */
        $hasOverlap = Appointment::where(
            'staff_profile_id',
            $request->staff_profile_id
        )
            ->where('appointment_date', $request->appointment_date)
            ->where('id', '!=', $appointment->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startTimeStr, $endTimeStr) {
                $query->where('start_time', '<', $endTimeStr)
                    ->where('end_time', '>', $startTimeStr);
            })
            ->exists();

        if ($hasOverlap) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'start_time' =>
                        'El horario seleccionado ya no está disponible con este barbero.'
                ]);
        }

        $appointment->update([
            'staff_profile_id' => $request->staff_profile_id,
            'service_id'       => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'start_time'       => $startTimeStr,
            'end_time'         => $endTimeStr,
        ]);

        return redirect()
            ->route('appointments.show', $token)
            ->with('success', '¡Tu cita ha sido actualizada con éxito!');
    }

    /**
     * Permite cancelar la cita mediante el token
     * (si está pendiente o confirmada).
     */
    public function cancelByToken($token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()
                ->with(
                    'error',
                    'Esta cita ya no se puede cancelar.'
                );
        }

        $appointment->update([
            'status' => 'cancelled'
        ]);

        return back()
            ->with(
                'success',
                'Tu cita ha sido cancelada exitosamente.'
            );
    }
}