<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Muestra la lista de barberos.
     */
    public function index()
    {
        $staffMembers = StaffProfile::with('user')->latest()->paginate(10);
        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Formulario para crear un nuevo barbero.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Guarda un nuevo barbero en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'specialty' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
        ]);

        // 1. Crear el usuario en la tabla `users`
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff', // Asignamos rol de personal/barbero
        ]);

        // 2. Crear el perfil en `staff_profiles`
        StaffProfile::create([
            'user_id' => $user->id,
            'specialty' => $validated['specialty'],
            'commission_rate' => $validated['commission_rate'] ?? 0,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Barbero registrado correctamente.');
    }

    /**
     * Formulario para editar un barbero.
     */
    public function edit(StaffProfile $staff)
    {
        $staff->load('user');
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Actualiza la información del barbero.
     */
    public function update(Request $request, StaffProfile $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($staff->user_id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'specialty' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
        ]);

        // Actualizar datos del Usuario
        $user = $staff->user;
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Actualizar datos del Perfil del Barbero
        $staff->update([
            'specialty' => $validated['specialty'],
            'commission_rate' => $validated['commission_rate'] ?? 0,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Datos del barbero actualizados correctamente.');
    }

    /**
     * Elimina a un barbero (o lo desactiva).
     */
    public function destroy(StaffProfile $staff)
    {
        $user = $staff->user;
        $staff->delete();
        $user->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Barbero eliminado correctamente.');
    }
}