<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard general.
     *
     * Redirige al usuario según su rol.
     */
    public function index()
    {
        $user = Auth::user();

        // Administrador
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Staff / Barbero
        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        // Cliente
        return redirect()->route('client.appointments.index');
    }

    /**
     * Dashboard del administrador.
     *
     * Carga las estadísticas generales de la barbería.
     */
    public function adminDashboard()
    {
        // =====================================================
        // ESTADÍSTICAS GENERALES
        // =====================================================

        $totalAppointments = Appointment::count();

        $totalClients = User::where('role', 'client')->count();

        $totalBarbers = StaffProfile::count();

        $totalServices = Service::count();


        // =====================================================
        // CITAS POR ESTADO
        // =====================================================

        $pendingAppointments = Appointment::where('status', 'pending')->count();

        $confirmedAppointments = Appointment::where('status', 'confirmed')->count();

        $completedAppointments = Appointment::where('status', 'completed')->count();

        $cancelledAppointments = Appointment::where('status', 'cancelled')->count();


        // =====================================================
        // CITAS POR FECHA
        // =====================================================

        $todayAppointments = Appointment::whereDate(
            'appointment_date',
            today()
        )->count();

        $weekAppointments = Appointment::whereBetween(
            'appointment_date',
            [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ]
        )->count();

        $monthAppointments = Appointment::whereMonth(
            'appointment_date',
            now()->month
        )
            ->whereYear(
                'appointment_date',
                now()->year
            )
            ->count();


        // =====================================================
        // CITAS POR BARBERO
        // =====================================================

        $appointmentsByBarber = StaffProfile::with('user')
            ->withCount('appointments')
            ->orderByDesc('appointments_count')
            ->get();


        // =====================================================
        // RENDIMIENTO DE CADA BARBERO
        // =====================================================

        $barberPerformance = StaffProfile::with('user')
            ->withCount([
                'appointments',

                'appointments as completed_appointments_count' => function ($query) {
                    $query->where('status', 'completed');
                },

                'appointments as cancelled_appointments_count' => function ($query) {
                    $query->where('status', 'cancelled');
                },
            ])
            ->orderByDesc('appointments_count')
            ->get();


        // =====================================================
        // ÚLTIMAS CITAS
        // =====================================================

        $latestAppointments = Appointment::with([
            'service',
            'staffProfile.user',
            'client',
        ])
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->take(10)
            ->get();


        // =====================================================
        // ENVIAR DATOS A LA VISTA
        // =====================================================

        return view('admin.dashboard', compact(
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'totalClients',
            'totalBarbers',
            'totalServices',
            'todayAppointments',
            'weekAppointments',
            'monthAppointments',
            'appointmentsByBarber',
            'barberPerformance',
            'latestAppointments'
        ));
    }
}