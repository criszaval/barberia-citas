<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
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

    // CRUD de Servicios (hereda prefix 'admin' y name 'admin.')
    Route::resource('services', ServiceController::class);

    // CRUD de Barberos / Personal
    Route::resource('staff', StaffController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';