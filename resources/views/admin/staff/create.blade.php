<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Barbero') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                {{-- Errores de validación --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.staff.store') }}" method="POST">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Correo Electrónico y Teléfono -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono / WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="70000000" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Contraseña y Confirmación -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña de Acceso</label>
                            <input type="password" name="password" id="password" required minlength="8" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <!-- Especialidad y Comisión -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="specialty" class="block text-sm font-medium text-gray-700">Especialidad (Opcional)</label>
                            <input type="text" name="specialty" id="specialty" value="{{ old('specialty') }}" placeholder="Ej. Cortes degradados, Arreglo de barba" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700">Porcentaje de Comisión (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', 0) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Estado Activo/Inactivo -->
                    <div class="mb-6">
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="is_active" id="is_active" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Activo (Disponible para reservas)</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm">
                            Guardar Barbero
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>