<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AppointmentController extends Controller
{
    /**
     * Muestra la vista principal para agendar una cita.
     */
    public function create()
    {
        $services = Service::where('is_active', true)->get();
        $staffMembers = StaffProfile::where('is_active', true)->with('user', 'services')->get();

        return view('appointments.create', compact('services', 'staffMembers'));
    }

    /**
     * Procesa y guarda la reserva de la cita.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos ingresados en el formulario
        $request->validate([
            'service_id'       => 'required|exists:services,id',
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:20',
            'create_account'   => 'nullable|boolean',
            'password'         => 'required_if:create_account,1|nullable|min:8',
        ]);

        $service = Service::findOrFail($request->service_id);

        // 2. Calcular hora de inicio y fin según la duración del servicio
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime   = (clone $startTime)->addMinutes($service->duration_minutes);

        $clientId = null;

        // 3. Manejar la identidad del cliente (Logueado vs Nuevo Usuario vs Invitado)
        if (Auth::check()) {
            // Usuario con sesión activa
            $clientId = Auth::id();
        } elseif ($request->boolean('create_account')) {
            // Invitado que activó "Crear una cuenta"
            
            // Validar que el correo no esté registrado previamente
            $request->validate([
                'email' => 'unique:users,email',
            ], [
                'email.unique' => 'Este correo ya está registrado. Por favor inicia sesión.',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'client',
            ]);

            // Iniciar sesión automáticamente al nuevo usuario
            Auth::login($user);
            $clientId = $user->id;
        }

        // 4. Guardar la cita en la base de datos
        $appointment = Appointment::create([
            'service_id'       => $request->service_id,
            'staff_profile_id' => $request->staff_profile_id,
            'client_id'        => $clientId,
            'guest_name'       => $request->name,
            'guest_email'      => $request->email,
            'guest_phone'      => $request->phone,
            'appointment_date' => $request->appointment_date,
            'start_time'       => $startTime->format('H:i:s'),
            'end_time'         => $endTime->format('H:i:s'),
            'status'           => 'pending',
            'notes'            => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', '¡Cita agendada exitosamente!');
    }
}