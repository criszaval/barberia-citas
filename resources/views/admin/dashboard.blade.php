<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control General (Administración)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">¡Bienvenido Administrador!</h3>
                <p class="text-gray-600 mb-6">Desde aquí tendrás visión global de la barbería, reportes y gestión de personal.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Agenda Global -->
                    <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-indigo-900">Agenda Global</h4>
                            <p class="text-sm text-indigo-700 mt-1">Ver y gestionar citas de todos los empleados.</p>
                        </div>
<<<<<<< HEAD
                        <a href="{{ route('staff.dashboard') }}" style="background-color: #4f46e5; color: #ffffff;" class="inline-block mt-4 text-xs bg-indigo-600 text-white font-semibold py-2 px-3 rounded text-center hover:bg-indigo-700 transition">
=======
                        <a href="{{ route('staff.dashboard') }}" style="background-color: #4f46e5; color: #ffffff;" class="inline-block mt-4 text-xs bg-indigo-600 text-white font-semibold py-2 px-3 rounded text-center hover:bg-indigo-700">
>>>>>>> 1f22b34d7c367ccdc0c5e339d1e8484b49e244f6
                            Ver Citas Totales
                        </a>
                    </div>

                    <!-- Servicios (Conectado al CRUD) -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-green-900">Servicios</h4>
                            <p class="text-sm text-green-700 mt-1">Administrar catálogo, precios y duraciones.</p>
                        </div>
<<<<<<< HEAD
                        <a href="{{ route('admin.services.index') }}" style="background-color: #16a34a; color: #ffffff;" class="inline-block mt-4 text-xs bg-green-600 text-white font-semibold py-2 px-3 rounded text-center hover:bg-green-700 transition">
=======
                        <a href="{{ route('admin.services.index') }}" style="background-color: #16a34a; color: #ffffff;" class="inline-block mt-4 text-xs bg-green-600 text-white font-semibold py-2 px-3 rounded text-center hover:bg-green-700">
>>>>>>> 1f22b34d7c367ccdc0c5e339d1e8484b49e244f6
                            Administrar Servicios
                        </a>
                    </div>

<<<<<<< HEAD
                    <!-- Personal / Staff (Conectado al CRUD) -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-yellow-900">Personal (Staff)</h4>
                            <p class="text-sm text-yellow-700 mt-1">Gestionar barberos, cuentas y comisiones.</p>
                        </div>
                        <a href="{{ route('admin.staff.index') }}" style="background-color: #ca8a04; color: #ffffff;" class="inline-block mt-4 text-xs bg-yellow-600 text-white font-semibold py-2 px-3 rounded text-center hover:bg-yellow-700 transition">
                            Gestionar Barberos
                        </a>
=======
                    <!-- Personal / Staff -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex flex-col justify-between">
                        <div>
                            <h4 class="font-semibold text-yellow-900">Personal (Staff)</h4>
                            <p class="text-sm text-yellow-700 mt-1">Gestionar empleados, cuentas y horarios.</p>
                        </div>
                        <span class="inline-block mt-4 text-xs bg-yellow-200 text-yellow-800 font-bold py-2 px-3 rounded text-center">
                            Próximamente
                        </span>
>>>>>>> 1f22b34d7c367ccdc0c5e339d1e8484b49e244f6
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>