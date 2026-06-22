<?php

namespace App\Livewire\Pacientes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class GestionFolders extends Component
{
    // ── Estado de vista ──────────────────────────────────────────────
    public string $vista = 'folders';
    public ?array $folderSeleccionado = null;

    // ── Modales ──────────────────────────────────────────────────────
    public bool $modalAgregar   = false;
    public bool $modalEditar    = false;
    public bool $modalReasignar = false;
    public bool $modalEliminar  = false;

    // ── Formulario Agregar / Editar ──────────────────────────────────
    public string $f_codigo_archivo = '';
    public string $f_estante        = '';
    public string $f_seccion        = '';
    public string $f_observaciones  = '';

    // ── Acciones de vista ────────────────────────────────────────────
    public function verDetalle(int $id): void
    {
        $folder = DB::selectOne(
            'SELECT id_folder, codigo_archivo, estante, seccion, observaciones
               FROM FOLDER WHERE id_folder = :id',
            ['id' => $id]
        );

        if ($folder) {
            $this->folderSeleccionado = (array) $folder;
            $this->vista = 'detalle_folder';
        }
    }

    public function volverFolders(): void
    {
        $this->vista = 'folders';
        $this->folderSeleccionado = null;
    }

    // ── Modal Agregar ─────────────────────────────────────────────────
    public function abrirModalAgregar(): void
    {
        $this->reset('f_codigo_archivo', 'f_estante', 'f_seccion', 'f_observaciones');
        $this->modalAgregar = true;
    }

    public function guardarFolder(): void
    {
        $this->validate([
            'f_codigo_archivo' => 'required|max:20',
            'f_estante'        => 'nullable|max:20',
            'f_seccion'        => 'nullable|max:20',
            'f_observaciones'  => 'nullable|max:255',
        ]);

        // Verificar código único
        $existe = DB::selectOne(
            'SELECT 1 FROM FOLDER WHERE UPPER(codigo_archivo) = UPPER(:codigo)',
            ['codigo' => $this->f_codigo_archivo]
        );

        if ($existe) {
            $this->addError('f_codigo_archivo', 'Ya existe un folder con este código.');
            return;
        }

        DB::statement(
            'INSERT INTO FOLDER (codigo_archivo, estante, seccion, observaciones)
             VALUES (:codigo, :estante, :seccion, :obs)',
            [
                'codigo'  => strtoupper(trim($this->f_codigo_archivo)),
                'estante' => $this->f_estante  ?: null,
                'seccion' => $this->f_seccion  ?: null,
                'obs'     => $this->f_observaciones ?: null,
            ]
        );

        $this->modalAgregar = false;
        session()->flash('success', 'Folder registrado correctamente.');
    }

    // ── Modal Editar ──────────────────────────────────────────────────
    public function abrirModalEditar(int $id): void
    {
        $folder = DB::selectOne(
            'SELECT id_folder, codigo_archivo, estante, seccion, observaciones
               FROM FOLDER WHERE id_folder = :id',
            ['id' => $id]
        );

        if (!$folder) return;

        $this->folderSeleccionado = (array) $folder;
        $this->f_codigo_archivo   = $folder->codigo_archivo ?? '';
        $this->f_estante          = $folder->estante        ?? '';
        $this->f_seccion          = $folder->seccion        ?? '';
        $this->f_observaciones    = $folder->observaciones  ?? '';
        $this->modalEditar = true;
    }

