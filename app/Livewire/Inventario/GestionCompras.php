<?php

namespace App\Livewire\Inventario;

use App\Models\Asistente;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Suministro;
use Livewire\Component;

class GestionCompras extends Component
{
    // ----------------------------------------------------------------
    // NAVEGACIÓN INTERNA (sin recargar página)
    // 'lista'   -> grid de cards de compras
    // 'detalle' -> ticket / registro de una compra específica
    // ----------------------------------------------------------------
    public string $vista = 'lista';

    public ?int $compraSeleccionada = null;

    // ----------------------------------------------------------------
    // ESTADO DE MODALES
    // ----------------------------------------------------------------
    public bool $modalNuevaCompraVisible = false;       // crear cabecera de COMPRA
    public bool $modalAgregarSuministroVisible = false;  // agregar fila a DETALLE_COMPRA
    public bool $modalConfirmarEliminarVisible = false;  // eliminar compra completa (cabecera)

    public ?int $compraAEliminar = null;

    // Campos del modal "Nueva compra" (cabecera)
    public ?int $form_id_proveedor = null;
    public ?int $form_id_asistente = null;
    public string $form_nro_factura = '';
    public string $form_fecha_compra = '';

    // Campos del modal "Agregar suministro a la compra" (DETALLE_COMPRA)
    public ?int $nuevo_id_suministro = null;
    public float $nuevo_det_precio_unitario = 0;
    public float $nuevo_det_cantidad = 1;

    // ----------------------------------------------------------------
    // CATÁLOGOS PARA LOS SELECTS (datos reales de Oracle)
    // ----------------------------------------------------------------
    public function getProveedoresProperty()
    {
        return Proveedor::orderBy('razon_social')->get();
    }

    public function getAsistentesProperty()
    {
        return Asistente::activos()->orderBy('nombres')->get();
    }

    public function getSuministrosDisponiblesProperty()
    {
        return Suministro::activos()->orderBy('nombre')->get();
    }

    // ----------------------------------------------------------------
    // LISTADO DE COMPRAS (consulta real a Oracle)
    // ----------------------------------------------------------------
    public function getComprasProperty()
    {
        return Compra::with(['proveedor', 'asistente', 'detalles.suministro'])
            ->orderByDesc('fecha_compra')
            ->orderByDesc('id_compra')
            ->get();
    }

    public function getCompraActualProperty()
    {
        if (! $this->compraSeleccionada) {
            return null;
        }

        return Compra::with(['proveedor', 'asistente', 'detalles.suministro'])
            ->find($this->compraSeleccionada);
    }

    // ----------------------------------------------------------------
    // NAVEGACIÓN ENTRE VISTAS
    // ----------------------------------------------------------------
    public function verDetalle(int $idCompra): void
    {
        $this->compraSeleccionada = $idCompra;
        $this->vista = 'detalle';
    }

    public function volverALista(): void
    {
        $this->vista = 'lista';
        $this->compraSeleccionada = null;
    }

    // ----------------------------------------------------------------
    // MODAL: NUEVA COMPRA (cabecera)
    // ----------------------------------------------------------------
    public function abrirNuevaCompra(): void
    {
        $this->resetFormularioCompra();
        $this->form_fecha_compra = now()->format('Y-m-d');
        $this->resetErrorBag();
        $this->modalNuevaCompraVisible = true;
    }

    public function cerrarNuevaCompra(): void
    {
        $this->modalNuevaCompraVisible = false;
        $this->resetFormularioCompra();
        $this->resetErrorBag();
    }

    public function guardarNuevaCompra(): void
    {
        $this->validate([
            'form_id_proveedor' => 'required|integer|exists:PROVEEDOR,id_proveedor',
            'form_id_asistente' => 'nullable|integer|exists:ASISTENTE,id_asistente',
            'form_nro_factura'  => 'nullable|string|max:50',
            'form_fecha_compra' => 'required|date',
        ], [
            'form_id_proveedor.required' => 'Debes seleccionar un proveedor.',
            'form_id_proveedor.exists'   => 'El proveedor seleccionado no es válido.',
            'form_id_asistente.exists'   => 'El asistente seleccionado no es válido.',
        ]);

        $compra = Compra::create([
            'id_proveedor' => $this->form_id_proveedor,
            'id_asistente' => $this->form_id_asistente,
            'fecha_compra' => $this->form_fecha_compra,
            'nro_factura'  => $this->form_nro_factura ?: null,
            'total_compra' => 0,
            'estado'       => 'Completada',
        ]);

        session()->flash('mensaje', 'Compra registrada correctamente. Ahora puedes agregar los suministros.');
        $this->cerrarNuevaCompra();
        $this->verDetalle($compra->id_compra);
    }

