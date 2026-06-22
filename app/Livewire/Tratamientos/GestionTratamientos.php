<?php

namespace App\Livewire\Tratamientos;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class GestionTratamientos extends Component
{
    use WithPagination;

    // ===================== FILTROS DE BÚSQUEDA =====================
    public string $buscar = '';
    public string $filtroEstado = '';
    public string $orden = 'reciente'; // reciente | antiguo | az | za | precio_asc | precio_desc

    // ===================== MODAL / FORMULARIO =====================
    public bool $modalAbierto = false;
    public bool $modoEdicion = false;
    public ?int $idTratamientoEditando = null;

    // Campos del formulario
    public string $nombre = '';
    public string $descripcion = '';
    public string $costo_referencial = '';
    public int $estado = 1;

    // Reinicia la paginación cuando cambian los filtros en tiempo real
    public function updating($propiedad): void
    {
        if (in_array($propiedad, ['buscar', 'filtroEstado', 'orden'])) {
            $this->resetPage();
        }
    }

    // ===================== REGLAS DE VALIDACIÓN =====================
    protected function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'costo_referencial' => 'required|numeric|min:0',
            'estado' => 'required|in:0,1',
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre del tratamiento es obligatorio.',
        'costo_referencial.required' => 'El costo referencial es obligatorio.',
        'costo_referencial.numeric' => 'El costo debe ser un número válido.',
        'costo_referencial.min' => 'El costo no puede ser negativo.',
    ];

    // ===================== ACCIONES DE LA UI =====================
    public function abrirModalCrear(): void
    {
        $this->resetFormulario();
        $this->modoEdicion = false;
        $this->modalAbierto = true;
    }

    public function abrirModalEditar(int $idTratamiento): void
    {
        $this->resetValidation();
        
        // Buscar el registro en Oracle
        $tratamiento = DB::selectOne("SELECT * FROM TRATAMIENTO WHERE id_tratamiento = :id", ['id' => $idTratamiento]);

        if (!$tratamiento) {
            session()->flash('error', 'El tratamiento no existe en la base de datos.');
            return;
        }

        // NORMALIZACIÓN: Convertir el objeto a array y luego a minúsculas para consistencia
        $data = array_change_key_case((array) $tratamiento, CASE_LOWER);

        $this->idTratamientoEditando = $data['id_tratamiento'];
        $this->nombre = $data['nombre'];
        $this->descripcion = $data['descripcion'] ?? '';
        $this->costo_referencial = (string) $data['costo_referencial'];
        $this->estado = (int) $data['estado'];

        $this->modoEdicion = true;
        $this->modalAbierto = true;
    }

    public function cerrarModal(): void
    {
        $this->modalAbierto = false;
        $this->resetFormulario();
    }

    public function guardarTratamiento(): void
    {
        $this->validate();

        if ($this->modoEdicion) {
            // UPDATE en Oracle
            DB::update("
                UPDATE TRATAMIENTO 
                SET nombre = :nombre, descripcion = :descripcion, costo_referencial = :costo, estado = :estado
                WHERE id_tratamiento = :id
            ", [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'costo' => $this->costo_referencial,
                'estado' => $this->estado,
                'id' => $this->idTratamientoEditando
            ]);

            $mensaje = 'Tratamiento actualizado correctamente.';
        } else {
            // INSERT en Oracle
            DB::insert("
                INSERT INTO TRATAMIENTO (nombre, descripcion, costo_referencial, estado)
                VALUES (:nombre, :descripcion, :costo, :estado)
            ", [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'costo' => $this->costo_referencial,
                'estado' => $this->estado
            ]);

            $mensaje = 'Tratamiento registrado correctamente.';
        }

        session()->flash('mensaje', $mensaje);
        $this->cerrarModal();
    }

    public function eliminarTratamiento(int $idTratamiento): void
    {
        DB::delete("DELETE FROM TRATAMIENTO WHERE id_tratamiento = :id", ['id' => $idTratamiento]);
        session()->flash('mensaje', 'Tratamiento eliminado correctamente.');
    }

    public function cambiarEstado(int $idTratamiento): void
    {
        DB::update("
            UPDATE TRATAMIENTO 
            SET estado = CASE WHEN estado = 1 THEN 0 ELSE 1 END 
            WHERE id_tratamiento = :id
        ", ['id' => $idTratamiento]);

        session()->flash('mensaje', 'Estado del tratamiento actualizado.');
    }

    private function resetFormulario(): void
    {
        $this->reset(['nombre', 'descripcion', 'costo_referencial', 'idTratamientoEditando', 'estado']);
        $this->resetValidation();
    }

    public function render()
    {
        // 1. Construir la consulta SQL dinámica para Oracle
        $queryStr = "SELECT * FROM TRATAMIENTO WHERE 1=1";
        $params = [];

        // Filtro de búsqueda por texto
        if ($this->buscar !== '') {
            $queryStr .= " AND (UPPER(nombre) LIKE :buscar_nom OR UPPER(descripcion) LIKE :buscar_desc)";
            $params['buscar_nom'] = '%' . strtoupper($this->buscar) . '%';
            $params['buscar_desc'] = '%' . strtoupper($this->buscar) . '%';
        }

        // Filtro de estado
        if ($this->filtroEstado !== '') {
            $queryStr .= " AND estado = :estado";
            $params['estado'] = (int) $this->filtroEstado;
        }

        // Definir Ordenamiento
        switch ($this->orden) {
            case 'antiguo':
                $queryStr .= " ORDER BY id_tratamiento ASC";
                break;
            case 'az':
                $queryStr .= " ORDER BY nombre ASC";
                break;
            case 'za':
                $queryStr .= " ORDER BY nombre DESC";
                break;
            case 'precio_asc':
                $queryStr .= " ORDER BY costo_referencial ASC";
                break;
            case 'precio_desc':
                $queryStr .= " ORDER BY costo_referencial DESC";
                break;
            default:
                $queryStr .= " ORDER BY id_tratamiento DESC";
                break;
        }

        // 2. Obtener los registros desde Oracle
        $todosLosTratamientos = DB::select($queryStr, $params);
        
        // NORMALIZACIÓN: Convertir cada objeto a array y luego a minúsculas
        $todosLosTratamientos = array_map(function($item) {
            return (object) array_change_key_case((array) $item, CASE_LOWER);
        }, $todosLosTratamientos);
        
        $coleccion = collect($todosLosTratamientos);

        // 3. Paginación
        $porPagina = 5;
        $paginaActual = $this->paginators['page'] ?? 1;
        
        $itemsPaginados = $coleccion->slice(($paginaActual - 1) * $porPagina, $porPagina)->all();
        
        $paginador = new LengthAwarePaginator(
            $itemsPaginados,
            $coleccion->count(),
            $porPagina,
            $paginaActual,
            ['path' => url()->current()]
        );

        return view('livewire.tratamientos.gestion-tratamientos', [
            'listaTratamientos' => $paginador,
        ]);
    }
}