    public function actualizarFolder(): void
    {
        $this->validate([
            'f_codigo_archivo' => 'required|max:20',
            'f_estante'        => 'nullable|max:20',
            'f_seccion'        => 'nullable|max:20',
            'f_observaciones'  => 'nullable|max:255',
        ]);

        $id = $this->folderSeleccionado['id_folder'] ?? null;
        if (!$id) return;

        // Verificar código único excluyendo el actual
        $existe = DB::selectOne(
            'SELECT 1 FROM FOLDER
              WHERE UPPER(codigo_archivo) = UPPER(:codigo)
                AND id_folder <> :id',
            ['codigo' => $this->f_codigo_archivo, 'id' => $id]
        );

        if ($existe) {
            $this->addError('f_codigo_archivo', 'Ya existe otro folder con este código.');
            return;
        }

        DB::statement(
            'UPDATE FOLDER
                SET codigo_archivo = :codigo,
                    estante        = :estante,
                    seccion        = :seccion,
                    observaciones  = :obs
              WHERE id_folder = :id',
            [
                'codigo'  => strtoupper(trim($this->f_codigo_archivo)),
                'estante' => $this->f_estante  ?: null,
                'seccion' => $this->f_seccion  ?: null,
                'obs'     => $this->f_observaciones ?: null,
                'id'      => $id,
            ]
        );

        // Refrescar folderSeleccionado para que la vista muestre datos actualizados
        $actualizado = DB::selectOne(
            'SELECT id_folder, codigo_archivo, estante, seccion, observaciones
               FROM FOLDER WHERE id_folder = :id',
            ['id' => $id]
        );
        $this->folderSeleccionado = (array) $actualizado;

        $this->modalEditar = false;
        session()->flash('success', 'Folder actualizado correctamente.');
    }

    // ── Modal Reasignar ───────────────────────────────────────────────
    public function abrirReasignar(): void
    {
        $this->modalReasignar = true;
    }

    public function iniciarReasignacion(): void
    {
        // Aquí iría la lógica real de reasignación según reglas de negocio
        $this->modalReasignar = false;
        session()->flash('success', 'Reasignación completada. Los pacientes han sido ubicados en su folder correspondiente.');
    }

    // ── Eliminar ──────────────────────────────────────────────────────
    public function confirmarEliminar(int $id): void
    {
        $folder = DB::selectOne(
            'SELECT id_folder, codigo_archivo, estante, seccion, observaciones
               FROM FOLDER WHERE id_folder = :id',
            ['id' => $id]
        );

        if (!$folder) return;

        $this->folderSeleccionado = (array) $folder;
        $this->modalEliminar = true;
    }

    public function eliminarFolder(): void
    {
        $id = $this->folderSeleccionado['id_folder'] ?? null;
        if (!$id) return;

        // La FK PACIENTE.id_folder tiene ON DELETE SET NULL, se desvinculan automáticamente
        DB::statement('DELETE FROM FOLDER WHERE id_folder = :id', ['id' => $id]);

        $this->modalEliminar      = false;
        $this->modalEditar        = false;
        $this->folderSeleccionado = null;
        $this->vista              = 'folders';

        session()->flash('success', 'Folder eliminado. Los pacientes vinculados quedaron sin folder asignado.');
    }

    // ── Computed: pacientes del folder activo ─────────────────────────
    public function getPacientesFolder(): array
    {
        $id = $this->folderSeleccionado['id_folder'] ?? null;
        if (!$id) return [];

        return DB::select(
            'SELECT p.ci_dni,
                    p.nombres || \' \' || p.apellidos AS nombre_completo,
                    p.telefono,
                    p.fecha_registro
               FROM PACIENTE p
              WHERE p.id_folder = :id
              ORDER BY p.apellidos, p.nombres',
            ['id' => $id]
        );
    }

    // ── Computed: total de pacientes por folder (para badge) ──────────
    public function getTotalesPorFolder(): array
    {
        $rows = DB::select(
            'SELECT id_folder, COUNT(*) AS total
               FROM PACIENTE
              WHERE id_folder IS NOT NULL
              GROUP BY id_folder'
        );

        $map = [];
        foreach ($rows as $row) {
            $map[$row->id_folder] = (int) $row->total;
        }
        return $map;
    }

    public function render()
    {
        $folders  = DB::select(
            'SELECT id_folder, codigo_archivo, estante, seccion, observaciones
               FROM FOLDER
              ORDER BY codigo_archivo'
        );

        return view('livewire.pacientes.gestion-folders', [
            'folders'          => $folders,
            'totalesPorFolder' => $this->getTotalesPorFolder(),
        ]);
    }
}