<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agregar Nuevo Servicio</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nombre del Servicio</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Precio ($)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Duración (Minutos)</label>
                            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Descripción (Opcional)</label>
                        <textarea name="description" rows="3" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.services.index') }}" class="px-4 py-2 border rounded-md text-gray-600 text-sm">Cancelar</a>
                        <button type="submit" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold">Guardar Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>