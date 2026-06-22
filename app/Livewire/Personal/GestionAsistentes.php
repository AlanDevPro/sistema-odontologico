<?php

namespace App\Livewire\Personal;

use App\Models\Asistente;
use Livewire\Component;

class GestionAsistentes extends Component
{
    // ----------------------------------------------------------------
    // NAVEGACIÓN INTERNA (sin recargar página)
    // 'lista'   -> grid de cards de asistentes
    // 'detalle' -> ficha completa de un asistente
    // ----------------------------------------------------------------
    public string $vista = 'lista';

    public ?int $asistenteSeleccionado = null;

    // ----------------------------------------------------------------
    // ESTADO DE MODALES
    // ----------------------------------------------------------------
    public bool $modalEditarVisible = false;
    public bool $modalAgregarVisible = false;
    public bool $modalConfirmarEliminarVisible = false;

    public ?int $asistenteAEliminar = null;

    // ----------------------------------------------------------------
    // CAMPOS DEL FORMULARIO (Modificar / Agregar)
    // Reflejan únicamente las columnas reales de ASISTENTE.
    // ----------------------------------------------------------------
    public string $form_ci_dni = '';
    public string $form_nombres = '';
    public string $form_apellidos = '';
    public string $form_telefono = '';
    public string $form_turno = '';
    public string $form_fecha_contratacion = '';

    // ----------------------------------------------------------------
    // LISTADO DE ASISTENTES (se llena desde la base de datos)
    // ----------------------------------------------------------------
    public function getAsistentesProperty()
    {
        return Asistente::activos()
            ->orderBy('id_asistente')
            ->get();
    }

    public function getAsistenteActualProperty()
    {
        if (! $this->asistenteSeleccionado) {
            return null;
        }

        return Asistente::activos()->find($this->asistenteSeleccionado);
    }

    // ----------------------------------------------------------------
    // REGLAS DE VALIDACIÓN
    // ----------------------------------------------------------------
    protected function reglasValidacion(?int $idIgnorar = null): array
    {
        $reglaCiDni = 'required|string|max:20|unique:ASISTENTE,ci_dni';

        if ($idIgnorar) {
            $reglaCiDni .= ',' . $idIgnorar . ',id_asistente';
        }

        return [
            'form_ci_dni'             => $reglaCiDni,
            'form_nombres'            => 'required|string|max:80',
            'form_apellidos'          => 'required|string|max:80',
            'form_telefono'           => 'nullable|string|max:15',
            'form_turno'              => 'nullable|string|max:20',
            'form_fecha_contratacion' => 'nullable|date',
        ];
    }

    protected array $messages = [
        'form_ci_dni.required'    => 'El CI/DNI es obligatorio.',
        'form_ci_dni.unique'      => 'Ya existe un asistente registrado con este CI/DNI.',
        'form_nombres.required'   => 'Los nombres son obligatorios.',
        'form_apellidos.required' => 'Los apellidos son obligatorios.',
    ];

    // ----------------------------------------------------------------
    // NAVEGACIÓN ENTRE VISTAS
    // ----------------------------------------------------------------
    public function verDetalle(int $idAsistente): void
    {
        $this->asistenteSeleccionado = $idAsistente;
        $this->vista = 'detalle';
    }

    public function volverALista(): void
    {
        $this->vista = 'lista';
        $this->asistenteSeleccionado = null;
    }

    // ----------------------------------------------------------------
    // MODAL: MODIFICAR ASISTENTE
    // ----------------------------------------------------------------
    public function abrirEditar(int $idAsistente): void
    {
        $asistente = Asistente::activos()->find($idAsistente);

        if (! $asistente) {
            return;
        }

        $this->asistenteSeleccionado    = $asistente->id_asistente;
        $this->form_ci_dni              = $asistente->ci_dni;
        $this->form_nombres             = $asistente->nombres;
        $this->form_apellidos           = $asistente->apellidos;
        $this->form_telefono            = $asistente->telefono ?? '';
        $this->form_turno               = $asistente->turno ?? '';
        $this->form_fecha_contratacion  = $asistente->fecha_contratacion
            ? $asistente->fecha_contratacion->format('Y-m-d')
            : '';

        $this->modalEditarVisible = true;
    }

    public function cerrarEditar(): void
    {
        $this->modalEditarVisible = false;
        $this->asistenteSeleccionado = null;
        $this->resetFormulario();
        $this->resetErrorBag();
    }

    public function guardarEditar(): void
    {
        $this->validate($this->reglasValidacion($this->asistenteSeleccionado), $this->messages);

        $asistente = Asistente::activos()->find($this->asistenteSeleccionado);

        if (! $asistente) {
            session()->flash('error', 'El asistente ya no existe.');
            $this->cerrarEditar();
            return;
        }

        $asistente->update([
            'ci_dni'             => $this->form_ci_dni,
            'nombres'            => $this->form_nombres,
            'apellidos'          => $this->form_apellidos,
            'telefono'           => $this->form_telefono ?: null,
            'turno'              => $this->form_turno ?: null,
            'fecha_contratacion' => $this->form_fecha_contratacion ?: null,
        ]);

        session()->flash('mensaje', 'Asistente modificado correctamente.');
        $this->cerrarEditar();
    }

    // ----------------------------------------------------------------
    // MODAL: AGREGAR ASISTENTE
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

    public function guardarNuevoAsistente(): void
    {
        $this->validate($this->reglasValidacion(), $this->messages);

        Asistente::create([
            'ci_dni'             => $this->form_ci_dni,
            'nombres'            => $this->form_nombres,
            'apellidos'          => $this->form_apellidos,
            'telefono'           => $this->form_telefono ?: null,
            'turno'              => $this->form_turno ?: null,
            'fecha_contratacion' => $this->form_fecha_contratacion ?: null,
            'estado'             => 1,
        ]);

        session()->flash('mensaje', 'Asistente agregado correctamente.');
        $this->cerrarAgregar();
    }

    private function resetFormulario(): void
    {
        $this->reset([
            'form_ci_dni',
            'form_nombres',
            'form_apellidos',
            'form_telefono',
            'form_turno',
            'form_fecha_contratacion',
        ]);
    }

    // ----------------------------------------------------------------
    // ELIMINAR ASISTENTE (baja lógica: estado = 0)
    // ----------------------------------------------------------------
    public function confirmarEliminar(int $idAsistente): void
    {
        $this->asistenteAEliminar = $idAsistente;
        $this->modalConfirmarEliminarVisible = true;
    }

    public function cancelarEliminar(): void
    {
        $this->asistenteAEliminar = null;
        $this->modalConfirmarEliminarVisible = false;
    }

    public function eliminarAsistente(): void
    {
        $asistente = Asistente::activos()->find($this->asistenteAEliminar);

        if ($asistente) {
            // Baja lógica: la columna ASISTENTE.estado existe para este fin.
            $asistente->update(['estado' => 0]);
        }

        if ($this->vista === 'detalle' && $this->asistenteSeleccionado === $this->asistenteAEliminar) {
            $this->volverALista();
        }

        session()->flash('mensaje', 'Asistente eliminado correctamente.');
        $this->cancelarEliminar();
    }

    public function render()
    {
        return view('livewire.personal.gestion-asistentes');
    }
}