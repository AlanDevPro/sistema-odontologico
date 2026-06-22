<?php

namespace App\Livewire\Inventario;

use App\Models\Proveedor;
use Livewire\Component;

class GestionProveedores extends Component
{
    // ----------------------------------------------------------------
    // ESTADO DE LA UI
    // ----------------------------------------------------------------
    public bool $modalEditarVisible = false;
    public bool $modalAgregarVisible = false;
    public bool $modalConfirmarEliminarVisible = false;

    public ?int $proveedorSeleccionado = null;
    public ?int $proveedorAEliminar = null;

    // Campos del formulario "Modificar / Agregar proveedor"
    // Coinciden exactamente con las columnas reales de PROVEEDOR.
    public string $form_nit_ruc = '';
    public string $form_razon_social = '';
    public string $form_nombre_contacto = '';
    public string $form_telefono = '';
    public string $form_direccion = '';
    public string $form_correo = '';

    // ----------------------------------------------------------------
    // LISTADO DE PROVEEDORES (se llena desde la base de datos)
    // ----------------------------------------------------------------
    public function getProveedoresProperty()
    {
        return Proveedor::orderBy('razon_social')->get();
    }

    public function getProveedorActualProperty()
    {
        if (! $this->proveedorSeleccionado) {
            return null;
        }

        return Proveedor::find($this->proveedorSeleccionado);
    }

    // ----------------------------------------------------------------
    // REGLAS DE VALIDACIÓN
    // ----------------------------------------------------------------
    protected function reglasValidacion(?int $idIgnorar = null): array
    {
        $reglaNitRuc = 'required|string|max:20|unique:PROVEEDOR,nit_ruc';

        if ($idIgnorar) {
            $reglaNitRuc .= ',' . $idIgnorar . ',id_proveedor';
        }

        return [
            'form_nit_ruc'         => $reglaNitRuc,
            'form_razon_social'    => 'required|string|max:100',
            'form_nombre_contacto' => 'nullable|string|max:80',
            'form_telefono'        => 'nullable|string|max:15',
            'form_direccion'       => 'nullable|string|max:255',
            'form_correo'          => 'nullable|email|max:100',
        ];
    }

    protected array $messages = [
        'form_nit_ruc.required'      => 'El NIT/RUC es obligatorio.',
        'form_nit_ruc.unique'        => 'Ya existe un proveedor registrado con este NIT/RUC.',
        'form_razon_social.required' => 'La razón social es obligatoria.',
        'form_correo.email'          => 'Ingresa un correo electrónico válido.',
    ];

    // ----------------------------------------------------------------
    // MODAL: MODIFICAR PROVEEDOR
    // ----------------------------------------------------------------
    public function abrirEditar(int $idProveedor): void
    {
        $proveedor = Proveedor::find($idProveedor);

        if (! $proveedor) {
            return;
        }

        $this->proveedorSeleccionado = $proveedor->id_proveedor;
        $this->form_nit_ruc          = $proveedor->nit_ruc;
        $this->form_razon_social     = $proveedor->razon_social;
        $this->form_nombre_contacto  = $proveedor->nombre_contacto ?? '';
        $this->form_telefono         = $proveedor->telefono ?? '';
        $this->form_direccion        = $proveedor->direccion ?? '';
        $this->form_correo           = $proveedor->correo ?? '';

        $this->modalEditarVisible = true;
    }

    public function cerrarEditar(): void
    {
        $this->modalEditarVisible = false;
        $this->proveedorSeleccionado = null;
        $this->resetFormulario();
        $this->resetErrorBag();
    }

    public function guardarEditar(): void
    {
        $this->validate($this->reglasValidacion($this->proveedorSeleccionado), $this->messages);

        $proveedor = Proveedor::find($this->proveedorSeleccionado);

        if (! $proveedor) {
            session()->flash('error', 'El proveedor ya no existe.');
            $this->cerrarEditar();
            return;
        }

        $proveedor->update([
            'nit_ruc'         => $this->form_nit_ruc,
            'razon_social'    => $this->form_razon_social,
            'nombre_contacto' => $this->form_nombre_contacto ?: null,
            'telefono'        => $this->form_telefono ?: null,
            'direccion'       => $this->form_direccion ?: null,
            'correo'          => $this->form_correo ?: null,
        ]);

        session()->flash('mensaje', 'Proveedor actualizado correctamente.');
        $this->cerrarEditar();
    }

    // ----------------------------------------------------------------
    // MODAL: AGREGAR PROVEEDOR
    // ----------------------------------------------------------------
    public function abrirAgregar(): void
    {
        $this->resetFormulario();
        $this->resetErrorBag();
        $this->modalAgregarVisible = true;
    }

    public function cerrarAgregar(): void
    {
        $this->modalAgregarVisible = false;
        $this->resetFormulario();
        $this->resetErrorBag();
    }

    public function guardarNuevoProveedor(): void
    {
        $this->validate($this->reglasValidacion(), $this->messages);

        Proveedor::create([
            'nit_ruc'         => $this->form_nit_ruc,
            'razon_social'    => $this->form_razon_social,
            'nombre_contacto' => $this->form_nombre_contacto ?: null,
            'telefono'        => $this->form_telefono ?: null,
            'direccion'       => $this->form_direccion ?: null,
            'correo'          => $this->form_correo ?: null,
        ]);

        session()->flash('mensaje', 'Proveedor agregado correctamente.');
        $this->cerrarAgregar();
    }

    private function resetFormulario(): void
    {
        $this->reset([
            'form_nit_ruc',
            'form_razon_social',
            'form_nombre_contacto',
            'form_telefono',
            'form_direccion',
            'form_correo',
        ]);
    }

    // ----------------------------------------------------------------
    // ELIMINAR PROVEEDOR (con confirmación)
    // ----------------------------------------------------------------
    public function confirmarEliminar(int $idProveedor): void
    {
        $this->proveedorAEliminar = $idProveedor;
        $this->modalConfirmarEliminarVisible = true;
    }

    public function cancelarEliminar(): void
    {
        $this->proveedorAEliminar = null;
        $this->modalConfirmarEliminarVisible = false;
    }

    public function eliminarProveedor(): void
    {
        $proveedor = Proveedor::find($this->proveedorAEliminar);

        if ($proveedor) {
            // PROVEEDOR no tiene columna 'estado': no admite baja lógica.
            // Si el proveedor tiene compras asociadas, la FK en COMPRA
            // bloqueará el borrado (RESTRICT por defecto en Oracle).
            try {
                $proveedor->delete();
                session()->flash('mensaje', 'Proveedor eliminado correctamente.');
            } catch (\Throwable $e) {
                session()->flash('error', 'No se puede eliminar: el proveedor tiene compras registradas.');
            }
        }

        $this->cancelarEliminar();
    }

    public function render()
    {
        return view('livewire.inventario.gestion-proveedores');
    }
}