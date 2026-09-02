<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StaffProfile;
use App\Models\Service;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialBusinessSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Administrador
        User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@barberia.com',
            'phone' => '70000000',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Crear Servicios
        $corte = Service::create([
            'name' => 'Corte de Cabello Tradicional',
            'description' => 'Corte clásico o moderno con lavado incluido.',
            'duration_minutes' => 30,
            'price' => 10.00,
            'is_active' => true,
        ]);

        $barba = Service::create([
            'name' => 'Perfilado y Cuidado de Barba',
            'description' => 'Afeitado o perfilado con toalla caliente.',
            'duration_minutes' => 20,
            'price' => 7.00,
            'is_active' => true,
        ]);

        $combo = Service::create([
            'name' => 'Combo Ejecutivo (Corte + Barba)',
            'description' => 'Servicio completo de corte, lavado y tratamiento de barba.',
            'duration_minutes' => 50,
            'price' => 15.00,
            'is_active' => true,
        ]);

        // 3. Crear Barbero 1 (Carlos López)
        $userCarlos = User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@barberia.com',
            'phone' => '71111111',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'is_active' => true,
        ]);

        $staffCarlos = StaffProfile::create([
            'user_id' => $userCarlos->id,
            'bio' => 'Especialista en degradados (fades) y cortes clásicos con 5 años de experiencia.',
            'is_active' => true,
        ]);

        // Asignar todos los servicios a Carlos
        $staffCarlos->services()->attach([$corte->id, $barba->id, $combo->id]);

        // Horario de Carlos: Lunes a Viernes (días 1 al 5) de 8:00 a 17:00
        for ($day = 1; $day <= 5; $day++) {
            Schedule::create([
                'staff_profile_id' => $staffCarlos->id,
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'is_active' => true,
            ]);
        }

        // 4. Crear Barbero 2 (Mateo Gómez)
        $userMateo = User::create([
            'name' => 'Mateo Gómez',
            'email' => 'mateo@barberia.com',
            'phone' => '72222222',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'is_active' => true,
        ]);

        $staffMateo = StaffProfile::create([
            'user_id' => $userMateo->id,
            'bio' => 'Experto en estilismo, barba ritual y diseño de cejas.',
            'is_active' => true,
        ]);

        // Asignar solo Corte y Barba a Mateo
        $staffMateo->services()->attach([$corte->id, $barba->id]);

        // Horario de Mateo: Martes a Sábado (días 2 al 6) de 10:00 a 19:00
        for ($day = 2; $day <= 6; $day++) {
            Schedule::create([
                'staff_profile_id' => $staffMateo->id,
                'day_of_week' => $day,
                'start_time' => '10:00:00',
                'end_time' => '19:00:00',
                'is_active' => true,
            ]);
        }
    }
}