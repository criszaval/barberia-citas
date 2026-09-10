<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de tu Cita</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">
    
    <!-- Contenedor principal con Alpine.js gestionando el estado y el polling (cada 5 segundos) -->
    <div class="bg-gray-800 border border-gray-700 p-8 rounded-xl shadow-xl max-w-lg w-full mx-4" 
         x-data="{ status: '{{ $appointment->status }}' }" 
         x-init="setInterval(async () => {
             try {
                 let res = await fetch('{{ route('appointments.json', $appointment->token) }}');
                 let data = await res.json();
                 status = data.status;
             } catch (e) {
                 console.error('Error al actualizar el estado', e);
             }
         }, 5000)">
         
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-white">Detalles de tu Cita</h1>
            <!-- El badge cambia de color y texto dinámicamente según Alpine.js -->
            <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider"
                :class="{
                    'bg-green-900 text-green-200': status === 'confirmed',
                    'bg-red-900 text-red-200': status === 'cancelled',
                    'bg-yellow-900 text-yellow-200': status === 'pending'
                }"
                x-text="status === 'confirmed' ? 'Confirmada' : (status === 'cancelled' ? 'Cancelada' : 'Pendiente')">
                {{ ucfirst($appointment->status) }}
            </span>
        </div>

        @if(session('success'))
            <div class="bg-green-800/50 border border-green-600 text-green-200 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-800/50 border border-red-600 text-red-200 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4 text-gray-300 border-t border-b border-gray-700 py-4 my-4">
            <div class="flex justify-between">
                <span class="text-gray-400">Cliente:</span>
                <span class="font-medium text-white">{{ $appointment->guest_name ?? ($appointment->client->name ?? 'N/D') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Servicio:</span>
                <span class="font-medium text-white">{{ $appointment->service->name ?? 'N/D' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Barbero / Personal:</span>
                <span class="font-medium text-white">{{ $appointment->staffProfile->user->name ?? 'N/D' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Fecha:</span>
                <span class="font-medium text-white">{{ $appointment->appointment_date }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Hora de inicio:</span>
                <span class="font-medium text-white">{{ $appointment->start_time }}</span>
            </div>
        </div>

        <!-- Botones de Acción dinámicos basados en el estado reactivo -->
        <div class="space-y-3 mt-6">
            <!-- Editar: Visible únicamente si el estado es 'pending' -->
            <template x-if="status === 'pending'">
                <a href="{{ route('appointments.edit-by-token', $appointment->token) }}" 
                   class="block text-center bg-yellow-600 hover:bg-yellow-500 text-white font-medium py-2.5 px-4 rounded-lg transition">
                    Editar cita
                </a>
            </template>

            <!-- Cancelar: Visible si está 'pending' o 'confirmed' -->
            <template x-if="status === 'pending' || status === 'confirmed'">
                <form action="{{ route('appointments.cancel-by-token', $appointment->token) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar tu cita?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-500 text-white font-medium py-2.5 px-4 rounded-lg transition">
                        Cancelar cita
                    </button>
                </form>
            </template>
        </div>

        <div class="mt-4">
            <a href="{{ route('appointments.create') }}" class="block text-center bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 px-4 rounded-lg transition">
                Reservar otra cita
            </a>
        </div>
    </div>
</body>
</html>