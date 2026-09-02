<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Almacena una nueva cita verificando disponibilidades y cruces de horario.
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

        // 1. Obtener la duración del servicio seleccionado
        $service = Service::findOrFail($request->service_id);
        
        // 2. Parsear la hora de forma flexible con Carbon (admite H:i o H:i:s)
        $startTime = Carbon::parse($request->start_time);
        $endTime   = (clone $startTime)->addMinutes($service->duration_minutes);

        $startTimeStr = $startTime->format('H:i:s');
        $endTimeStr   = $endTime->format('H:i:s');

        // 3. Validar si ya existe una cita confirmada o pendiente que choque en ese rango para el mismo empleado
        $hasOverlap = Appointment::where('staff_profile_id', $request->staff_profile_id)
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
                ->withErrors(['start_time' => 'El horario seleccionado ya no está disponible con este barbero/empleado. Por favor elige otro horario.']);
        }

        // 4. Crear la reserva
        Appointment::create([
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
        ]);

        return redirect()->route('appointments.create')
            ->with('success', '¡Tu cita ha sido reservada con éxito! Nos pondremos en contacto para confirmar.');
    }
}