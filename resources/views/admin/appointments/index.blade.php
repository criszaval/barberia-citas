<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agenda Global de Citas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filtros de Búsqueda -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('admin.appointments.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    
                    <!-- Filtro por Barbero -->
                    <div>
                        <label for="staff_profile_id" class="block text-sm font-medium text-gray-700">Barbero</label>
                        <select name="staff_profile_id" id="staff_profile_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Todos los barberos</option>
                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" {{ request('staff_profile_id') == $barber->id ? 'selected' : '' }}>
                                    {{ $barber->user->name ?? 'Barbero #'.$barber->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Fecha -->
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="appointment_date" id="appointment_date" value="{{ request('appointment_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center space-x-2">
                        <button type="submit" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm text-sm transition">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-md text-sm transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de Citas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="p-3">Cliente / Contacto</th>
                                <th class="p-3">Barbero</th>
                                <th class="p-3">Servicio</th>
                                <th class="p-3">Fecha y Hora</th>
                                <th class="p-3 text-center">Estado</th>
                                <th class="p-3 text-right">Acciones Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <!-- Nombre del Cliente (Registrado o Invitado) -->
                                    <td class="p-3">
                                        <div class="font-semibold text-gray-900">
                                            {{ $appointment->guest_name ?? $appointment->client->name ?? 'Cliente no especificado' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            📧 {{ $appointment->guest_email ?? $appointment->client->email ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            📞 {{ $appointment->guest_phone ?? '-' }}
                                        </div>
                                    </td>

                                    <!-- Barbero Asignado -->
                                    <td class="p-3 text-gray-700 font-medium">
                                        {{ $appointment->staffProfile->user->name ?? 'Sin asignar' }}
                                    </td>

                                    <!-- Servicio Seleccionado -->
                                    <td class="p-3 text-gray-700">
                                        <div class="font-medium">{{ $appointment->service->name ?? 'Servicio no disponible' }}</div>
                                        <div class="text-xs text-gray-500">
                                            ${{ number_format($appointment->service->price ?? 0, 2) }} 
                                            ({{ $appointment->service->duration_minutes ?? 0 }} min)
                                        </div>
                                    </td>

                                    <!-- Fecha y Horario -->
                                    <td class="p-3 text-gray-700">
                                        <div>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                        </div>
                                    </td>

                                    <!-- Badge de Estado -->
                                    <td class="p-3 text-center">
                                        @switch($appointment->status)
                                            @case('confirmed')
                                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmada</span>
                                                @break
                                            @case('completed')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completada</span>
                                                @break
                                            @case('cancelled')
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelada</span>
                                                @break
                                            @default
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendiente</span>
                                        @endswitch
                                    </td>

                                    <!-- Acciones / Control de Estado -->
                                    <td class="p-3 text-right whitespace-nowrap">
                                        @if($appointment->status === 'completed')
                                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-md border border-gray-300 inline-flex items-center gap-1">
                                                🔒 Finalizada
                                            </span>
                                        @else
                                            <form action="{{ route('admin.appointments.updateStatus', $appointment->id) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmar</option>
                                                    <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completar</option>
                                                    <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelar</option>
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">
                                        No hay citas registradas en la plataforma.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>