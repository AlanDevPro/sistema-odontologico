<?php

namespace App\Livewire;

use App\Models\Paciente;
use App\Models\Folder;
use App\Models\Tratamiento;
use App\Models\Doctor;
use App\Models\Odontograma;
use App\Models\DetalleOdontograma;
use Illuminate\Database\QueryException;
use Livewire\Component;
use Livewire\WithPagination;

class GestionPacientes extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    // ===================== NAVEGACIÓN INTERNA =====================
    public string $vista = 'lista'; // 'lista' | 'ficha' | 'odontograma'
    public ?int $idPacienteSeleccionado = null;

    // ===================== FILTROS (vista lista) =====================
    public string $buscarNombres = '';
    public string $buscarApellidoPaterno = '';
    public string $buscarApellidoMaterno = '';
    public string $filtroTratamiento = '';
    public string $filtroFolder = '';
    public string $filtroSexo = '';
    public string $orden = 'reciente';

    // ===================== MODAL: CREAR PACIENTE (lista) =====================
    public bool $modalAbierto = false;

    public string $ci_dni = '';
    public string $nombres = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $fecha_nacimiento = '';
    public string $sexo = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $antecedentes_medicos = '';
    public string $id_folder = '';

    // ===================== MODAL: EDITAR PACIENTE (ficha) =====================
    public bool $modalDatosBasicos = false;
    public string $f_nombres = '';
    public string $f_apellido_paterno = '';
    public string $f_apellido_materno = '';
    public string $f_sexo = '';
    public string $f_telefono = '';
    public string $f_direccion = '';
    public string $f_antecedentes_medicos = '';
    public string $f_id_folder = '';

    // ===================== MODAL: ELIMINAR PACIENTE (ficha) =====================
    public bool $modalEliminar = false;

    // ===================== ODONTOGRAMA =====================
    public ?int $odontogramaSeleccionadoId = null;
    public array $detallesOdontograma = []; // hallazgos del odontograma activo
    public array $estadoDientes = [];       // pieza_dental => ['estado'=>..,'color'=>..,'id_detalle'=>..]

    public bool $modalNuevoOdontograma = false;
    public string $od_id_doctor = '';
    public string $od_observaciones = '';

    public bool $modalEliminarOdontograma = false;
    public ?int $odontogramaAEliminar = null;

    // ===================== MODAL: HALLAZGO POR DIENTE (DETALLE_ODONTOGRAMA) =====================
    public bool $modalDetalleDiente = false;
    public ?int $detalleEditandoId = null;
    public string $dd_pieza_dental = '';
    public string $dd_cara = '';
    public string $dd_diagnostico = '';
    public string $dd_id_tratamiento = '';
    public string $dd_estado = 'Por tratar';

    public array $opcionesCara = ['Vestibular', 'Palatino/Lingual', 'Mesial', 'Distal', 'Oclusal/Incisal'];
    public array $opcionesEstado = ['Por tratar', 'En tratamiento', 'Tratado'];

    public array $coloresEstado = [
        'Por tratar'      => '#ef4444',
        'En tratamiento'  => '#f59e0b',
        'Tratado'         => '#10b981',
    ];

    // ===================== CATÁLOGOS (cargados desde Oracle) =====================
    public $folders;
    public $tratamientos;
    public $doctores;

    public function mount(): void
    {
        $this->folders = Folder::orderBy('codigo_archivo')->get();
        $this->tratamientos = Tratamiento::activos()->orderBy('nombre')->get();
        $this->doctores = Doctor::activos()->orderBy('nombres')->get();
    }

    // ===================== HELPERS =====================
    public function getPacienteActualProperty(): ?Paciente
    {
        if (! $this->idPacienteSeleccionado) {
            return null;
        }

        return Paciente::with([
                'folder',
                'odontogramas' => fn ($q) => $q->orderByDesc('fecha_evaluacion')->with('doctor'),
            ])
            ->find($this->idPacienteSeleccionado);
    }

    // ===================== NAVEGACIÓN LISTA <-> FICHA =====================
    public function verPaciente(int $idPaciente): void
    {
        $this->idPacienteSeleccionado = $idPaciente;
        $this->vista = 'ficha';
    }

    public function volverALista(): void
    {
        $this->vista = 'lista';
        $this->idPacienteSeleccionado = null;
        $this->resetOdontograma();
    }

    public function volverAFicha(): void
    {
        $this->vista = 'ficha';
        $this->resetOdontograma();
    }

    // ===================== MODAL: CREAR PACIENTE (lista) =====================
    public function abrirModalCrear(): void
    {
        $this->resetFormularioRapido();
        $this->modalAbierto = true;
    }

    public function cerrarModal(): void
    {
        $this->modalAbierto = false;
        $this->resetFormularioRapido();
    }

    public function guardarPaciente(): void
    {
        $datos = $this->validate([
            'ci_dni'                => 'required|string|max:20|unique:oracle.paciente,ci_dni',
            'nombres'                => 'required|string|max:80',
            'apellido_paterno'       => 'required|string|max:80',
            'apellido_materno'       => 'nullable|string|max:80',
            'fecha_nacimiento'       => 'required|date|before:today',
            'sexo'                   => 'required|in:M,F,O',
            'telefono'               => 'nullable|string|max:15',
            'direccion'              => 'nullable|string|max:255',
            'antecedentes_medicos'   => 'nullable|string|max:500',
            'id_folder'              => 'nullable|exists:oracle.folder,id_folder',
        ]);

        Paciente::create([
            'id_folder'             => $datos['id_folder'] ?: null,
            'ci_dni'                => $datos['ci_dni'],
            'nombres'               => $datos['nombres'],
            'apellidos'             => Paciente::combinarApellidos($datos['apellido_paterno'], $datos['apellido_materno']),
            'fecha_nacimiento'      => $datos['fecha_nacimiento'],
            'sexo'                  => $datos['sexo'],
            'telefono'              => $datos['telefono'] ?: null,
            'direccion'             => $datos['direccion'] ?: null,
            'antecedentes_medicos'  => $datos['antecedentes_medicos'] ?: null,
            'fecha_registro'        => now(),
        ]);

        session()->flash('mensaje', 'Paciente registrado correctamente.');
        $this->cerrarModal();
    }

    private function resetFormularioRapido(): void
    {
        $this->reset([
            'ci_dni', 'nombres', 'apellido_paterno', 'apellido_materno',
            'fecha_nacimiento', 'sexo', 'telefono', 'direccion',
            'antecedentes_medicos', 'id_folder',
        ]);
        $this->resetValidation();
    }

    public function updating($propiedad): void
    {
        if (in_array($propiedad, ['buscarNombres', 'buscarApellidoPaterno', 'buscarApellidoMaterno', 'filtroTratamiento', 'filtroFolder', 'filtroSexo', 'orden'])) {
            $this->resetPage();
        }
    }

    // ===================== MODAL: EDITAR PACIENTE (ficha) =====================
    public function abrirModalDatosBasicos(): void
    {
        $p = $this->pacienteActual;
        if (! $p) {
            return;
        }

        $this->f_nombres              = $p->nombres;
        $this->f_apellido_paterno     = $p->apellido_paterno;
        $this->f_apellido_materno     = $p->apellido_materno;
        $this->f_sexo                 = $p->sexo;
        $this->f_telefono             = $p->telefono ?? '';
        $this->f_direccion            = $p->direccion ?? '';
        $this->f_antecedentes_medicos = $p->antecedentes_medicos ?? '';
        $this->f_id_folder            = (string) ($p->id_folder ?? '');
        $this->modalDatosBasicos      = true;
    }

    public function guardarDatosBasicos(): void
    {
        $datos = $this->validate([
            'f_nombres'              => 'required|string|max:80',
            'f_apellido_paterno'     => 'required|string|max:80',
            'f_apellido_materno'     => 'nullable|string|max:80',
            'f_sexo'                 => 'required|in:M,F,O',
            'f_telefono'             => 'nullable|string|max:15',
            'f_direccion'            => 'nullable|string|max:255',
            'f_antecedentes_medicos' => 'nullable|string|max:500',
            'f_id_folder'            => 'nullable|exists:oracle.folder,id_folder',
        ]);

        $p = $this->pacienteActual;
        if (! $p) {
            return;
        }

        $p->update([
            'nombres'              => $datos['f_nombres'],
            'apellidos'            => Paciente::combinarApellidos($datos['f_apellido_paterno'], $datos['f_apellido_materno']),
            'sexo'                 => $datos['f_sexo'],
            'telefono'             => $datos['f_telefono'] ?: null,
            'direccion'            => $datos['f_direccion'] ?: null,
            'antecedentes_medicos' => $datos['f_antecedentes_medicos'] ?: null,
            'id_folder'            => $datos['f_id_folder'] ?: null,
        ]);

        $this->modalDatosBasicos = false;
        session()->flash('mensaje', 'Datos del paciente actualizados correctamente.');
    }

    // ===================== MODAL: ELIMINAR PACIENTE (ficha) =====================
    public function abrirModalEliminar(): void
    {
        $this->modalEliminar = true;
    }

    public function confirmarEliminarPaciente(): void
    {
        $p = $this->pacienteActual;
        if (! $p) {
            return;
        }

        try {
            $p->delete();
            $this->modalEliminar = false;
            session()->flash('mensaje', 'Paciente eliminado correctamente.');
            $this->volverALista();
        } catch (QueryException $e) {
            // ODONTOGRAMA -> PACIENTE no tiene ON DELETE CASCADE en el schema actual.
            $this->modalEliminar = false;
            session()->flash('error', 'No se puede eliminar: el paciente tiene odontogramas registrados. Elimínalos primero o agrega ON DELETE CASCADE a la FK fk_odonto_paciente.');
        }
    }

    // ===================== ODONTOGRAMA: NAVEGACIÓN =====================
    public function irAOdontograma(): void
    {
        $p = $this->pacienteActual;
        if (! $p) {
            return;
        }

        $primero = $p->odontogramas->first();

        if ($primero) {
            $this->seleccionarOdontograma($primero->id_odontograma);
        } else {
            $this->estadoDientes = [];
            $this->detallesOdontograma = [];
            $this->odontogramaSeleccionadoId = null;
        }

        $this->vista = 'odontograma';
    }

    private function resetOdontograma(): void
    {
        $this->odontogramaSeleccionadoId = null;
        $this->estadoDientes = [];
        $this->detallesOdontograma = [];
        $this->od_id_doctor = '';
        $this->od_observaciones = '';
    }

    public function seleccionarOdontograma(int $idOdontograma): void
    {
        $this->odontogramaSeleccionadoId = $idOdontograma;
        $this->cargarDetalles($idOdontograma);
    }

    private function cargarDetalles(int $idOdontograma): void
    {
        $detalles = DetalleOdontograma::where('id_odontograma', $idOdontograma)
            ->with('tratamiento')
            ->orderByDesc('id_detalle')
            ->get();

        $this->detallesOdontograma = $detalles->toArray();

        $estados = [];
        foreach ($detalles as $d) {
            // El primero que aparece por pieza (orden desc por id_detalle) es el más reciente.
            if (! isset($estados[$d->pieza_dental])) {
                $estados[$d->pieza_dental] = [
                    'estado'     => $d->estado,
                    'color'      => $this->coloresEstado[$d->estado] ?? '#9ca3af',
                    'id_detalle' => $d->id_detalle,
                ];
            }
        }
        $this->estadoDientes = $estados;
    }

    // ===================== ODONTOGRAMA: NUEVO REGISTRO =====================
    public function abrirModalNuevoOdontograma(): void
    {
        $this->od_id_doctor = '';
        $this->od_observaciones = '';
        $this->modalNuevoOdontograma = true;
    }

    public function guardarNuevoOdontograma(): void
    {
        $datos = $this->validate([
            'od_id_doctor'     => 'required|exists:oracle.doctor,id_doctor',
            'od_observaciones' => 'nullable|string|max:500',
        ]);

        $p = $this->pacienteActual;
        if (! $p) {
            return;
        }

        $nuevo = Odontograma::create([
            'id_paciente'              => $p->id_paciente,
            'id_doctor'                => $datos['od_id_doctor'],
            'fecha_evaluacion'         => now(),
            'observaciones_generales'  => $datos['od_observaciones'] ?: null,
        ]);

        $this->modalNuevoOdontograma = false;
        $this->seleccionarOdontograma($nuevo->id_odontograma);
        session()->flash('mensaje', 'Odontograma registrado correctamente.');
    }

    // ===================== ODONTOGRAMA: ELIMINAR =====================
    public function confirmarEliminarOdontograma(int $idOdontograma): void
    {
        $this->odontogramaAEliminar = $idOdontograma;
        $this->modalEliminarOdontograma = true;
    }

    public function eliminarOdontograma(): void
    {
        if (! $this->odontogramaAEliminar) {
            return;
        }

        // DETALLE_ODONTOGRAMA tiene ON DELETE CASCADE: se eliminan sus hallazgos automáticamente.
        Odontograma::destroy($this->odontogramaAEliminar);

        $eraElSeleccionado = $this->odontogramaSeleccionadoId === $this->odontogramaAEliminar;
        $this->modalEliminarOdontograma = false;
        $this->odontogramaAEliminar = null;

        if ($eraElSeleccionado) {
            $this->odontogramaSeleccionadoId = null;
            $this->estadoDientes = [];
            $this->detallesOdontograma = [];
        }

        session()->flash('mensaje', 'Odontograma eliminado correctamente.');
    }

    // ===================== ODONTOGRAMA: HALLAZGO POR DIENTE (DETALLE_ODONTOGRAMA) =====================
    public function marcarDiente(string $piezaDental): void
    {
        if (! $this->odontogramaSeleccionadoId) {
            session()->flash('error', 'Selecciona o registra un odontograma antes de marcar un diente.');
            return;
        }

        $this->detalleEditandoId = null;
        $this->dd_pieza_dental = $piezaDental;
        $this->dd_cara = '';
        $this->dd_diagnostico = '';
        $this->dd_id_tratamiento = '';
        $this->dd_estado = 'Por tratar';
        $this->modalDetalleDiente = true;
    }

    public function abrirModalEditarDetalle(int $idDetalle): void
    {
        $d = DetalleOdontograma::find($idDetalle);
        if (! $d) {
            return;
        }

        $this->detalleEditandoId = $d->id_detalle;
        $this->dd_pieza_dental = $d->pieza_dental;
        $this->dd_cara = $d->cara;
        $this->dd_diagnostico = $d->diagnostico;
        $this->dd_id_tratamiento = (string) ($d->id_tratamiento ?? '');
        $this->dd_estado = $d->estado;
        $this->modalDetalleDiente = true;
    }

    public function cerrarModalDetalleDiente(): void
    {
        $this->modalDetalleDiente = false;
        $this->resetValidation();
    }

    public function guardarDetalleDiente(): void
    {
        $datos = $this->validate([
            'dd_pieza_dental'   => 'required|string|max:10',
            'dd_cara'           => 'required|string|max:20',
            'dd_diagnostico'    => 'required|string|max:100',
            'dd_id_tratamiento' => 'nullable|exists:oracle.tratamiento,id_tratamiento',
            'dd_estado'         => 'required|in:Por tratar,En tratamiento,Tratado',
        ]);

        if (! $this->odontogramaSeleccionadoId) {
            return;
        }

        $payload = [
            'id_odontograma' => $this->odontogramaSeleccionadoId,
            'id_tratamiento' => $datos['dd_id_tratamiento'] ?: null,
            'pieza_dental'   => $datos['dd_pieza_dental'],
            'cara'           => $datos['dd_cara'],
            'diagnostico'    => $datos['dd_diagnostico'],
            'estado'         => $datos['dd_estado'],
        ];

        if ($this->detalleEditandoId) {
            DetalleOdontograma::find($this->detalleEditandoId)?->update($payload);
        } else {
            DetalleOdontograma::create($payload);
        }

        $this->modalDetalleDiente = false;
        $this->cargarDetalles($this->odontogramaSeleccionadoId);
        session()->flash('mensaje', 'Hallazgo guardado correctamente.');
    }

    public function eliminarDetalleDiente(int $idDetalle): void
    {
        DetalleOdontograma::destroy($idDetalle);

        if ($this->odontogramaSeleccionadoId) {
            $this->cargarDetalles($this->odontogramaSeleccionadoId);
        }

        session()->flash('mensaje', 'Hallazgo eliminado correctamente.');
    }

    // ===================== LISTADO FILTRADO (vista lista) — todo desde Oracle =====================
    public function getPacientesFiltradosProperty()
    {
        $query = Paciente::query()->with('folder');

        if ($this->buscarNombres !== '') {
            $query->whereRaw('LOWER(nombres) LIKE ?', ['%' . mb_strtolower($this->buscarNombres) . '%']);
        }

        if ($this->buscarApellidoPaterno !== '' || $this->buscarApellidoMaterno !== '') {
            $termino = trim($this->buscarApellidoPaterno . ' ' . $this->buscarApellidoMaterno);
            $query->whereRaw('LOWER(apellidos) LIKE ?', ['%' . mb_strtolower($termino) . '%']);
        }

        if ($this->filtroTratamiento !== '') {
            $idTratamiento = $this->filtroTratamiento;
            $query->whereHas('odontogramas.detalles', function ($q) use ($idTratamiento) {
                $q->where('id_tratamiento', $idTratamiento);
            });
        }

        if ($this->filtroFolder !== '') {
            $query->where('id_folder', $this->filtroFolder);
        }

        if ($this->filtroSexo !== '') {
            $query->where('sexo', $this->filtroSexo);
        }

        match ($this->orden) {
            'antiguo' => $query->orderBy('fecha_registro', 'asc'),
            'az'      => $query->orderBy('nombres', 'asc'),
            'za'      => $query->orderBy('nombres', 'desc'),
            default   => $query->orderBy('fecha_registro', 'desc'),
        };

        return $query->paginate(8);
    }

    public function render()
    {
        return view('livewire.gestion-pacientes', [
            'listaPacientes' => $this->pacientesFiltrados,
            'paciente'       => $this->pacienteActual,
        ]);
    }
}