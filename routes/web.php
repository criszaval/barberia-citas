<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas públicas para la reserva de citas
Route::get('/reservar', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/reservar', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\StaffDashboardController;

// Rutas protegidas para personal / empleados
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/appointments/{appointment}/status', [StaffDashboardController::class, 'updateStatus'])->name('appointments.updateStatus');
});

require __DIR__.'/auth.php';