    private function resetFormularioCompra(): void
    {
        $this->reset(['form_id_proveedor', 'form_id_asistente', 'form_nro_factura', 'form_fecha_compra']);
    }

    // ----------------------------------------------------------------
    // ELIMINAR COMPRA COMPLETA (cabecera + detalles en cascada)
    // ----------------------------------------------------------------
    public function confirmarEliminar(int $idCompra): void
    {
        $this->compraAEliminar = $idCompra;
        $this->modalConfirmarEliminarVisible = true;
    }

    public function cancelarEliminar(): void
    {
        $this->compraAEliminar = null;
        $this->modalConfirmarEliminarVisible = false;
    }

    public function eliminarCompra(): void
    {
        $compra = Compra::find($this->compraAEliminar);

        if ($compra) {
            // DETALLE_COMPRA tiene ON DELETE CASCADE hacia COMPRA, pero el
            // stock que ya se sumó en ALMACEN_INVENTARIO NO se revierte
            // automáticamente (el trigger solo actúa en INSERT).
            $compra->delete();

            if ($this->vista === 'detalle' && $this->compraSeleccionada === $this->compraAEliminar) {
                $this->volverALista();
            }
        }

        session()->flash('mensaje', 'Compra eliminada. Ten en cuenta que el stock ya sumado en inventario no se revierte automáticamente.');
        $this->cancelarEliminar();
    }

    // ----------------------------------------------------------------
    // MODAL: AGREGAR SUMINISTRO A LA COMPRA (DETALLE_COMPRA)
    // Solo se permite AGREGAR filas nuevas. No se editan ni eliminan
    // filas existentes, porque el trigger TRG_ACTUALIZAR_STOCK_COMPRA
    // únicamente suma stock al insertar; no hay lógica para revertir.
    // ----------------------------------------------------------------
    public function abrirAgregarSuministro(): void
    {
        $this->resetFormularioDetalle();
        $this->resetErrorBag();
        $this->modalAgregarSuministroVisible = true;
    }

    public function cerrarAgregarSuministro(): void
    {
        $this->modalAgregarSuministroVisible = false;
        $this->resetFormularioDetalle();
        $this->resetErrorBag();
    }

    public function guardarNuevoDetalle(): void
    {
        $this->validate([
            'nuevo_id_suministro'        => 'required|integer|exists:SUMINISTRO,id_suministro',
            'nuevo_det_precio_unitario'  => 'required|numeric|min:0.01',
            'nuevo_det_cantidad'         => 'required|numeric|min:0.01',
        ], [
            'nuevo_id_suministro.required' => 'Debes seleccionar un suministro.',
            'nuevo_id_suministro.exists'   => 'El suministro seleccionado no es válido.',
        ]);

        $compra = Compra::find($this->compraSeleccionada);

        if (! $compra) {
            session()->flash('error', 'La compra ya no existe.');
            $this->cerrarAgregarSuministro();
            return;
        }

        $subtotal = round($this->nuevo_det_precio_unitario * $this->nuevo_det_cantidad, 2);

        // El INSERT dispara TRG_ACTUALIZAR_STOCK_COMPRA en Oracle, que suma
        // 'cantidad' al stock_actual del suministro en ALMACEN_INVENTARIO.
        DetalleCompra::create([
            'id_compra'       => $compra->id_compra,
            'id_suministro'   => $this->nuevo_id_suministro,
            'cantidad'        => $this->nuevo_det_cantidad,
            'precio_unitario' => $this->nuevo_det_precio_unitario,
            'subtotal'        => $subtotal,
        ]);

        // COMPRA.total_compra no tiene trigger propio: se recalcula aquí.
        $compra->recalcularTotal();

        session()->flash('mensaje', 'Suministro agregado a la compra correctamente.');
        $this->cerrarAgregarSuministro();
    }

    private function resetFormularioDetalle(): void
    {
        $this->reset(['nuevo_id_suministro', 'nuevo_det_precio_unitario']);
        $this->nuevo_det_cantidad = 1;
    }

    public function render()
    {
        return view('livewire.inventario.gestion-compras');
    }
}