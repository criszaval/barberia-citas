
<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            {{-- TÍTULO --}}
            <div class="shrink-0">

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Mi Agenda de Citas') }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Consulte y administre sus próximas citas.
                </p>

            </div>


            {{-- RESUMEN DEL HORARIO --}}
            <div class="flex items-center gap-3">

                <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 shadow-sm">

                    <div class="flex items-center gap-2 mb-1">

                        {{-- Icono reloj --}}
                        <svg
                            class="w-4 h-4 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                            Mi horario
                        </span>

                    </div>


                    {{-- DÍAS --}}
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">

                        @php
                            $days = [
                                0 => 'Dom',
                                1 => 'Lun',
                                2 => 'Mar',
                                3 => 'Mié',
                                4 => 'Jue',
                                5 => 'Vie',
                                6 => 'Sáb',
                            ];
                        @endphp


                        @foreach($schedules as $schedule)

                            @php
                                $dayName = $days[$schedule->day_of_week] ?? '';
                            @endphp


                            @if($schedule->is_active)

                                {{-- DÍA ACTIVO --}}
                                <span class="inline-flex items-center gap-1 text-xs text-gray-600">

                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                    <span class="font-medium">
                                        {{ $dayName }}
                                    </span>

                                    <span class="text-gray-400">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                    </span>

                                </span>

                            @else

                                {{-- DÍA INACTIVO --}}
                                <span class="inline-flex items-center gap-1 text-xs text-gray-400">

                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>

                                    <span class="font-medium">
                                        {{ $dayName }}
                                    </span>

                                    <span>
                                        Cerrado
                                    </span>

                                </span>

                            @endif

                        @endforeach

                    </div>

                </div>


                {{-- BOTÓN PARA EDITAR --}}
              

            </div>

        </div>

    </x-slot>


    {{-- CONTENIDO --}}
    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- CARD PRINCIPAL --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">


                {{-- MENSAJE DE ÉXITO --}}
                @if (session('success'))

                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- MENSAJE DE ERROR --}}
                @if (session('error'))

                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">

                        {{ session('error') }}

                    </div>

                @endif


                {{-- ERRORES DE VALIDACIÓN --}}
                @if ($errors->any())

                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">

                        <ul class="list-disc list-inside">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- ENCABEZADO DE CITAS --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Próximas Citas Asignadas
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Aquí puede consultar y administrar sus citas.
                        </p>

                    </div>


                    {{-- BOTÓN CONFIGURAR HORARIO --}}
                    <a
                        href="{{ route('staff.schedule.index') }}"
                        class="inline-flex items-center justify-center
                               px-4 py-2
                               bg-gray-800
                               text-white
                               rounded-md
                               text-sm
                               font-medium
                               hover:bg-gray-700
                               transition"
                    >
                        Configurar mi horario
                    </a>

                </div>


                {{-- SIN CITAS --}}
                @if($appointments->isEmpty())

                    <div class="py-8 text-center">

                        <div class="flex justify-center mb-3">

                            <svg
                                class="w-10 h-10 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>

                        </div>

                        <p class="text-gray-500">
                            No tienes citas programadas por el momento.
                        </p>

                    </div>


                @else


                    {{-- TABLA --}}
                    <div class="overflow-x-auto">

                        <table class="w-full text-left border-collapse">

                            {{-- CABECERA --}}
                            <thead>

                                <tr class="bg-gray-100 border-b text-gray-700 text-sm font-semibold">

                                    <th class="p-3">
                                        Fecha
                                    </th>

                                    <th class="p-3">
                                        Horario
                                    </th>

                                    <th class="p-3">
                                        Cliente
                                    </th>

                                    <th class="p-3">
                                        Servicio
                                    </th>

                                    <th class="p-3">
                                        Contacto
                                    </th>

                                    <th class="p-3">
                                        Estado
                                    </th>

                                    <th class="p-3 text-center">
                                        Acciones
                                    </th>

                                </tr>

                            </thead>


                            {{-- CITAS --}}
                            <tbody>

                                @foreach($appointments as $appointment)

                                    <tr class="border-b hover:bg-gray-50 text-sm">


                                        {{-- FECHA --}}
                                        <td class="p-3 font-medium">

                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}

                                        </td>


                                        {{-- HORARIO --}}
                                        <td class="p-3 text-indigo-600 font-semibold">

                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}

                                            -

                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}

                                        </td>


                                        {{-- CLIENTE --}}
                                        <td class="p-3">

                                            <strong>
                                                {{ $appointment->guest_name ?? $appointment->client->name ?? 'Cliente' }}
                                            </strong>


                                            @if($appointment->client_id)

                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800
                                                           px-2 py-0.5 rounded ml-1"
                                                >
                                                    Registrado
                                                </span>

                                            @else

                                                <span
                                                    class="text-xs bg-gray-100 text-gray-600
                                                           px-2 py-0.5 rounded ml-1"
                                                >
                                                    Invitado
                                                </span>

                                            @endif

                                        </td>


                                        {{-- SERVICIO --}}
                                        <td class="p-3">

                                            {{ $appointment->service->name ?? 'Servicio' }}

                                        </td>


                                        {{-- CONTACTO --}}
                                        <td class="p-3 text-xs text-gray-600">

                                            <div>
                                                📞 {{ $appointment->guest_phone ?? '-' }}
                                            </div>

                                            <div>
                                                ✉️ {{ $appointment->guest_email ?? '-' }}
                                            </div>

                                        </td>


                                        {{-- ESTADO --}}
                                        <td class="p-3">

                                            @switch($appointment->status)


                                                @case('pending')

                                                    <span
                                                        class="bg-yellow-100
                                                               text-yellow-800
                                                               text-xs
                                                               px-2.5
                                                               py-0.5
                                                               rounded
                                                               font-medium"
                                                    >
                                                        Pendiente
                                                    </span>

                                                    @break


                                                @case('confirmed')

                                                    <span
                                                        class="bg-blue-100
                                                               text-blue-800
                                                               text-xs
                                                               px-2.5
                                                               py-0.5
                                                               rounded
                                                               font-medium"
                                                    >
                                                        Confirmada
                                                    </span>

                                                    @break


                                                @case('completed')

                                                    <span
                                                        class="bg-green-100
                                                               text-green-800
                                                               text-xs
                                                               px-2.5
                                                               py-0.5
                                                               rounded
                                                               font-medium"
                                                    >
                                                        Completada
                                                    </span>

                                                    @break


                                                @case('cancelled')

                                                    <span
                                                        class="bg-red-100
                                                               text-red-800
                                                               text-xs
                                                               px-2.5
                                                               py-0.5
                                                               rounded
                                                               font-medium"
                                                    >
                                                        Cancelada
                                                    </span>

                                                    @break


                                            @endswitch

                                        </td>


                                        {{-- ACCIONES --}}
                                        <td class="p-3 text-center whitespace-nowrap">


                                            @if(
                                                $appointment->status === 'completed' ||
                                                $appointment->status === 'cancelled'
                                            )

                                                {{-- CITA BLOQUEADA --}}
                                                <span
                                                    class="text-xs
                                                           font-semibold
                                                           text-gray-500
                                                           bg-gray-100
                                                           px-3
                                                           py-1
                                                           rounded-md
                                                           border
                                                           border-gray-300
                                                           inline-flex
                                                           items-center
                                                           gap-1"
                                                >
                                                    🔒 Bloqueada
                                                </span>


                                            @else


                                                {{-- CAMBIAR ESTADO --}}
                                                <form
                                                    action="{{ route('staff.appointments.updateStatus', $appointment) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                >

                                                    @csrf

                                                    @method('PATCH')


                                                    <select
                                                        name="status"
                                                        onchange="this.form.submit()"
                                                        class="text-xs
                                                               border-gray-300
                                                               rounded
                                                               focus:ring-indigo-500
                                                               focus:border-indigo-500"
                                                    >

                                                        <option
                                                            value="pending"
                                                            {{ $appointment->status == 'pending' ? 'selected' : '' }}
                                                        >
                                                            Pendiente
                                                        </option>


                                                        <option
                                                            value="confirmed"
                                                            {{ $appointment->status == 'confirmed' ? 'selected' : '' }}
                                                        >
                                                            Confirmar
                                                        </option>


                                                        <option
                                                            value="completed"
                                                            {{ $appointment->status == 'completed' ? 'selected' : '' }}
                                                        >
                                                            Completar
                                                        </option>


                                                        <option
                                                            value="cancelled"
                                                            {{ $appointment->status == 'cancelled' ? 'selected' : '' }}
                                                        >
                                                            Cancelar
                                                        </option>

                                                    </select>

                                                </form>

                                            @endif

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
