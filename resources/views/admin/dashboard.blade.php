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
                    <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <h4 class="font-semibold text-indigo-900">Agenda Global</h4>
                        <p class="text-sm text-indigo-700 mt-1">Ver y gestionar citas de todos los empleados.</p>
                        <a href="{{ route('staff.dashboard') }}" class="inline-block mt-3 text-xs bg-indigo-600 text-white py-1 px-3 rounded">Ver Citas Totales</a>
                    </div>

                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-semibold text-green-900">Servicios</h4>
                        <p class="text-sm text-green-700 mt-1">Administrar catálogo, precios y duraciones.</p>
                        <span class="inline-block mt-3 text-xs text-green-800 font-bold">Activos</span>
                    </div>

                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="font-semibold text-yellow-900">Personal (Staff)</h4>
                        <p class="text-sm text-yellow-700 mt-1">Gestionar empleados, cuentas y horarios.</p>
                        <span class="inline-block mt-3 text-xs text-yellow-800 font-bold">Activos</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>