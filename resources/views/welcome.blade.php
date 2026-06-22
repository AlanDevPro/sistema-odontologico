<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información Web para el Consultorio Odontológico LALYS DENT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Estilos adicionales para replicar la imagen exactamente */
        .bg-lalys-red {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .bg-lalys-light {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .hero-overlay {
            background: linear-gradient(to right, rgba(0,0,0,0.4), rgba(0,0,0,0.1));
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">

    {{-- ====== NAVBAR ====== --}}
    <nav class="bg-lalys-red shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-white">
                    <div class="bg-white/20 rounded-full p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C7 2 4 5 4 9c0 2 .5 3.5 1.3 5.3.6 1.4 1 2.8 1 4.4 0 1.3.7 3.3 2.2 3.3.9 0 1.3-.6 1.5-1.6.2-1 .3-2.7 2-2.7s1.8 1.7 2 2.7c.2 1 .6 1.6 1.5 1.6 1.5 0 2.2-2 2.2-3.3 0-1.6.4-3 1-4.4C19.5 12.5 20 11 20 9c0-4-3-7-8-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-2xl leading-none">LALYS</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] opacity-90">Consultorio Dental</div>
                    </div>
                </a>

                {{-- Menú Desktop --}}
                <div class="hidden md:flex items-center gap-8 text-white text-sm font-medium">
                    <a href="#conocenos" class="hover:text-red-100 transition flex items-center gap-1">
                        <span>🏠</span> Conócenos
                    </a>
                    <a href="#sistema" class="hover:text-red-100 transition flex items-center gap-1">
                        <span>🖥️</span> Sistema
                    </a>
                    <a href="#contactenos" class="hover:text-red-100 transition flex items-center gap-1">
                        <span>✉️</span> Contáctenos
                    </a>
                </div>

                {{-- Botones de acción --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-2 bg-white text-red-600 px-5 py-2 rounded-full text-sm font-bold hover:bg-red-50 transition shadow-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Ingresar
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center gap-2 border-2 border-white text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-white hover:text-red-600 transition shadow-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ====== HERO ====== --}}
    <section class="relative">
        <div class="grid md:grid-cols-2">
            {{-- Imagen de fondo (consultorio) --}}
            <div class="h-[500px] md:h-[550px] bg-cover bg-center relative"
                 style="background-image: url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=1200&q=80');">
                <div class="hero-overlay w-full h-full flex items-center justify-center">
                    <div class="text-center text-white p-8">
                        <h2 class="text-4xl md:text-5xl font-bold text-shadow">Sonrisa Saludable</h2>
                        <p class="text-lg md:text-xl mt-2 opacity-90">Tu salud bucal es nuestra prioridad</p>
                    </div>
                </div>
            </div>

            {{-- Lado claro con tarjeta --}}
            <div class="h-[500px] md:h-[550px] bg-lalys-light flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl px-10 py-10 text-center max-w-sm w-full">
                    <div class="mb-4">
                        <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-white font-bold text-3xl">L</span>
                        </div>
                    </div>
                    <h1 class="text-red-600 font-extrabold text-3xl tracking-wide">LALYS</h1>
                    <p class="text-gray-500 text-xs font-semibold tracking-[0.3em] -mt-1 mb-4">CONSULTORIO DENTAL</p>
                    
                    <div class="text-center mb-6">
                        <p class="text-gray-700 font-semibold text-sm">
                            Bienvenido a LALYS Consultorio Dental
                        </p>
                        <p class="text-gray-500 text-xs mt-1">
                            consultoría odontológica LALYS
                        </p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('login') }}"
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-full transition shadow-lg hover:shadow-xl">
                            🔑 Ingresar
                        </a>
                        <a href="{{ route('register') }}"
                           class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-full transition shadow-lg hover:shadow-xl">
                            📝 Registrarse
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====== SECCIÓN "RECUERDOS" ====== --}}
    <section class="relative py-16 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                {{-- Texto --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-1 bg-red-500 rounded-full"></div>
                        <h2 class="text-red-600 font-bold text-2xl">Recuerdos</h2>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Es muy importante acudir al dentista regularmente para mantener una buena 
                        salud bucodental. Aquí te presento algunos recursos para tus cuellos de ingerir:
                    </p>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1">✓</span>
                            <span>Visita al dentista cada 6 meses para prevención</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1">✓</span>
                            <span>Mantén una buena higiene bucal diaria</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1">✓</span>
                            <span>Evita alimentos azucarados y ácidos</span>
                        </li>
                    </ul>
                </div>

                {{-- Imagen --}}
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="w-64 h-64 rounded-full overflow-hidden border-4 border-red-200 shadow-xl">
                            <img src="https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=600&q=80" 
                                 alt="Odontóloga" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-lg">
                            <span class="text-2xl">🦷</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====== NUESTROS TRATAMIENTOS ====== --}}
    <section class="bg-gradient-to-b from-orange-500 to-orange-400 py-16 px-4">
        <h2 class="text-center text-white font-bold text-3xl mb-12">Nuestros Tratamientos</h2>

        <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-center gap-6 md:gap-8">

            {{-- Tarjetas de tratamientos --}}
            @php
                $tratamientos = [
                    [
                        'nombre' => 'Odontopediatria',
                        'img' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=400&q=80',
                        'color' => 'from-blue-400 to-blue-500'
                    ],
                    [
                        'nombre' => 'Rehabilitación Oral',
                        'img' => 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?w=400&q=80',
                        'color' => 'from-green-400 to-green-500'
                    ],
                    [
                        'nombre' => 'Cirugía Oral',
                        'img' => 'https://images.unsplash.com/photo-1609840114035-3c981b782dfe?w=400&q=80',
                        'color' => 'from-purple-400 to-purple-500'
                    ],
                ];
            @endphp

            @foreach ($tratamientos as $t)
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-56 text-center transform hover:scale-105 transition duration-300">
                    <div class="relative">
                        <div class="h-32 w-32 mx-auto rounded-full overflow-hidden border-4 border-orange-300 shadow-lg mb-4">
                            <img src="{{ $t['img'] }}" alt="{{ $t['nombre'] }}" class="h-full w-full object-cover">
                        </div>
                        <div class="absolute -top-2 -right-2 bg-gradient-to-r {{ $t['color'] }} text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                            Ver foto
                        </div>
                    </div>
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wide">{{ $t['nombre'] }}</p>
                    <div class="w-12 h-1 bg-orange-400 mx-auto mt-2 rounded-full"></div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ====== FOOTER ====== --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="text-white font-bold text-lg mb-2">LALYS</h3>
                    <p class="text-sm">Consultorio Dental</p>
                    <p class="text-xs text-gray-500 mt-1">Cuidando tu sonrisa desde 2020</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-2">Contacto</h4>
                    <p class="text-sm">📞 +57 300 123 4567</p>
                    <p class="text-sm">📧 info@lalys.dent</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-2">Horario</h4>
                    <p class="text-sm">Lunes a Viernes: 8:00 - 18:00</p>
                    <p class="text-sm">Sábados: 8:00 - 13:00</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-6 pt-4 text-center text-xs text-gray-500">
                © {{ date('Y') }} LALYS Consultorio Dental. Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>