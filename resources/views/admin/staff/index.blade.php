<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Barberos / Personal') }}
            </h2>
            <a href="{{ route('admin.staff.create') }}" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm transition">
                + Nuevo Barbero
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerta de éxito --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="p-3">Nombre</th>
                                <th class="p-3">Contacto</th>
                                <th class="p-3">Especialidad</th>
                                <th class="p-3">Comisión</th>
                                <th class="p-3 text-center">Estado</th>
                                <th class="p-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($staffMembers as $staff)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-semibold text-gray-900">
                                        {{ $staff->user->name }}
                                    </td>
                                    <td class="p-3 text-gray-600">
                                        <div>{{ $staff->user->email }}</div>
                                        <div class="text-xs text-gray-400">{{ $staff->user->phone ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="p-3 text-gray-600">
                                        {{ $staff->specialty ?? 'General' }}
                                    </td>
                                    <td class="p-3 text-gray-600">
                                        {{ $staff->commission_rate }}%
                                    </td>
                                    <td class="p-3 text-center">
                                        @if($staff->is_active)
                                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Activo</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right space-x-2">
                                        <a href="{{ route('admin.staff.edit', $staff->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</a>
                                        
                                        <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar a este barbero?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">
                                        No hay barberos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $staffMembers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>