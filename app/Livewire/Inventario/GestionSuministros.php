<?php

namespace App\Livewire\Inventario;

use App\Models\Suministro;
use Livewire\Component;

class GestionSuministros extends Component
{
    // ----------------------------------------------------------------
    // BÚSQUEDA Y FILTROS
    // ----------------------------------------------------------------
    public string $buscarNombre = '';
    public string $buscarCodigoBarras = '';

    /** 'todos' | 'con_stock' | 'sin_stock' */
    public string $filtroStock = 'todos';

    // ----------------------------------------------------------------
    // ESTADO DE MODALES
    // ----------------------------------------------------------------
    public bool $modalModificarVisible = false;
    public bool $modalStockVisible = false;
    public bool $modalAgregarVisible = false;
    public bool $modalConfirmarEliminarVisible = false;

    public ?int $suministroSeleccionado = null;
    public ?int $suministroAEliminar = null;

    // Campos del modal "Modificar Suministro"
    // Coinciden exactamente con las columnas reales de SUMINISTRO.
    public string $form_nombre = '';
    public string $form_categoria = '';
    public string $form_codigo_barras = '';
    public string $form_unidad_medida = '';
    public int $form_stock_minimo = 5;

    // Campos del modal "Agregar Suministro"
    public string $nuevo_nombre = '';
    public string $nuevo_categoria = '';
    public string $nuevo_codigo_barras = '';
    public string $nuevo_unidad_medida = '';
    public int $nuevo_stock_minimo = 5;

    // ----------------------------------------------------------------
    // LISTADO FILTRADO (computed, consulta real a Oracle)
    // ----------------------------------------------------------------
    public function getSuministrosFiltradosProperty()
    {
        return Suministro::activos()
            ->with('inventario')
            ->when($this->buscarNombre !== '', function ($q) {
                $q->where('nombre', 'like', '%' . $this->buscarNombre . '%');
            })
            ->when($this->buscarCodigoBarras !== '', function ($q) {
                $q->where('codigo_barras', 'like', '%' . $this->buscarCodigoBarras . '%');
            })
            ->when($this->filtroStock === 'con_stock', fn ($q) => $q->conStock())
            ->when($this->filtroStock === 'sin_stock', fn ($q) => $q->sinStock())
            ->orderBy('nombre')
            ->get();
    }

    public function aplicarFiltro(string $filtro): void
    {
        $this->filtroStock = $filtro;
    }

    public function getSuministroActualProperty()
    {
        if (! $this->suministroSeleccionado) {
            return null;
        }

        return Suministro::activos()->with('inventario')->find($this->suministroSeleccionado);
    }

    // ----------------------------------------------------------------
    // REGLAS DE VALIDACIÓN
    // ----------------------------------------------------------------
    protected function reglasModificar(?int $idIgnorar = null): array
    {
        $reglaCodigo = 'nullable|string|max:50|unique:SUMINISTRO,codigo_barras';

        if ($idIgnorar) {
            $reglaCodigo .= ',' . $idIgnorar . ',id_suministro';
        }

        return [
            'form_nombre'        => 'required|string|max:100',
            'form_categoria'     => 'nullable|string|max:50',
            'form_codigo_barras' => $reglaCodigo,
            'form_unidad_medida' => 'nullable|string|max:20',
            'form_stock_minimo'  => 'required|integer|min:0',
        ];
    }

    protected function reglasAgregar(): array
    {
        return [
            'nuevo_nombre'        => 'required|string|max:100',
            'nuevo_categoria'     => 'nullable|string|max:50',
            'nuevo_codigo_barras' => 'nullable|string|max:50|unique:SUMINISTRO,codigo_barras',
            'nuevo_unidad_medida' => 'nullable|string|max:20',
            'nuevo_stock_minimo'  => 'required|integer|min:0',
        ];
    }

    protected array $messages = [
        'form_nombre.required'        => 'El nombre del suministro es obligatorio.',
        'nuevo_nombre.required'       => 'El nombre del suministro es obligatorio.',
        'form_codigo_barras.unique'   => 'Ya existe un suministro con este código de barras.',
        'nuevo_codigo_barras.unique'  => 'Ya existe un suministro con este código de barras.',
    ];

    // ----------------------------------------------------------------
    // MODAL: MODIFICAR SUMINISTRO (datos generales)
    // ----------------------------------------------------------------
    public function abrirModificar(int $idSuministro): void
    {
        $s = Suministro::activos()->find($idSuministro);

        if (! $s) {
            return;
        }

        $this->suministroSeleccionado = $s->id_suministro;
        $this->form_nombre            = $s->nombre;
        $this->form_categoria         = $s->categoria ?? '';
        $this->form_codigo_barras     = $s->codigo_barras ?? '';
        $this->form_unidad_medida     = $s->unidad_medida ?? '';
        $this->form_stock_minimo      = $s->stock_minimo;

        $this->modalModificarVisible = true;
    }

