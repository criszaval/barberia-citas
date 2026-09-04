<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('client_id', Auth::id())
            ->with(['service', 'staffProfile.user'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('client.appointments', compact('appointments'));
    }

    public function edit(Appointment $appointment)
    {
        // Validar pertenencia y que solo esté en estado pendiente
        if ($appointment->client_id !== Auth::id() || $appointment->status !== 'pending') {
            return redirect()->route('client.appointments.index')
                ->with('error', 'Solo puedes editar citas pendientes.');
        }

        return view('client.edit', compact('appointment'));
    }

   public function update(Request $request, Appointment $appointment)
    {
        // 1. Validar únicamente la fecha y hora de inicio recibidas del formulario
        $request->validate([
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
        ]);

        // 2. Obtener la duración del servicio (en minutos)
        $durationInMinutes = $appointment->service->duration_minutes ?? 30; // 30 minutos por defecto

        // 3. Calcular el end_time sumando la duración a la hora de inicio
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = $startTime->copy()->addMinutes($durationInMinutes)->format('H:i:s');

        // 4. Actualizar la cita
        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'status' => 'pending', // opcional: reiniciar estado si requiere re-confirmación
        ]);

        return redirect()->route('client.appointments.index')
            ->with('success', 'Tu cita ha sido reagendada con éxito.');
    }
    public function cancel(Appointment $appointment)
{
    // Validar que la cita sea del usuario y que esté en estado pendiente o confirmada
    if ($appointment->client_id !== Auth::id() || !in_array($appointment->status, ['pending', 'confirmed'])) {
        return redirect()->route('client.appointments.index')
            ->with('error', 'No puedes cancelar esta cita.');
    }

    $appointment->update([
        'status' => 'cancelled',
    ]);

    return redirect()->route('client.appointments.index')
        ->with('success', 'La cita ha sido cancelada exitosamente.');
}
}