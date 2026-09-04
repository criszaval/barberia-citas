<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <p class="text-lg font-medium text-gray-800">
                            {{ __("¡Bienvenido! Has iniciado sesión correctamente.") }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Desde aquí puedes revisar el historial y estado de tus citas agendadas.
                        </p>
                    </div>

                    <!-- Enlace directo a Mis Citas -->
                    <a href="{{ route('client.appointments.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Ver Mis Citas') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>