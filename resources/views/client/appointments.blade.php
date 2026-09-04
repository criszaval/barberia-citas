<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Citas Reservadas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Historial de Mis Citas</h3>
                    <a href="{{ route('appointments.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-md shadow-sm transition">
                        + Nueva Cita
                    </a>
                </div>

                @if($appointments->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-base">Aún no tienes citas agendadas.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b text-gray-700 text-sm font-semibold">
                                    <th class="p-3">Fecha</th>
                                    <th class="p-3">Horario</th>
                                    <th class="p-3">Servicio</th>
                                    <th class="p-3">Barbero</th>
                                    <th class="p-3 text-center">Estado</th>
                                    <th class="p-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr class="border-b hover:bg-gray-50 text-sm">
                                        <td class="p-3 font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="p-3 text-indigo-600 font-semibold">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="p-3">
                                            <div class="font-medium text-gray-800">{{ $appointment->service->name ?? 'Servicio' }}</div>
                                            <div class="text-xs text-gray-500">${{ number_format($appointment->service->price ?? 0, 2) }}</div>
                                        </td>
                                        <td class="p-3 font-medium text-gray-700">
                                            {{ $appointment->staffProfile->user->name ?? 'Por asignar' }}
                                        </td>
                                        <td class="p-3 text-center">
                                            @switch($appointment->status)
                                                @case('pending')
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded font-semibold">Pendiente</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-1 rounded font-semibold">Confirmada</span>
                                                    @break
                                                @case('completed')
                                                    <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded font-semibold">Completada</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="bg-red-100 text-red-800 text-xs px-2.5 py-1 rounded font-semibold">Cancelada</span>
                                                    @break
                                            @endswitch
                                        </td>
                                       <td class="p-3 text-center">
    <div class="flex justify-center items-center gap-2">
        @if($appointment->status === 'pending')
            {{-- Si está pendiente: Puede Reagendar y Cancelar --}}
            <a href="{{ route('client.appointments.edit', $appointment) }}" class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 px-3 py-1 rounded font-medium transition">
                ✏️ Reagendar
            </a>
            
            <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-xs bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 px-3 py-1 rounded font-medium transition">
                    🚫 Cancelar
                </button>
            </form>

        @elseif($appointment->status === 'confirmed')
            {{-- Si está confirmada: ÚNICAMENTE puede Cancelar --}}
            <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar tu cita confirmada?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-xs bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 px-3 py-1 rounded font-medium transition">
                    🚫 Cancelar
                </button>
            </form>

        @else
            {{-- Completada o Cancelada --}}
            <span class="text-xs text-gray-400 italic">Sin acciones</span>
        @endif
    </div>
</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>