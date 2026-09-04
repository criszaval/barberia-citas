<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener la lista de barberos para el filtro
        $barbers = StaffProfile::with('user')->get();

        // 2. Consulta de citas con sus relaciones
        $query = Appointment::with(['client', 'staffProfile.user', 'service']);

        // Filtro por Barbero
        if ($request->filled('staff_profile_id')) {
            $query->where('staff_profile_id', $request->staff_profile_id);
        }

        // Filtro por Fecha
        if ($request->filled('appointment_date')) {
            $query->whereDate('appointment_date', $request->appointment_date);
        }

        // Ordenar por fecha y hora
        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('start_time', 'asc')
                              ->paginate(15)
                              ->withQueryString();

        return view('admin.appointments.index', compact('appointments', 'barbers'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Bloquear modificación si la cita ya fue completada o cancelada
        if ($appointment->status === 'completed') {
            return back()->with('error', 'No se puede modificar una cita que ya ha sido completada.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'El estado de la cita se actualizó correctamente.');
    }
}