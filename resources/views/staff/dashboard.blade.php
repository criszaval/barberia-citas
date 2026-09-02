<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Agenda de Citas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-lg font-bold text-gray-800 mb-4">Próximas Citas Asignadas</h3>

                @if($appointments->isEmpty())
                    <p class="text-gray-500">No tienes citas programadas por el momento.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b text-gray-700 text-sm font-semibold">
                                    <th class="p-3">Fecha</th>
                                    <th class="p-3">Horario</th>
                                    <th class="p-3">Cliente</th>
                                    <th class="p-3">Servicio</th>
                                    <th class="p-3">Contacto</th>
                                    <th class="p-3">Estado</th>
                                    <th class="p-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr class="border-b hover:bg-gray-50 text-sm">
                                        <td class="p-3 font-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                        <td class="p-3 text-indigo-600 font-semibold">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="p-3">
                                            <strong>{{ $appointment->guest_name }}</strong>
                                            @if($appointment->client_id)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded ml-1">Registrado</span>
                                            @else
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded ml-1">Invitado</span>
                                            @endif
                                        </td>
                                        <td class="p-3">{{ $appointment->service->name }}</td>
                                        <td class="p-3 text-xs text-gray-600">
                                            <div>📞 {{ $appointment->guest_phone }}</div>
                                            <div>✉️ {{ $appointment->guest_email }}</div>
                                        </td>
                                        <td class="p-3">
                                            @switch($appointment->status)
                                                @case('pending')
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-0.5 rounded font-medium">Pendiente</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded font-medium">Confirmada</span>
                                                    @break
                                                @case('completed')
                                                    <span class="bg-green-100 text-green-800 text-xs px-2.5 py-0.5 rounded font-medium">Completada</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="bg-red-100 text-red-800 text-xs px-2.5 py-0.5 rounded font-medium">Cancelada</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="p-3 text-center">
                                            <form action="{{ route('staff.appointments.updateStatus', $appointment) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmar</option>
                                                    <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completar</option>
                                                    <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelar</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>