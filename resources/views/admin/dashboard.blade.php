```blade
<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Administración
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Resumen general de la barbería
            </p>
        </div>

    </x-slot>


    {{-- ========================================================= --}}
    {{-- CSS DEL DASHBOARD --}}
    {{-- ========================================================= --}}

    <style>

        /* =========================================================
           MÉTRICAS PRINCIPALES
        ========================================================= */

        .dashboard-metricas {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
            width: 100%;
        }

        .dashboard-metricas > div {
            width: 100%;
            min-width: 0;
        }


        /* =========================================================
           CITAS POR ESTADO
        ========================================================= */

        .dashboard-estados {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
            width: 100%;
        }

        .dashboard-estados > div {
            width: 100%;
            min-width: 0;
        }


        /* =========================================================
           RESUMEN DE CITAS
        ========================================================= */

        .dashboard-resumen {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            width: 100%;
        }

        .dashboard-resumen > div {
            width: 100%;
            min-width: 0;
        }


        /* =========================================================
           ACCESOS RÁPIDOS
        ========================================================= */

        .dashboard-accesos {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            width: 100%;
        }

        .dashboard-accesos > div {
            width: 100%;
            min-width: 0;
        }


        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 1023px) {

            .dashboard-metricas {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-estados {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-resumen {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-accesos {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }


        /* =========================================================
           MÓVIL
        ========================================================= */

        @media (max-width: 639px) {

            .dashboard-metricas {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .dashboard-estados {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .dashboard-resumen {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .dashboard-accesos {
                grid-template-columns: 1fr;
                gap: 12px;
            }

        }

    </style>


    <div class="py-6 sm:py-8 lg:py-10">

        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- MENSAJE DE BIENVENIDA --}}
            {{-- ========================================================= --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-5 sm:mb-6">

                <h3 class="text-base sm:text-lg font-bold text-gray-800">
                    ¡Bienvenido Administrador!
                </h3>

                <p class="text-sm sm:text-base text-gray-600 mt-1">
                    Desde este panel puede consultar el estado general de las
                    citas, clientes, barberos y servicios.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- MÉTRICAS PRINCIPALES --}}
            {{-- ========================================================= --}}

            <div class="dashboard-metricas">


                {{-- TOTAL CITAS --}}

                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 sm:p-5">

                    <div class="flex items-center justify-between gap-3">

                        <div class="min-w-0">

                            <p class="text-sm font-medium text-indigo-700">
                                Total de citas
                            </p>

                            <p class="text-2xl sm:text-3xl font-bold text-indigo-900 mt-2">
                                {{ $totalAppointments }}
                            </p>

                        </div>


                        <div class="shrink-0 w-10 h-10 sm:w-11 sm:h-11 bg-indigo-100 rounded-full flex items-center justify-center">

                            <svg
                                class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600"
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

                    </div>


                    <p class="text-xs text-indigo-600 mt-4">
                        Citas registradas en el sistema
                    </p>

                </div>



                {{-- CLIENTES --}}

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-5">

                    <div class="flex items-center justify-between gap-3">

                        <div class="min-w-0">

                            <p class="text-sm font-medium text-blue-700">
                                Clientes
                            </p>

                            <p class="text-2xl sm:text-3xl font-bold text-blue-900 mt-2">
                                {{ $totalClients }}
                            </p>

                        </div>


                        <div class="shrink-0 w-10 h-10 sm:w-11 sm:h-11 bg-blue-100 rounded-full flex items-center justify-center">

                            <svg
                                class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-5a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 100-6 3 3 0 000 6z"
                                />

                            </svg>

                        </div>

                    </div>


                    <p class="text-xs text-blue-600 mt-4">
                        Clientes registrados
                    </p>

                </div>



                {{-- BARBEROS --}}

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-5">

                    <div class="flex items-center justify-between gap-3">

                        <div class="min-w-0">

                            <p class="text-sm font-medium text-yellow-700">
                                Barberos
                            </p>

                            <p class="text-2xl sm:text-3xl font-bold text-yellow-900 mt-2">
                                {{ $totalBarbers }}
                            </p>

                        </div>


                        <div class="shrink-0 w-10 h-10 sm:w-11 sm:h-11 bg-yellow-100 rounded-full flex items-center justify-center">

                            <svg
                                class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0H5z"
                                />

                            </svg>

                        </div>

                    </div>


                    <p class="text-xs text-yellow-700 mt-4">
                        Personal registrado
                    </p>

                </div>



                {{-- SERVICIOS --}}

                <div class="bg-green-50 border border-green-200 rounded-xl p-4 sm:p-5">

                    <div class="flex items-center justify-between gap-3">

                        <div class="min-w-0">

                            <p class="text-sm font-medium text-green-700">
                                Servicios
                            </p>

                            <p class="text-2xl sm:text-3xl font-bold text-green-900 mt-2">
                                {{ $totalServices }}
                            </p>

                        </div>


                        <div class="shrink-0 w-10 h-10 sm:w-11 sm:h-11 bg-green-100 rounded-full flex items-center justify-center">

                            <svg
                                class="w-5 h-5 sm:w-6 sm:h-6 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v12m6-6H6"
                                />

                            </svg>

                        </div>

                    </div>


                    <p class="text-xs text-green-700 mt-4">
                        Servicios disponibles
                    </p>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- CITAS POR ESTADO --}}
            {{-- ========================================================= --}}

            <div class="dashboard-estados">


                {{-- PENDIENTES --}}

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-5">

                    <p class="text-sm font-medium text-yellow-700">
                        Pendientes
                    </p>

                    <p class="text-2xl sm:text-3xl font-bold text-yellow-900 mt-2">
                        {{ $pendingAppointments }}
                    </p>

                    <p class="text-xs text-yellow-600 mt-3">
                        Citas esperando confirmación
                    </p>

                </div>



                {{-- CONFIRMADAS --}}

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-5">

                    <p class="text-sm font-medium text-blue-700">
                        Confirmadas
                    </p>

                    <p class="text-2xl sm:text-3xl font-bold text-blue-900 mt-2">
                        {{ $confirmedAppointments }}
                    </p>

                    <p class="text-xs text-blue-600 mt-3">
                        Citas confirmadas
                    </p>

                </div>



                {{-- COMPLETADAS --}}

                <div class="bg-green-50 border border-green-200 rounded-xl p-4 sm:p-5">

                    <p class="text-sm font-medium text-green-700">
                        Completadas
                    </p>

                    <p class="text-2xl sm:text-3xl font-bold text-green-900 mt-2">
                        {{ $completedAppointments }}
                    </p>

                    <p class="text-xs text-green-600 mt-3">
                        Servicios realizados
                    </p>

                </div>



                {{-- CANCELADAS --}}

                <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-5">

                    <p class="text-sm font-medium text-red-700">
                        Canceladas
                    </p>

                    <p class="text-2xl sm:text-3xl font-bold text-red-900 mt-2">
                        {{ $cancelledAppointments }}
                    </p>

                    <p class="text-xs text-red-600 mt-3">
                        Citas canceladas
                    </p>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- RESUMEN DE CITAS --}}
            {{-- ========================================================= --}}

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6 mb-5 sm:mb-6">

                <div class="mb-5">

                    <h3 class="text-base sm:text-lg font-bold text-gray-800">
                        Resumen de citas
                    </h3>

                    <p class="text-sm text-gray-500">
                        Citas registradas según período
                    </p>

                </div>


                <div class="dashboard-resumen">


                    {{-- HOY --}}

                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 sm:p-5">

                        <p class="text-sm font-medium text-indigo-700">
                            Citas de hoy
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold text-indigo-900 mt-2">
                            {{ $todayAppointments }}
                        </p>

                        <p class="text-xs text-indigo-600 mt-3">
                            {{ now()->format('d/m/Y') }}
                        </p>

                    </div>



                    {{-- SEMANA --}}

                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 sm:p-5">

                        <p class="text-sm font-medium text-purple-700">
                            Citas esta semana
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold text-purple-900 mt-2">
                            {{ $weekAppointments }}
                        </p>

                        <p class="text-xs text-purple-600 mt-3">
                            Semana actual
                        </p>

                    </div>



                    {{-- MES --}}

                    <div class="bg-pink-50 border border-pink-200 rounded-xl p-4 sm:p-5">

                        <p class="text-sm font-medium text-pink-700">
                            Citas este mes
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold text-pink-900 mt-2">
                            {{ $monthAppointments }}
                        </p>

                        <p class="text-xs text-pink-600 mt-3">
                            {{ now()->translatedFormat('F Y') }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- CITAS POR BARBERO --}}
            {{-- ========================================================= --}}

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6 mb-5 sm:mb-6">

                <div class="mb-6">

                    <h3 class="text-base sm:text-lg font-bold text-gray-800">
                        Citas por barbero
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Cantidad total de citas asignadas a cada barbero.
                    </p>

                </div>


                @if($appointmentsByBarber->count() > 0)

                    @php

                        $maxAppointments = max(
                            $appointmentsByBarber->max('appointments_count'),
                            1
                        );

                    @endphp


                    <div class="space-y-5">

                        @foreach($appointmentsByBarber as $barber)

                            @php

                                $percentage =
                                    ($barber->appointments_count / $maxAppointments) * 100;

                            @endphp


                            <div>

                                <div class="flex flex-row justify-between items-center gap-2 mb-2">

                                    <span class="text-sm font-semibold text-gray-700 break-words">
                                        {{ $barber->user->name ?? 'Barbero sin nombre' }}
                                    </span>

                                    <span class="text-sm font-bold text-gray-800 whitespace-nowrap">
                                        {{ $barber->appointments_count }}
                                        {{ $barber->appointments_count == 1 ? 'cita' : 'citas' }}
                                    </span>

                                </div>


                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">

                                    <div
                                        class="bg-indigo-600 h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%;"
                                    ></div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-8">

                        <p class="text-gray-500">
                            Todavía no hay barberos registrados.
                        </p>

                    </div>

                @endif

            </div>



            {{-- ========================================================= --}}
            {{-- RENDIMIENTO DE BARBEROS --}}
            {{-- ========================================================= --}}

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6 mb-5 sm:mb-6">

                <div class="mb-5 text-center">

                    <h3 class="text-base sm:text-lg font-bold text-gray-800">
                        Rendimiento de barberos
                    </h3>

                    <p class="text-sm text-gray-500">
                        Resumen de citas gestionadas por cada barbero.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <div class="flex justify-center min-w-[650px]">

                        <table class="w-full max-w-4xl divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                    >
                                        Barbero
                                    </th>

                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                    >
                                        Total
                                    </th>

                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                    >
                                        Completadas
                                    </th>

                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                    >
                                        Canceladas
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($barberPerformance as $barber)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-4 py-4 text-center whitespace-nowrap">

                                            <div class="font-medium text-gray-900">
                                                {{ $barber->user->name ?? 'Barbero sin nombre' }}
                                            </div>

                                        </td>


                                        <td class="px-4 py-4 text-center">

                                            <span
                                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800"
                                            >
                                                {{ $barber->appointments_count }}
                                            </span>

                                        </td>


                                        <td class="px-4 py-4 text-center">

                                            <span
                                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800"
                                            >
                                                {{ $barber->completed_appointments_count }}
                                            </span>

                                        </td>


                                        <td class="px-4 py-4 text-center">

                                            <span
                                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800"
                                            >
                                                {{ $barber->cancelled_appointments_count }}
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="px-4 py-8 text-center text-gray-500"
                                        >
                                            No hay información de barberos.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- ACCESOS RÁPIDOS --}}
            {{-- ========================================================= --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-4 sm:p-6">

                <div class="mb-5">

                    <h3 class="text-base sm:text-lg font-bold text-gray-800">
                        Accesos rápidos
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Administración general del sistema.
                    </p>

                </div>


                <div class="dashboard-accesos">


                    {{-- AGENDA --}}

                    <div
                        class="p-4 sm:p-5 bg-indigo-50 border border-indigo-200 rounded-xl flex flex-col justify-between"
                    >

                        <div>

                            <h4 class="font-semibold text-indigo-900">
                                Agenda Global
                            </h4>

                            <p class="text-sm text-indigo-700 mt-1">
                                Ver y gestionar citas de todos los empleados.
                            </p>

                        </div>


                        <a
                            href="{{ route('admin.appointments.index') }}"
                            style="background-color: #4f46e5; color: #ffffff;"
                            class="inline-block mt-4 text-sm font-semibold py-2 px-3 rounded text-center hover:opacity-90 transition"
                        >
                            Ver Citas Totales
                        </a>

                    </div>



                    {{-- CLIENTES --}}

                    <div
                        class="p-4 sm:p-5 bg-blue-50 border border-blue-200 rounded-xl flex flex-col justify-between"
                    >

                        <div>

                            <h4 class="font-semibold text-blue-900">
                                Clientes
                            </h4>

                            <p class="text-sm text-blue-700 mt-1">
                                Ver listado de clientes registrados en el sistema.
                            </p>

                        </div>


                        <a
                            href="{{ route('admin.clients.index') }}"
                            style="background-color: #2563eb; color: #ffffff;"
                            class="inline-block mt-4 text-sm font-semibold py-2 px-3 rounded text-center hover:opacity-90 transition"
                        >
                            Ver Clientes
                        </a>

                    </div>



                    {{-- SERVICIOS --}}

                    <div
                        class="p-4 sm:p-5 bg-green-50 border border-green-200 rounded-xl flex flex-col justify-between"
                    >

                        <div>

                            <h4 class="font-semibold text-green-900">
                                Servicios
                            </h4>

                            <p class="text-sm text-green-700 mt-1">
                                Administrar catálogo, precios y duraciones.
                            </p>

                        </div>


                        <a
                            href="{{ route('admin.services.index') }}"
                            style="background-color: #16a34a; color: #ffffff;"
                            class="inline-block mt-4 text-sm font-semibold py-2 px-3 rounded text-center hover:opacity-90 transition"
                        >
                            Administrar Servicios
                        </a>

                    </div>



                    {{-- PERSONAL --}}

                    <div
                        class="p-4 sm:p-5 bg-yellow-50 border border-yellow-200 rounded-xl flex flex-col justify-between"
                    >

                        <div>

                            <h4 class="font-semibold text-yellow-900">
                                Personal (Staff)
                            </h4>

                            <p class="text-sm text-yellow-700 mt-1">
                                Gestionar barberos, cuentas y personal.
                            </p>

                        </div>


                        <a
                            href="{{ route('admin.staff.index') }}"
                            style="background-color: #ca8a04; color: #ffffff;"
                            class="inline-block mt-4 text-sm font-semibold py-2 px-3 rounded text-center hover:opacity-90 transition"
                        >
                            Gestionar Barberos
                        </a>

                    </div>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>
```
