<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: true, profileMenuOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel principal' }} - LALYS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-700">
<div class="min-h-screen flex flex-col">

    @php
        // Centralizamos los datos del doctor logueado desde su relación con el usuario autenticado
        $userActive = Auth::user();
        $doctorActive = $userActive ? $userActive->doctor : null;
        
        // Datos de contingencia en caso de que falle la carga relacional
        $nombreDoc = $doctorActive ? "{$doctorActive->nombres} {$doctorActive->apellidos}" : ($userActive->name ?? 'Personal Médico');
        $especialidadDoc = $doctorActive->especialidad ?? 'Odontólogo General';
        $ciDoc = $doctorActive->ci_dni ?? 'S/N';
        $emailDoc = $userActive->email ?? 'contacto@lalysdent.com';
        
        // Obtener iniciales del nombre
        $iniciales = '';
        if ($nombreDoc && $nombreDoc !== 'Personal Médico') {
            $nombresArray = explode(' ', $nombreDoc);
            if (count($nombresArray) >= 2) {
                $iniciales = strtoupper(substr($nombresArray[0], 0, 1) . substr(end($nombresArray), 0, 1));
            } else {
                $iniciales = strtoupper(substr($nombreDoc, 0, 2));
            }
        } else {
            $iniciales = 'PM'; // Personal Médico
        }
        
        // Determinar el rol del usuario de manera segura
        $rolUsuario = 'Especialista Médico';
        if ($userActive) {
            // Verificar si el usuario tiene el método hasRole (Spatie)
            if (method_exists($userActive, 'hasRole')) {
                $rolUsuario = $userActive->hasRole('admin') ? 'Administrador' : 'Especialista Médico';
            } 
            // Verificar si tiene el método isAdmin (alternativa)
            elseif (method_exists($userActive, 'isAdmin')) {
                $rolUsuario = $userActive->isAdmin() ? 'Administrador' : 'Especialista Médico';
            }
            // Verificar si tiene columna de rol directamente
            elseif (isset($userActive->rol) || isset($userActive->role)) {
                $rol = $userActive->rol ?? $userActive->role ?? '';
                $rolUsuario = strtolower($rol) === 'admin' ? 'Administrador' : 'Especialista Médico';
            }
            // Verificar si tiene columna is_admin
            elseif (isset($userActive->is_admin)) {
                $rolUsuario = $userActive->is_admin ? 'Administrador' : 'Especialista Médico';
            }
        }
    @endphp

    {{-- ====== TOPBAR / HEADER ====== --}}
    <header class="bg-gradient-to-r from-red-600 to-red-500 h-16 flex items-center justify-between px-6 shadow-md z-30">
        
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-red-100 transition focus:outline-none" title="Alternar menú lateral">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold tracking-wide text-lg select-none">
                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C7 2 4 5 4 9c0 2 .5 3.5 1.3 5.3.6 1.4 1 2.8 1 4.4 0 1.3.7 3.3 2.2 3.3.9 0 1.3-.6 1.5-1.6.2-1 .3-2.7 2-2.7s1.8 1.7 2 2.7c.2 1 .6 1.6 1.5 1.6 1.5 0 2.2-2 2.2-3.3 0-1.6.4-3 1-4.4C19.5 12.5 20 11 20 9c0-4-3-7-8-7z"/>
                </svg>
                <span>LALYS <span class="font-light text-red-100 text-sm">Consultorio Dental</span></span>
            </a>
        </div>

        <div class="flex items-center gap-4 relative">
            <div class="hidden md:flex flex-col text-right">
                <span class="text-white font-semibold text-sm leading-tight">{{ $nombreDoc }}</span>
                <span class="text-xs text-red-100 font-medium opacity-90">{{ $rolUsuario }}</span>
            </div>

            <div class="relative">
                <button @click="profileMenuOpen = !profileMenuOpen" @click.away="profileMenuOpen = false" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-600 focus:ring-white transition">
                    <!-- Avatar con iniciales -->
                    <div class="h-10 w-10 rounded-full flex items-center justify-center bg-white text-red-600 font-bold text-sm border-2 border-white/40 shadow-sm">
                        {{ $iniciales }}
                    </div>
                </button>

                <div x-show="profileMenuOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-40" 
                     style="display: none;">
                    
                    <div class="px-4 py-3 border-b border-gray-100 md:hidden">
                        <p class="text-sm font-semibold text-gray-800">{{ $nombreDoc }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $emailDoc }}</p>
                    </div>

                    <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Ver Perfil
                    </a>
                    
                    <hr class="border-gray-100">

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}" 
                           @click.preventDefault="$root.submit();" 
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium transition">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Cerrar Sesión
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">

        {{-- ====== SIDEBAR / MENÚ LATERAL ====== --}}
        <aside x-show="sidebarOpen"
               class="w-66 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col justify-between overflow-y-auto"
               x-transition:enter="transition ease-in-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            
            <div>
                <div class="flex flex-col items-center pt-6 pb-5 px-4 border-b border-gray-100 bg-gray-50/50">
                    <!-- Avatar con iniciales en el sidebar -->
                    <div class="h-20 w-20 rounded-full flex items-center justify-center bg-gradient-to-br from-red-500 to-red-600 text-white font-bold text-2xl border-4 border-red-100 shadow-sm mb-2.5">
                        {{ $iniciales }}
                    </div>
                    
                    <p class="font-bold text-gray-800 text-base tracking-tight text-center leading-tight">{{ $nombreDoc }}</p>
                    <p class="text-xs font-semibold text-red-500 mb-1">{{ $especialidadDoc }}</p>
                    <p class="text-xs text-gray-400 truncate max-w-full px-2 mb-2">{{ $emailDoc }}</p>
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-700 shadow-inner">
                        🪪 CI: {{ $ciDoc }}
                    </span>
                </div>
            
                <nav class="py-3 px-3 space-y-1">
                    @php
                        $menu = [
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>', 'label' => 'Dashboard principal', 'route' => 'dashboard'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>', 'label' => 'Lista de Pacientes', 'route' => 'pacientes.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>', 'label' => 'Folders Clínicos', 'route' => 'folders.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>', 'label' => 'Control de Citas', 'route' => 'citas.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>', 'label' => 'Nomenclatura Catálogo', 'route' => 'tratamientos.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>', 'label' => 'Caja y Pagos', 'route' => 'pagos.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7h7m-7 0a5 5 0 11-10 0 5 5 0 0110 0z"/></svg>', 'label' => 'Mis Asistentes', 'route' => 'asistentes.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011 1v2a1 1 0 01-1 1m0-4h3m-3 4h3m4-2h2a1 1 0 00.793-.391l2.583-3.443A1 1 0 0022 12v-2a1 1 0 00-1-1h-1.25M13 13h4.75V9H13v4z"/></svg>', 'label' => 'Proveedores', 'route' => 'proveedores.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>', 'label' => 'Almacén Suministros', 'route' => 'suministros.index'],
                            ['icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>', 'label' => 'Historial de Compras', 'route' => 'compras.index'],
                        ];
                    @endphp
            
                    @foreach ($menu as $item)
                        @php
                            $hasRoute = Route::has($item['route']);
                            $url = $hasRoute ? route($item['route']) : '#';
                            $isActive = $hasRoute ? request()->routeIs($item['route']) : false;
                        @endphp
            
                        <a href="{{ $url }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 group
                                  {{ $isActive ? 'text-red-600 bg-red-50/80 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <span class="{{ $isActive ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-600' }} transition">
                                {!! $item['icon'] !!}
                            </span>
                            <span>{{ $item['label'] }}</span>
                            @if($isActive)
                                <span class="ml-auto w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Métricas acumuladas base inferiores del Sidebar --}}
            <div class="p-4 bg-gray-50/70 border-t border-gray-100">
                @php
                    try {
                        $stats = DB::selectOne("
                            SELECT 
                                (SELECT COUNT(*) FROM PACIENTE) as total_pacientes,
                                (SELECT COUNT(*) FROM CITA WHERE estado != 'Cancelada') as citas_activas,
                                (SELECT COUNT(*) FROM DOCTOR WHERE estado = 1) as doctores_activos
                            FROM DUAL
                        ");
                    } catch (\Exception $e) {
                        $stats = null;
                    }
                @endphp
                
            </div>
        </aside>

        {{-- ====== MAIN SLOTTING CONTENT ====== --}}
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Sincronización Responsiva del Sidebar por medio de Breakpoints --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth < 1024) {
            const alpineEl = document.querySelector('[x-data]');
            if (alpineEl && alpineEl.__x) {
                alpineEl.__x.$data.sidebarOpen = false;
            }
        }
    });
</script>

@livewireScripts
</body>
</html>