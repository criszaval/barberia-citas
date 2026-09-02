<x-guest-layout>
    <div class="max-w-2xl mx-auto my-8 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Agendar Cita</h2>

        {{-- Alerta de éxito --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST" x-data="{ createAccount: false }">
            @csrf

            <!-- 1. Servicio y Especialista -->
            <h3 class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-3">1. Servicio y Especialista</h3>

            <!-- Selección de Servicio -->
            <div class="mb-4">
                <label for="service_id" class="block font-medium text-sm text-gray-700">Selecciona un Servicio</label>
                <select name="service_id" id="service_id" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Selecciona un Servicio --</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} (${{ number_format($service->price, 2) }} - {{ $service->duration_minutes }} min)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Selección de Barbero / Empleado -->
            <div class="mb-4">
                <label for="staff_profile_id" class="block font-medium text-sm text-gray-700">Selecciona el Profesional</label>
                <select name="staff_profile_id" id="staff_profile_id" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Selecciona un Profesional --</option>
                    @foreach ($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ old('staff_profile_id') == $staff->id ? 'selected' : '' }}>
                            {{ $staff->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha y Hora -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="appointment_date" class="block font-medium text-sm text-gray-700">Fecha</label>
                    <input type="date" min="{{ date('Y-m-d') }}" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="start_time" class="block font-medium text-sm text-gray-700">Hora</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <!-- 2. Datos del Cliente -->
            <h3 class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-3">2. Datos del Cliente</h3>

            @auth
                <!-- Si el usuario ya está autenticado -->
                <div class="p-4 bg-gray-50 border rounded-md mb-4">
                    <p class="text-sm text-gray-600">Reservando como: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>
                    <input type="hidden" name="guest_name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="guest_email" value="{{ auth()->user()->email }}">
                    <input type="hidden" name="guest_phone" value="{{ auth()->user()->phone ?? 'N/A' }}">
                </div>
            @else
                <!-- Si el cliente es invitado (se cambió name="name" por name="guest_name", etc.) -->
                <div class="mb-4">
                    <label for="guest_name" class="block font-medium text-sm text-gray-700">Nombre Completo</label>
                    <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" placeholder="Ej. Juan Pérez" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="guest_email" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
                        <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}" placeholder="correo@ejemplo.com" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="guest_phone" class="block font-medium text-sm text-gray-700">Teléfono / WhatsApp</label>
                        <input type="tel" name="guest_phone" id="guest_phone" value="{{ old('guest_phone') }}" placeholder="70000000" maxlength="12" required pattern="^[0-9]{4}[- ]?[0-9]{4}$" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Casilla para Crear Cuenta (Opcional) -->
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="create_account" value="1" x-model="createAccount" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">¿Deseas crear una cuenta para gestionar futuras citas?</span>
                    </label>
                </div>

                <!-- Campo de contraseña -->
                <div class="mb-4" x-show="createAccount" x-cloak>
                    <label for="password" class="block font-medium text-sm text-gray-700">Crea tu Contraseña</label>
                    <input type="password" name="password" id="password" minlength="8" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Mínimo 8 caracteres">
                </div>
            @endauth

            <!-- Notas Adicionales -->
            <div class="mb-6">
                <label for="notes" class="block font-medium text-sm text-gray-700">Notas / Preferencias (Opcional)</label>
                <textarea name="notes" id="notes" rows="2" placeholder="Detalles extra sobre tu solicitud..." class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>

            <!-- Botón de Envío -->
            <div class="mt-6">
                <button type="submit" style="background-color: #4f46e5; color: #ffffff;" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>