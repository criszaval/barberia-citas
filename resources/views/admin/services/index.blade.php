<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Servicios</h2>
            <a href="{{ route('admin.services.create') }}" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">
                + Nuevo Servicio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b text-gray-700 text-sm">
                                <th class="p-3">Servicio</th>
                                <th class="p-3">Precio</th>
                                <th class="p-3">Duración</th>
                                <th class="p-3">Estado</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr class="border-b hover:bg-gray-50 text-sm">
                                    <td class="p-3 font-semibold">{{ $service->name }}</td>
                                    <td class="p-3 text-green-600 font-bold">${{ number_format($service->price, 2) }}</td>
                                    <td class="p-3">{{ $service->duration_minutes }} min</td>
                                    <td class="p-3">
                                        @if($service->is_active)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center space-x-2">
                                        <a href="{{ route('admin.services.edit', $service) }}" class="text-indigo-600 hover:underline text-xs font-bold">Editar</a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar servicio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-xs font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>