    public function cerrarModificar(): void
    {
        $this->modalModificarVisible = false;
        $this->suministroSeleccionado = null;
        $this->resetFormularioModificar();
        $this->resetErrorBag();
    }

    public function guardarModificar(): void
    {
        $this->validate($this->reglasModificar($this->suministroSeleccionado), $this->messages);

        $suministro = Suministro::activos()->find($this->suministroSeleccionado);

        if (! $suministro) {
            session()->flash('error', 'El suministro ya no existe.');
            $this->cerrarModificar();
            return;
        }

        $suministro->update([
            'nombre'        => $this->form_nombre,
            'categoria'     => $this->form_categoria ?: null,
            'codigo_barras' => $this->form_codigo_barras ?: null,
            'unidad_medida' => $this->form_unidad_medida ?: null,
            'stock_minimo'  => $this->form_stock_minimo,
        ]);

        session()->flash('mensaje', 'Suministro modificado correctamente.');
        $this->cerrarModificar();
    }

    private function resetFormularioModificar(): void
    {
        $this->reset(['form_nombre', 'form_categoria', 'form_codigo_barras', 'form_unidad_medida']);
        $this->form_stock_minimo = 5;
    }

    // ----------------------------------------------------------------
    // MODAL: STOCK DE SUMINISTRO (solo lectura)
    // El stock real (ALMACEN_INVENTARIO.stock_actual) se actualiza
    // automáticamente por el trigger TRG_ACTUALIZAR_STOCK_COMPRA al
    // registrar compras en DETALLE_COMPRA. Aquí solo se consulta.
    // ----------------------------------------------------------------
    public function abrirStock(int $idSuministro): void
    {
        $s = Suministro::activos()->find($idSuministro);

        if (! $s) {
            return;
        }

        $this->suministroSeleccionado = $s->id_suministro;
        $this->modalStockVisible = true;
    }

    public function cerrarStock(): void
    {
        $this->modalStockVisible = false;
        $this->suministroSeleccionado = null;
    }

    // ----------------------------------------------------------------
    // MODAL: AGREGAR SUMINISTRO
    // ----------------------------------------------------------------
    public function abrirAgregar(): void
    {
        $this->resetFormularioAgregar();
        $this->resetErrorBag();
        $this->modalAgregarVisible = true;
    }

    public function cerrarAgregar(): void
    {
        $this->modalAgregarVisible = false;
        $this->resetFormularioAgregar();
        $this->resetErrorBag();
    }

    public function guardarNuevoSuministro(): void
    {
        $this->validate($this->reglasAgregar(), $this->messages);

        // El registro en ALMACEN_INVENTARIO se crea automáticamente la
        // primera vez que se compre este suministro (vía el trigger
        // TRG_ACTUALIZAR_STOCK_COMPRA), por lo que aquí no se inserta
        // stock manualmente.
        Suministro::create([
            'nombre'        => $this->nuevo_nombre,
            'categoria'     => $this->nuevo_categoria ?: null,
            'codigo_barras' => $this->nuevo_codigo_barras ?: null,
            'unidad_medida' => $this->nuevo_unidad_medida ?: null,
            'stock_minimo'  => $this->nuevo_stock_minimo,
            'estado'        => 1,
        ]);

        session()->flash('mensaje', 'Suministro agregado correctamente.');
        $this->cerrarAgregar();
    }

    private function resetFormularioAgregar(): void
    {
        $this->reset(['nuevo_nombre', 'nuevo_categoria', 'nuevo_codigo_barras', 'nuevo_unidad_medida']);
        $this->nuevo_stock_minimo = 5;
    }

    // ----------------------------------------------------------------
    // ELIMINAR SUMINISTRO (baja lógica: estado = 0)
    // ----------------------------------------------------------------
    public function confirmarEliminar(int $idSuministro): void
    {
        $this->suministroAEliminar = $idSuministro;
        $this->modalConfirmarEliminarVisible = true;
    }

    public function cancelarEliminar(): void
    {
        $this->suministroAEliminar = null;
        $this->modalConfirmarEliminarVisible = false;
    }

    public function eliminarSuministro(): void
    {
        $suministro = Suministro::activos()->find($this->suministroAEliminar);

        if ($suministro) {
            // Baja lógica: la columna SUMINISTRO.estado existe para este fin.
            $suministro->update(['estado' => 0]);
        }

        session()->flash('mensaje', 'Suministro eliminado correctamente.');
        $this->cancelarEliminar();
    }

    public function render()
    {
        return view('livewire.inventario.gestion-suministros');
    }
}