<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 border border-gray-700 p-8 rounded-xl shadow-xl max-w-lg w-full mx-4">
        <h1 class="text-2xl font-bold text-white mb-6">Modificar tu Cita</h1>

        @if($errors->any())
            <div class="bg-red-800/50 border border-red-600 text-red-200 px-4 py-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.update-by-token', $appointment->token) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Servicio</label>
                <select name="service_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} ({{ $service->duration_minutes }} min)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Barbero / Personal</label>
                <select name="staff_profile_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white">
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ old('staff_profile_id', $appointment->staff_profile_id) == $staff->id ? 'selected' : '' }}>
                            {{ $staff->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Fecha</label>
                <input type="date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Hora de inicio</label>
                <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white">
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-lg transition text-center">
                    Guardar Cambios
                </button>
                <a href="{{ route('appointments.show', $appointment->token) }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-medium py-2.5 rounded-lg transition text-center">
                    Volver
                </a>
            </div>
        </form>
    </div>
</body>
</html>