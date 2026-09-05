<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-800 mb-6">Listado de Clientes Registrados</h3>

                @if($clients->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-base">No hay clientes registrados en el sistema.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b text-gray-700 text-sm font-semibold">
                                    <th class="p-3">Nombre</th>
                                    <th class="p-3">Correo Electrónico</th>
                                    <th class="p-3 text-center">Total de Citas</th>
                                    <th class="p-3">Fecha de Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr class="border-b hover:bg-gray-50 text-sm">
                                        <td class="p-3 font-medium text-gray-900">
                                            {{ $client->name }}
                                        </td>
                                        <td class="p-3 text-gray-600">
                                            {{ $client->email }}
                                        </td>
                                        <td class="p-3 text-center">
                                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2.5 py-1 rounded font-semibold">
                                                {{ $client->client_appointments_count ?? 0 }} citas
                                            </span>
                                        </td>
                                        <td class="p-3 text-gray-500">
                                            {{ $client->created_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $clients->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>