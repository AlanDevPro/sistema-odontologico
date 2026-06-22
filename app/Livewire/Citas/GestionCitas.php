<?php

namespace App\Livewire\Citas;

use App\Models\Asistente;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use Illuminate\Database\QueryException;
use Livewire\Component;
use Livewire\WithPagination;

class GestionCitas extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    // ============================================================
    // BÚSQUEDA Y FILTROS
    // ============================================================
    public string $buscarNombres = '';
    public string $buscarApellidoPaterno = '';
    public string $buscarApellidoMaterno = '';
    public string $orden = 'recientes'; // recientes | antiguos
    public string $filtroEstado = 'todas'; // todas | Pendiente | Confirmada | Atendida | Cancelada | Reprogramada

    // ============================================================
    // MODALES
    // ============================================================
    public bool $modalAgregar = false;
    public bool $modalModificar = false;
    public bool $modalEstado = false;
    public bool $modalVer = false;
    public bool $modalEliminar = false;

    // ============================================================
    // FORMULARIO (Agregar / Modificar / Estado)
    // ============================================================
    public ?int $citaSeleccionada = null; // id_cita activa en el modal
    public string $id_paciente = '';
    public string $id_doctor = '';
    public string $id_asistente = '';
    public string $fecha_hora = '';
    public string $motivo = '';
    public string $estado_nuevo = 'Pendiente';

    public array $estados = Cita::ESTADOS;

    // ============================================================
    // CATÁLOGOS (cargados desde Oracle)
    // ============================================================
    public $pacientes;
    public $doctores;
    public $asistentes;

    public function mount(): void
    {
        $this->pacientes = Paciente::orderBy('nombres')->get();
        $this->doctores = Doctor::activos()->orderBy('nombres')->get();
        $this->asistentes = Asistente::activos()->orderBy('nombres')->get();
    }

    // ============================================================
    // PROPIEDAD COMPUTADA: cita activa en los modales (Ver/Modificar/Estado/Eliminar)
    // ============================================================
    public function getCitaActualProperty(): ?Cita
    {
        if (! $this->citaSeleccionada) {
            return null;
        }

        return Cita::with(['paciente', 'doctor', 'asistente'])->find($this->citaSeleccionada);
    }

    // ============================================================
    // PROPIEDAD COMPUTADA: listado filtrado / buscado / ordenado / paginado
    // ============================================================
    public function getCitasFiltradasProperty()
    {
        $query = Cita::query()->with(['paciente', 'doctor']);

        if ($this->buscarNombres !== '') {
            $termino = $this->buscarNombres;
            $query->whereHas('paciente', function ($q) use ($termino) {
                $q->whereRaw('LOWER(nombres) LIKE ?', ['%' . mb_strtolower($termino) . '%']);
            });
        }

        if ($this->buscarApellidoPaterno !== '' || $this->buscarApellidoMaterno !== '') {
            $termino = trim($this->buscarApellidoPaterno . ' ' . $this->buscarApellidoMaterno);
            $query->whereHas('paciente', function ($q) use ($termino) {
                $q->whereRaw('LOWER(apellidos) LIKE ?', ['%' . mb_strtolower($termino) . '%']);
            });
        }

        if ($this->filtroEstado !== 'todas') {
            $query->where('estado', $this->filtroEstado);
        }

        $this->orden === 'recientes'
            ? $query->orderByDesc('fecha_hora')
            : $query->orderBy('fecha_hora');

        return $query->paginate(10);
    }

    // ============================================================
    // ABRIR MODALES
    // ============================================================
    public function abrirAgregar(): void
    {
        $this->resetFormulario();
        $this->modalAgregar = true;
    }

    public function abrirVer(int $id): void
    {
        $this->citaSeleccionada = $id;
        $this->modalVer = true;
    }

    public function abrirModificar(int $id): void
    {
        $cita = Cita::find($id);
        if (! $cita) {
            return;
        }

        $this->citaSeleccionada = $id;
        $this->id_paciente = (string) $cita->id_paciente;
        $this->id_doctor = (string) $cita->id_doctor;
        $this->id_asistente = (string) ($cita->id_asistente ?? '');
        $this->fecha_hora = $cita->fecha_hora?->format('Y-m-d\TH:i') ?? '';
        $this->motivo = $cita->motivo;
        $this->modalModificar = true;
    }

    public function abrirEstado(int $id): void
    {
        $cita = Cita::find($id);
        if (! $cita) {
            return;
        }

        $this->citaSeleccionada = $id;
        $this->estado_nuevo = $cita->estado;
        $this->modalEstado = true;
    }

    public function abrirEliminar(int $id): void
    {
        $this->citaSeleccionada = $id;
        $this->modalEliminar = true;
    }

    public function cerrarModales(): void
    {
        $this->modalAgregar = false;
        $this->modalModificar = false;
        $this->modalEstado = false;
        $this->modalVer = false;
        $this->modalEliminar = false;
        $this->resetFormulario();
    }

    private function resetFormulario(): void
    {
        $this->citaSeleccionada = null;
        $this->id_paciente = '';
        $this->id_doctor = '';
        $this->id_asistente = '';
        $this->fecha_hora = '';
        $this->motivo = '';
        $this->estado_nuevo = 'Pendiente';
        $this->resetValidation();
    }

    // ============================================================
    // GUARDAR (CRUD real contra Oracle)
    // ============================================================
    public function guardarNuevaCita(): void
    {
        $datos = $this->validate([
            'id_paciente'  => 'required|exists:oracle.paciente,id_paciente',
            'id_doctor'    => 'required|exists:oracle.doctor,id_doctor',
            'id_asistente' => 'nullable|exists:oracle.asistente,id_asistente',
            'fecha_hora'   => 'required|date',
            'motivo'       => 'required|string|max:255',
        ]);

        Cita::create([
            'id_paciente'  => $datos['id_paciente'],
            'id_doctor'    => $datos['id_doctor'],
            'id_asistente' => $datos['id_asistente'] ?: null,
            'fecha_hora'   => $datos['fecha_hora'],
            'motivo'       => $datos['motivo'],
            'estado'       => 'Pendiente',
        ]);

        session()->flash('mensaje', 'Cita registrada correctamente.');
        $this->cerrarModales();
    }

    public function actualizarCita(): void
    {
        $datos = $this->validate([
            'id_paciente'  => 'required|exists:oracle.paciente,id_paciente',
            'id_doctor'    => 'required|exists:oracle.doctor,id_doctor',
            'id_asistente' => 'nullable|exists:oracle.asistente,id_asistente',
            'fecha_hora'   => 'required|date',
            'motivo'       => 'required|string|max:255',
        ]);

        $cita = Cita::find($this->citaSeleccionada);
        if (! $cita) {
            return;
        }

        $cita->update([
            'id_paciente'  => $datos['id_paciente'],
            'id_doctor'    => $datos['id_doctor'],
            'id_asistente' => $datos['id_asistente'] ?: null,
            'fecha_hora'   => $datos['fecha_hora'],
            'motivo'       => $datos['motivo'],
        ]);

        session()->flash('mensaje', 'Cita modificada correctamente.');
        $this->cerrarModales();
    }

    public function actualizarEstado(): void
    {
        $datos = $this->validate([
            'estado_nuevo' => 'required|in:' . implode(',', Cita::ESTADOS),
        ]);

        $cita = Cita::find($this->citaSeleccionada);
        if (! $cita) {
            return;
        }

        $cita->update(['estado' => $datos['estado_nuevo']]);

        session()->flash('mensaje', 'Estado de la cita actualizado.');
        $this->cerrarModales();
    }

    public function eliminarCita(): void
    {
        try {
            Cita::destroy($this->citaSeleccionada);
            session()->flash('mensaje', 'Cita eliminada correctamente.');
        } catch (QueryException $e) {
            session()->flash('error', 'No se pudo eliminar la cita: tiene registros relacionados.');
        }

        $this->cerrarModales();
    }

    // ============================================================
    // HELPERS DE PRESENTACIÓN
    // ============================================================
    public function colorEstado(string $estado): string
    {
        return match ($estado) {
            'Pendiente'    => 'pendiente',
            'Confirmada'   => 'confirmada',
            'Atendida'     => 'atendida',
            'Cancelada'    => 'cancelada',
            'Reprogramada' => 'reprogramada',
            default        => 'pendiente',
        };
    }

    public function render()
    {
        return view('livewire.citas.gestion-citas', [
            'citasFiltradas' => $this->citasFiltradas,
            'citaActual'     => $this->citaActual,
        ]);
    }
}