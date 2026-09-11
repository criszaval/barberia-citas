<x-guest-layout>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
    >

    <style>
        .flatpickr-calendar {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            font-family: inherit;
        }

        .flatpickr-months,
        .flatpickr-months .flatpickr-month {
            background: #4f46e5;
            color: white;
        }

        .flatpickr-current-month {
            color: white;
            font-weight: 600;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: white;
            background: #4f46e5;
            font-weight: 600;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            fill: white;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            fill: #e0e7ff;
        }

        .flatpickr-weekdays {
            background: #f9fafb;
        }

        .flatpickr-weekday {
            color: #4b5563;
            font-weight: 600;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #4f46e5;
            border-color: #4f46e5;
            color: white;
        }

        .flatpickr-day.today {
            border-color: #4f46e5;
        }

        .flatpickr-day:hover {
            background: #eef2ff;
            border-color: #eef2ff;
        }

        #appointment_date {
            cursor: pointer;
        }

        .flatpickr-input[readonly] {
            cursor: pointer;
            background-color: white;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>


    <!-- HEADER -->

    <header class="w-full bg-white border-b border-gray-200 shadow-sm py-4 px-6">

        <div class="max-w-4xl mx-auto flex justify-between items-center">

            <a
                href="{{ route('appointments.create') }}"
                class="text-xl font-bold text-gray-800 hover:text-indigo-600 transition"
            >
                Barbería
            </a>

            <nav class="flex items-center space-x-3">

                @if (Route::has('login'))

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="text-sm font-semibold text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition"
                        >
                            Ir al Dashboard
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md transition"
                        >
                            Iniciar Sesión
                        </a>

                        @if (Route::has('register'))

                            <a
                                href="{{ route('register') }}"
                                class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md shadow-sm transition"
                            >
                                Registrarse
                            </a>

                        @endif

                    @endauth

                @endif

            </nav>

        </div>

    </header>


    <!-- CONTENIDO -->

    <div class="max-w-2xl mx-auto my-8 p-6 bg-white shadow-md rounded-lg">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Agendar Cita
        </h2>


        <!-- MENSAJE DE ÉXITO -->

        @if (session('success'))

            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>

        @endif


        <!-- ERRORES -->

        @if ($errors->any())

            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">

                <ul class="list-disc pl-5">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- FORMULARIO -->

        <form
            action="{{ route('appointments.store') }}"
            method="POST"
            x-data="{ createAccount: false }"
        >

            @csrf


            <!-- SECCIÓN 1 -->

            <h3 class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-3">
                1. Servicio y Especialista
            </h3>


            <!-- SERVICIO -->

            <div class="mb-4">

                <label
                    for="service_id"
                    class="block font-medium text-sm text-gray-700"
                >
                    Selecciona un Servicio
                </label>

                <select
                    name="service_id"
                    id="service_id"
                    required
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        -- Selecciona un Servicio --
                    </option>

                    @foreach ($services as $service)

                        <option
                            value="{{ $service->id }}"
                            {{ old('service_id') == $service->id ? 'selected' : '' }}
                        >
                            {{ $service->name }}
                            (${{ number_format($service->price, 2) }}
                            - {{ $service->duration_minutes }} min)
                        </option>

                    @endforeach

                </select>

            </div>


            <!-- PROFESIONAL -->

            <div class="mb-4">

                <label
                    for="staff_profile_id"
                    class="block font-medium text-sm text-gray-700"
                >
                    Selecciona el Profesional
                </label>

                <select
                    name="staff_profile_id"
                    id="staff_profile_id"
                    required
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        -- Selecciona un Profesional --
                    </option>

                    @foreach ($staffMembers as $staff)

                        <option
                            value="{{ $staff->id }}"
                            {{ old('staff_profile_id') == $staff->id ? 'selected' : '' }}
                        >
                            {{ $staff->user->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <!-- FECHA Y HORA -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">


                <!-- FECHA -->

                <div>

                    <label
                        for="appointment_date"
                        class="block font-medium text-sm text-gray-700"
                    >
                        Fecha
                    </label>

                    <input
                        type="text"
                        name="appointment_date"
                        id="appointment_date"
                        value="{{ old('appointment_date') }}"
                        placeholder="Selecciona una fecha"
                        autocomplete="off"
                        required
                        disabled
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >

                    <p
                        id="dateHelp"
                        class="mt-1 text-xs text-gray-500"
                    >
                        Selecciona un profesional.
                    </p>

                </div>


                <!-- HORA -->

                <div>

                    <label
                        for="start_time"
                        class="block font-medium text-sm text-gray-700"
                    >
                        Hora
                    </label>

                    <select
                        name="start_time"
                        id="start_time"
                        required
                        disabled
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >

                        <option value="">
                            Selecciona una hora
                        </option>

                    </select>

                    <p
                        id="timeHelp"
                        class="mt-1 text-xs text-gray-500"
                    >
                        Primero selecciona servicio, profesional y fecha.
                    </p>

                </div>

            </div>


            <hr class="my-6 border-gray-200">


            <!-- SECCIÓN 2 -->

            <h3 class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-3">
                2. Datos del Cliente
            </h3>


            @auth

                <div class="p-4 bg-gray-50 border rounded-md mb-4">

                    <p class="text-sm text-gray-600">

                        Reservando como:

                        <strong>
                            {{ auth()->user()->name }}
                        </strong>

                        ({{ auth()->user()->email }})

                    </p>

                    <input
                        type="hidden"
                        name="guest_name"
                        value="{{ auth()->user()->name }}"
                    >

                    <input
                        type="hidden"
                        name="guest_email"
                        value="{{ auth()->user()->email }}"
                    >

                    <input
                        type="hidden"
                        name="guest_phone"
                        value="{{ auth()->user()->phone ?? 'N/A' }}"
                    >

                </div>

            @else

                <!-- NOMBRE -->

                <div class="mb-4">

                    <label
                        for="guest_name"
                        class="block font-medium text-sm text-gray-700"
                    >
                        Nombre Completo
                    </label>

                    <input
                        type="text"
                        name="guest_name"
                        id="guest_name"
                        value="{{ old('guest_name') }}"
                        placeholder="Ej. Juan Pérez"
                        required
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                <!-- EMAIL Y TELÉFONO -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                    <div>

                        <label
                            for="guest_email"
                            class="block font-medium text-sm text-gray-700"
                        >
                            Correo Electrónico
                        </label>

                        <input
                            type="email"
                            name="guest_email"
                            id="guest_email"
                            value="{{ old('guest_email') }}"
                            placeholder="correo@ejemplo.com"
                            required
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>


                    <div>

                        <label
                            for="guest_phone"
                            class="block font-medium text-sm text-gray-700"
                        >
                            Teléfono / WhatsApp
                        </label>

                        <input
                            type="tel"
                            name="guest_phone"
                            id="guest_phone"
                            value="{{ old('guest_phone') }}"
                            placeholder="70000000"
                            maxlength="12"
                            required
                            pattern="^[0-9]{4}[- ]?[0-9]{4}$"
                            oninput="this.value = this.value.replace(/[^0-9-]/g, '');"
                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                </div>


                <!-- CREAR CUENTA -->

                <div class="mb-4">

                    <label class="inline-flex items-center">

                        <input
                            type="checkbox"
                            name="create_account"
                            value="1"
                            x-model="createAccount"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >

                        <span class="ml-2 text-sm text-gray-600">
                            ¿Deseas crear una cuenta para gestionar futuras citas?
                        </span>

                    </label>

                </div>


                <!-- CONTRASEÑA -->

                <div
                    class="mb-4"
                    x-show="createAccount"
                    x-cloak
                >

                    <label
                        for="password"
                        class="block font-medium text-sm text-gray-700"
                    >
                        Crea tu Contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        minlength="8"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Mínimo 8 caracteres"
                    >

                </div>

            @endauth


            <!-- NOTAS -->

            <div class="mb-6">

                <label
                    for="notes"
                    class="block font-medium text-sm text-gray-700"
                >
                    Notas / Preferencias (Opcional)
                </label>

                <textarea
                    name="notes"
                    id="notes"
                    rows="2"
                    placeholder="Detalles extra sobre tu solicitud..."
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('notes') }}</textarea>

            </div>


            <!-- BOTÓN -->

            <div class="mt-6">

                <button
                    type="submit"
                    style="background-color: #4f46e5; color: #ffffff;"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Confirmar Reserva
                </button>

            </div>

        </form>

    </div>


    <!-- FLATPICKR -->

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const staffSelect =
                document.getElementById('staff_profile_id');

            const serviceSelect =
                document.getElementById('service_id');

            const dateInput =
                document.getElementById('appointment_date');

            const timeInput =
                document.getElementById('start_time');

            const dateHelp =
                document.getElementById('dateHelp');

            const timeHelp =
                document.getElementById('timeHelp');


            let workingDays = [];

            let availableTimes = [];


            /*
            |--------------------------------------------------------------------------
            | FUNCIONES PARA HABILITAR / DESHABILITAR FECHA
            |--------------------------------------------------------------------------
            */

            function enableDateInput() {

                dateInput.disabled = false;

                if (datePicker.altInput) {
                    datePicker.altInput.disabled = false;
                }

            }


            function disableDateInput() {

                dateInput.disabled = true;

                if (datePicker.altInput) {
                    datePicker.altInput.disabled = true;
                }

            }


            /*
            |--------------------------------------------------------------------------
            | FUNCIONES PARA HABILITAR / DESHABILITAR HORA
            |--------------------------------------------------------------------------
            */

            function enableTimeInput() {

                timeInput.disabled = false;

            }


            function disableTimeInput() {

                timeInput.disabled = true;

                timeInput.value = '';

            }


            /*
            |--------------------------------------------------------------------------
            | CALENDARIO
            |--------------------------------------------------------------------------
            */

            const datePicker = flatpickr(
                dateInput,
                {

                    locale: "es",

                    dateFormat: "Y-m-d",

                    altInput: true,

                    altFormat: "d/m/Y",

                    minDate: "today",

                    closeOnSelect: true,

                    allowInput: false,

                    clickOpens: true,

                    disableMobile: true,

                    disable: [

                        function (date) {

                            if (workingDays.length === 0) {

                                return true;

                            }

                            return !workingDays.includes(
                                date.getDay()
                            );

                        }

                    ],

                    onChange: function (
                        selectedDates,
                        dateStr
                    ) {

                        availableTimes = [];

                        timeInput.innerHTML = `
                            <option value="">
                                Cargando horarios...
                            </option>
                        `;

                        disableTimeInput();

                        if (!dateStr) {

                            timeHelp.textContent =
                                'Seleccione una fecha para consultar los horarios.';

                            return;

                        }

                        loadAvailableTimes();

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ASEGURAR QUE EL CAMPO VISUAL DE FLATPICKR ESTÉ BLOQUEADO AL INICIO
            |--------------------------------------------------------------------------
            */

            if (datePicker.altInput) {

                datePicker.altInput.disabled = true;

            }


            /*
            |--------------------------------------------------------------------------
            | CARGAR DÍAS LABORALES DEL PROFESIONAL
            |--------------------------------------------------------------------------
            */

            async function loadWorkingDays() {

                const staffId =
                    staffSelect.value;


                workingDays = [];

                availableTimes = [];


                datePicker.clear();


                timeInput.innerHTML = `
                    <option value="">
                        Primero selecciona una fecha
                    </option>
                `;


                disableDateInput();

                disableTimeInput();


                dateHelp.textContent =
                    'Cargando días disponibles...';


                timeHelp.textContent =
                    'Primero selecciona servicio, profesional y fecha.';


                if (!staffId) {

                    datePicker.set(
                        'disable',
                        [
                            function () {
                                return true;
                            }
                        ]
                    );


                    dateHelp.textContent =
                        'Primero selecciona un profesional.';

                    return;

                }


                try {

                    const response = await fetch(

                        "{{ url('/reservar/disponibilidad') }}/"
                        + staffId,

                        {

                            method: 'GET',

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            }

                        }

                    );


                    if (!response.ok) {

                        throw new Error(
                            'No se pudo consultar la disponibilidad.'
                        );

                    }


                    const data =
                        await response.json();


                    console.log(
                        'Disponibilidad recibida:',
                        data
                    );


                    workingDays =
                        Array.isArray(
                            data.working_days
                        )
                            ? data.working_days.map(Number)
                            : [];


                    console.log(
                        'Días laborales:',
                        workingDays
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ACTUALIZAR DÍAS PERMITIDOS
                    |--------------------------------------------------------------------------
                    */

                    datePicker.set(
                        'disable',
                        [

                            function (date) {

                                return !workingDays.includes(
                                    date.getDay()
                                );

                            }

                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | HABILITAR CALENDARIO
                    |--------------------------------------------------------------------------
                    */

                    if (workingDays.length > 0) {

                        enableDateInput();


                        dateHelp.textContent =
                            'Seleccione una fecha en la que el profesional trabaja.';


                    } else {

                        disableDateInput();


                        dateHelp.textContent =
                            'Este profesional no tiene días laborales configurados.';

                    }


                } catch (error) {

                    console.error(
                        'Error de disponibilidad:',
                        error
                    );


                    workingDays = [];


                    disableDateInput();


                    datePicker.set(
                        'disable',
                        [
                            function () {
                                return true;
                            }
                        ]
                    );


                    dateHelp.textContent =
                        'No se pudo cargar el horario del profesional.';


                    alert(
                        'No se pudo cargar la disponibilidad del profesional.'
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CARGAR HORARIOS DISPONIBLES
            |--------------------------------------------------------------------------
            */

            async function loadAvailableTimes() {

                const staffId =
                    staffSelect.value;

                const serviceId =
                    serviceSelect.value;

                const date =
                    dateInput.value;


                availableTimes = [];


                disableTimeInput();


                timeInput.innerHTML = `
                    <option value="">
                        Cargando horarios...
                    </option>
                `;


                timeHelp.textContent =
                    'Cargando horarios disponibles...';


                if (
                    !staffId ||
                    !serviceId ||
                    !date
                ) {

                    timeInput.innerHTML = `
                        <option value="">
                            Selecciona servicio, profesional y fecha
                        </option>
                    `;


                    timeHelp.textContent =
                        'Seleccione servicio, profesional y fecha.';

                    return;

                }


                try {

                    const url =

                        "{{ route('appointments.availableTimes') }}"
                        + "?staff_profile_id="
                        + encodeURIComponent(staffId)
                        + "&service_id="
                        + encodeURIComponent(serviceId)
                        + "&appointment_date="
                        + encodeURIComponent(date);


                    console.log(
                        'Consultando horarios:',
                        url
                    );


                    const response = await fetch(

                        url,

                        {

                            method: 'GET',

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            }

                        }

                    );


                    if (!response.ok) {

                        throw new Error(
                            'No se pudieron cargar los horarios.'
                        );

                    }


                    const data =
                        await response.json();


                    console.log(
                        'Horarios recibidos:',
                        data
                    );


                    availableTimes =
                        Array.isArray(
                            data.available_times
                        )
                            ? data.available_times
                            : [];


                    /*
                    |--------------------------------------------------------------------------
                    | LIMPIAR SELECT
                    |--------------------------------------------------------------------------
                    */

                    timeInput.innerHTML = '';


                    /*
                    |--------------------------------------------------------------------------
                    | NO HAY HORARIOS
                    |--------------------------------------------------------------------------
                    */

                    if (availableTimes.length === 0) {

                        timeInput.innerHTML = `
                            <option value="">
                                No hay horarios disponibles
                            </option>
                        `;


                        disableTimeInput();


                        timeHelp.textContent =
                            'No hay horarios disponibles para esta fecha.';

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AGREGAR HORARIOS
                    |--------------------------------------------------------------------------
                    */

                    const defaultOption =
                        document.createElement('option');

                    defaultOption.value = '';

                    defaultOption.textContent =
                        'Selecciona una hora';

                    timeInput.appendChild(
                        defaultOption
                    );


                    availableTimes.forEach(
                        function (time) {

                            const option =
                                document.createElement('option');

                            option.value = time;

                            option.textContent =
                                formatTime(time);

                            timeInput.appendChild(
                                option
                            );

                        }
                    );


                    enableTimeInput();


                    timeHelp.textContent =
                        'Seleccione uno de los horarios disponibles.';


                } catch (error) {

                    console.error(
                        'Error cargando horarios:',
                        error
                    );


                    availableTimes = [];


                    timeInput.innerHTML = `
                        <option value="">
                            No se pudieron cargar los horarios
                        </option>
                    `;


                    disableTimeInput();


                    timeHelp.textContent =
                        'No se pudieron cargar los horarios disponibles.';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | FORMATEAR HORA
            |--------------------------------------------------------------------------
            */

            function formatTime(time) {

                if (!time) {
                    return '';
                }


                const parts =
                    time.split(':');


                const hour =
                    parseInt(parts[0], 10);

                const minute =
                    parts[1];


                const period =
                    hour >= 12
                        ? 'PM'
                        : 'AM';


                let displayHour =
                    hour % 12;


                if (displayHour === 0) {
                    displayHour = 12;
                }


                return (
                    displayHour +
                    ':' +
                    minute +
                    ' ' +
                    period
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CAMBIO DE PROFESIONAL
            |--------------------------------------------------------------------------
            */

            staffSelect.addEventListener(
                'change',
                function () {

                    console.log(
                        'Profesional seleccionado:',
                        this.value
                    );


                    loadWorkingDays();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CAMBIO DE SERVICIO
            |--------------------------------------------------------------------------
            */

            serviceSelect.addEventListener(
                'change',
                function () {

                    console.log(
                        'Servicio seleccionado:',
                        this.value
                    );


                    /*
                    | Si ya existe una fecha seleccionada,
                    | actualizar los horarios.
                    */

                    if (
                        dateInput.value &&
                        staffSelect.value
                    ) {

                        loadAvailableTimes();

                    } else {

                        timeInput.innerHTML = `
                            <option value="">
                                Primero selecciona una fecha
                            </option>
                        `;


                        disableTimeInput();


                        timeHelp.textContent =
                            'Primero selecciona servicio, profesional y fecha.';

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CARGA AUTOMÁTICA
            |--------------------------------------------------------------------------
            */

            console.log(
                'Servicio inicial:',
                serviceSelect.value
            );


            console.log(
                'Profesional inicial:',
                staffSelect.value
            );


            /*
            | Este era uno de los problemas principales.
            |
            | Si Mateo ya viene seleccionado desde PHP,
            | el evento change nunca se dispara automáticamente.
            |
            | Por eso hacemos la consulta manualmente al cargar.
            */

            if (staffSelect.value) {

                loadWorkingDays();

            }


        });

    </script>

</x-guest-layout>