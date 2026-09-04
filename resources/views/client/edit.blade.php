<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reagendar Cita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-800 mb-4">Modificar Fecha y Hora</h3>

                <div class="mb-4 text-sm text-gray-600 bg-gray-50 p-3 rounded">
                    <p><strong>Servicio:</strong> {{ $appointment->service->name }}</p>
                    <p><strong>Barbero:</strong> {{ $appointment->staffProfile->user->name ?? 'Asignación automática' }}</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client.appointments.update', $appointment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nueva Fecha -->
                    <div class="mb-4">
                        <x-input-label for="appointment_date" :value="__('Nueva Fecha')" />
                        <x-text-input id="appointment_date" class="block mt-1 w-full" type="date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}" required />
                        <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                    </div>

                    <!-- Nueva Hora -->
                    <div class="mb-4">
                        <x-input-label for="start_time" :value="__('Nueva Hora')" />
                        <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" value="{{ old('start_time', $appointment->start_time) }}" required />
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-3">
                        <a href="{{ route('client.appointments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Cancelar') }}
                        </a>
                        <x-primary-button>
                            {{ __('Guardar Cambios') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>