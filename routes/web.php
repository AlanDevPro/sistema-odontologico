<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard; // Importado para resolver la ruta adaptada a Livewire
use App\Livewire\GestionPacientes;
use App\Livewire\Pacientes\GestionFolders;
use App\Livewire\Citas\GestionCitas;
use App\Livewire\Tratamientos\GestionTratamientos;
use App\Livewire\Finanzas\GestionPagos;
use App\Livewire\Personal\GestionAsistentes;
use App\Livewire\Inventario\GestionProveedores;
use App\Livewire\Inventario\GestionSuministros;
use App\Livewire\Inventario\GestionCompras;

Route::get('/', function () {
    return view('welcome');
});

// Grupo de Rutas Protegidas por Autenticación de Jetstream + Filtro Médico para Oracle
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'doctor' // Se añade globalmente al grupo para proteger todas las vistas clínicas del consultorio
])->group(function () {

    // ===================== DASHBOARD =====================
    // Modificado para resolver mediante el componente controlador de Livewire
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ===================== MÓDULO PACIENTES =====================
    // Ruta principal del módulo de pacientes
    Route::get('/lista-de-pacientes', function () {
        return view('pacientes.index');
    })->name('pacientes.index');

    // Rutas adicionales para pacientes (si necesitas rutas específicas)
    // Route::get('/pacientes/crear', GestionPacientes::class)->name('pacientes.create');
    // Route::get('/pacientes/{id}/editar', GestionPacientes::class)->name('pacientes.edit');

    // ===================== MÓDULO FOLDERS =====================
    Route::get('/folders', function () {
        return view('folders.index');
    })->name('folders.index');
    // Route::get('/folders/crear', GestionFolders::class)->name('folders.create');
    // Route::get('/folders/{id}/editar', GestionFolders::class)->name('folders.edit');

    // ===================== MÓDULO CITAS =====================
    Route::get('/citas', function () {
        return view('citas.index');
    })->name('citas.index');
    // Route::get('/citas/crear', GestionCitas::class)->name('citas.create');
    // Route::get('/citas/{id}/editar', GestionCitas::class)->name('citas.edit');

    // ===================== MÓDULO TRATAMIENTOS =====================
    Route::get('/tratamientos', function () {
        return view('tratamientos.index');
    })->name('tratamientos.index');
    // Route::get('/tratamientos/crear', GestionTratamientos::class)->name('tratamientos.create');
    // Route::get('/tratamientos/{id}/editar', GestionTratamientos::class)->name('tratamientos.edit');

    // ===================== MÓDULO FINANZAS / PAGOS =====================
    Route::get('/pagos', function () {
        return view('pagos.index');
    })->name('pagos.index');
    // Route::get('/pagos/crear', GestionPagos::class)->name('pagos.create');
    // Route::get('/pagos/{id}/editar', GestionPagos::class)->name('pagos.edit');

    // ===================== MÓDULO PERSONAL / ASISTENTES =====================
    Route::get('/asistentes', function () {
        return view('asistentes.index');
    })->name('asistentes.index');
    // Route::get('/asistentes/crear', GestionAsistentes::class)->name('asistentes.create');
    // Route::get('/asistentes/{id}/editar', GestionAsistentes::class)->name('asistentes.edit');

    // ===================== MÓDULO INVENTARIO =====================
    // Proveedores
    Route::get('/proveedores', function () {
        return view('inventario.proveedores');
    })->name('proveedores.index');
    // Route::get('/proveedores/crear', GestionProveedores::class)->name('proveedores.create');
    // Route::get('/proveedores/{id}/editar', GestionProveedores::class)->name('proveedores.edit');

    // Suministros
    Route::get('/suministros', function () {
        return view('inventario.suministros');
    })->name('suministros.index');
    // Route::get('/suministros/crear', GestionSuministros::class)->name('suministros.create');
    // Route::get('/suministros/{id}/editar', GestionSuministros::class)->name('suministros.edit');

    // Compras
    Route::get('/compras', function () {
        return view('inventario.compras');
    })->name('compras.index');
    // Route::get('/compras/crear', GestionCompras::class)->name('compras.create');
    // Route::get('/compras/{id}/editar', GestionCompras::class)->name('compras.edit');

});