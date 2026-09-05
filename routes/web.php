<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientAppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('appointments.create');
});

// Rutas públicas para la reserva de citas
Route::get('/reservar', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/reservar', [AppointmentController::class, 'store'])->name('appointments.store');

// Redirección centralizada tras login según el rol
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas protegidas exclusivamente para Personal / Empleados (Staff)
Route::middleware(['auth', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/appointments/{appointment}/status', [StaffDashboardController::class, 'updateStatus'])->name('appointments.updateStatus');
});

// Rutas protegidas exclusivamente para Administradores (Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión global de citas para el Admin
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

    // Listado de Clientes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

    // CRUD de Servicios 
    Route::resource('services', ServiceController::class);

    // CRUD de Barberos / Personal
    Route::resource('staff', StaffController::class);
});

// Rutas protegidas para clientes autenticados y perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/mis-citas', [ClientAppointmentController::class, 'index'])->name('client.appointments.index');
    Route::get('/mis-citas/{appointment}/editar', [ClientAppointmentController::class, 'edit'])->name('client.appointments.edit');
    Route::put('/mis-citas/{appointment}', [ClientAppointmentController::class, 'update'])->name('client.appointments.update');
    
    // Ruta de cancelación para el cliente
    Route::patch('/mis-citas/{appointment}/cancelar', [ClientAppointmentController::class, 'cancel'])->name('client.appointments.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';