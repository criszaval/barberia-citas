<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Barbero: ') }} {{ $staff->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $staff->user->name) }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Correo y Teléfono -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $staff->user->email) }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono / WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $staff->user->phone) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Cambiar Contraseña (Opcional) -->
                    <div class="p-4 bg-gray-50 rounded-md mb-4 border">
                        <p class="text-xs text-gray-500 mb-2">Deja estos campos en blanco si no deseas cambiar la contraseña actual del barbero.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <!-- Especialidad y Comisión -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="specialty" class="block text-sm font-medium text-gray-700">Especialidad</label>
                            <input type="text" name="specialty" id="specialty" value="{{ old('specialty', $staff->specialty) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700">Porcentaje de Comisión (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', $staff->commission_rate) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="mb-6">
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="is_active" id="is_active" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ old('is_active', $staff->is_active) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('is_active', $staff->is_active) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                            Actualizar Barbero
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>