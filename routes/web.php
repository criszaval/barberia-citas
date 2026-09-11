<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientAppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffScheduleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('appointments.create');
});


// ============================================================
// RUTAS PÚBLICAS PARA RESERVA DE CITAS
// ============================================================

Route::get('/reservar', [AppointmentController::class, 'create'])
    ->name('appointments.create');

Route::post('/reservar', [AppointmentController::class, 'store'])
    ->name('appointments.store');


// ============================================================
// CONSULTA Y GESTIÓN DE CITAS POR TOKEN
// ============================================================

Route::get('/citas/consultar/{token}', [AppointmentController::class, 'show'])
    ->name('appointments.show');

Route::get('/citas/consultar/{token}/json', [AppointmentController::class, 'jsonStatus'])
    ->name('appointments.json');

Route::get('/citas/consultar/{token}/editar', [AppointmentController::class, 'editByToken'])
    ->name('appointments.edit-by-token');

Route::put('/citas/consultar/{token}', [AppointmentController::class, 'updateByToken'])
    ->name('appointments.update-by-token');

Route::patch('/citas/consultar/{token}/cancelar', [AppointmentController::class, 'cancelByToken'])
    ->name('appointments.cancel-by-token');


// ============================================================
// DISPONIBILIDAD DE PROFESIONALES
// ============================================================

Route::get('/reservar/disponibilidad/{staffProfile}', [AppointmentController::class, 'availableDates'])
    ->name('appointments.availableDates');

Route::get('/reservar/horarios-disponibles', [AppointmentController::class, 'availableTimes'])
    ->name('appointments.availableTimes');


// ============================================================
// DASHBOARD GENERAL
// ============================================================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// ============================================================
// RUTAS PARA STAFF / BARBEROS
// ============================================================

Route::middleware(['auth', 'role:staff,admin'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        // Dashboard del barbero
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('dashboard');

        // ====================================================
        // LISTADO DE CLIENTES
        // ====================================================

        Route::get('/clients', [ClientController::class, 'index'])
            ->name('clients.index');

        // ====================================================
        // CRUD DE SERVICIOS
        // ====================================================

        Route::resource('services', ServiceController::class);

        // ====================================================
        // ACTUALIZAR ESTADO DE UNA CITA
        // ====================================================

        Route::patch('/appointments/{appointment}/status', [StaffDashboardController::class, 'updateStatus'])
            ->name('appointments.updateStatus');

        // ====================================================
        // HORARIO PERSONAL DEL BARBERO
        // ====================================================

        // Mostrar horario
        Route::get('/schedule', [StaffScheduleController::class, 'index'])
            ->name('schedule.index');

        // Guardar / actualizar horario
        Route::put('/schedule', [StaffScheduleController::class, 'update'])
            ->name('schedule.update');
    });


// ============================================================
// RUTAS PARA ADMINISTRADORES
// ============================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard del administrador
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // ====================================================
        // GESTIÓN GLOBAL DE CITAS
        // ====================================================

        Route::get('/appointments', [AdminAppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])
            ->name('appointments.updateStatus');

        // ====================================================
        // CRUD DE SERVICIOS
        // ====================================================

        Route::resource('services', ServiceController::class);

        // ====================================================
        // CRUD DE BARBEROS / PERSONAL
        // ====================================================

        Route::resource('staff', StaffController::class);
    });


// ============================================================
// PERFIL Y CITAS DEL USUARIO AUTENTICADO
// ============================================================

Route::middleware('auth')->group(function () {

    // ========================================================
    // MIS CITAS
    // ========================================================

    Route::get('/mis-citas', [ClientAppointmentController::class, 'index'])
        ->name('client.appointments.index');

    Route::get('/mis-citas/{appointment}/editar', [ClientAppointmentController::class, 'edit'])
        ->name('client.appointments.edit');

    Route::put('/mis-citas/{appointment}', [ClientAppointmentController::class, 'update'])
        ->name('client.appointments.update');

    Route::patch('/mis-citas/{appointment}/cancelar', [ClientAppointmentController::class, 'cancel'])
        ->name('client.appointments.cancel');

    // ========================================================
    // PERFIL
    // ========================================================

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__.'/auth.php';