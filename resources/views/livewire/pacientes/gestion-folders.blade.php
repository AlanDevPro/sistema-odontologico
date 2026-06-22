<div class="gf-wrap" x-data>

    {{-- ═══════════════════════════════════════════════════════════════
         ESTILOS
    ════════════════════════════════════════════════════════════════ --}}
    <style>
        .gf-wrap { font-family: 'Segoe UI', sans-serif; color: #1e293b; }

        /* ── Cabecera ── */
        .gf-header          { display:flex; align-items:center; justify-content:space-between;
                              margin-bottom:1.5rem; flex-wrap:wrap; gap:.75rem; }
        .gf-title           { font-size:1.35rem; font-weight:700; color:#1e293b; }
        .gf-breadcrumb      { font-size:.8rem; color:#64748b; margin-top:.15rem; }

        /* ── Botones ── */
        .btn-primary        { display:inline-flex; align-items:center; gap:.45rem;
                              background:#f97316; color:#fff; border:none; border-radius:10px;
                              padding:.55rem 1.1rem; font-size:.88rem; font-weight:600;
                              cursor:pointer; transition:background .2s; }
        .btn-primary:hover  { background:#ea6c05; }
        .btn-secondary      { display:inline-flex; align-items:center; gap:.45rem;
                              background:#e2e8f0; color:#475569; border:none; border-radius:10px;
                              padding:.55rem 1.1rem; font-size:.88rem; font-weight:600;
                              cursor:pointer; transition:background .2s; }
        .btn-secondary:hover{ background:#cbd5e1; }
        .btn-danger         { display:inline-flex; align-items:center; gap:.45rem;
                              background:#fee2e2; color:#dc2626; border:none; border-radius:10px;
                              padding:.55rem 1.1rem; font-size:.88rem; font-weight:600;
                              cursor:pointer; transition:background .2s; }
        .btn-danger:hover   { background:#fecaca; }
        .btn-blue           { display:inline-flex; align-items:center; gap:.45rem;
                              background:#2563eb; color:#fff; border:none; border-radius:10px;
                              padding:.55rem 1.1rem; font-size:.88rem; font-weight:600;
                              cursor:pointer; transition:background .2s; }
        .btn-blue:hover     { background:#1d4ed8; }
        .btn-sm             { padding:.38rem .8rem; font-size:.78rem; }

        /* ── Flash ── */
        .gf-flash           { background:#dcfce7; border-left:4px solid #16a34a; color:#15803d;
                              padding:.7rem 1rem; border-radius:8px; margin-bottom:1rem;
                              font-size:.87rem; display:flex; align-items:center; gap:.5rem; }

        /* ── Grid de Folders ── */
        .gf-grid            { display:grid;
                              grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                              gap:1rem; }
        .folder-card        { background:#fff; border-radius:14px; padding:1rem 1.1rem;
                              box-shadow:0 2px 10px rgba(0,0,0,.07);
                              display:flex; flex-direction:column; gap:.5rem;
                              transition:transform .18s, box-shadow .18s; cursor:default; }
        .folder-card:hover  { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.1); }
        .fc-codigo          { font-size:1.05rem; font-weight:700; color:#1e293b; }
        .fc-meta            { font-size:.8rem; color:#64748b; display:flex; flex-direction:column; gap:.15rem; }
        .fc-meta span       { display:flex; align-items:center; gap:.35rem; }
        .fc-actions         { display:flex; justify-content:space-between; align-items:center;
                              margin-top:.35rem; }
        .fc-edit-btn        { background:#f97316; border:none; border-radius:8px; color:#fff;
                              width:36px; height:36px; display:flex; align-items:center;
                              justify-content:center; cursor:pointer; font-size:1rem;
                              transition:background .2s; }
        .fc-edit-btn:hover  { background:#ea6c05; }
        .fc-ver-link        { font-size:.75rem; color:#2563eb; font-weight:600; cursor:pointer;
                              text-decoration:underline; }

        /* Badge de pacientes */
        .fc-badge           { display:inline-block; background:#fff7ed; color:#ea580c;
                              border-radius:20px; padding:.15rem .55rem;
                              font-size:.7rem; font-weight:700; }

        /* ── Empty grid ── */
        .gf-empty-grid      { text-align:center; padding:3rem; color:#94a3b8;
                              background:#fff; border-radius:14px;
                              box-shadow:0 2px 10px rgba(0,0,0,.07); }
        .gf-empty-grid svg  { width:48px; opacity:.4; margin-bottom:.75rem; }

        /* ── Detalle Folder ── */
        .detalle-header     { background:#fff; border-radius:14px; padding:1.2rem 1.5rem;
                              box-shadow:0 2px 10px rgba(0,0,0,.07); margin-bottom:1.2rem;
                              display:flex; align-items:flex-start; gap:1rem; flex-wrap:wrap;
                              justify-content:space-between; }
        .dh-info h2         { font-size:1.2rem; font-weight:700; color:#1e293b; margin:0 0 .4rem; }
        .dh-info p          { font-size:.82rem; color:#64748b; margin:0 0 .2rem; }
        .badge              { display:inline-block; padding:.25rem .65rem; border-radius:20px;
                              font-size:.75rem; font-weight:700; }
        .badge-orange       { background:#fff7ed; color:#ea580c; }
        .badge-blue         { background:#eff6ff; color:#2563eb; }
        .badge-slate        { background:#f1f5f9; color:#475569; }

        /* Tabla */
        .gf-table-wrap      { background:#fff; border-radius:14px;
                              box-shadow:0 2px 10px rgba(0,0,0,.07); overflow:hidden; }
        .gf-table           { width:100%; border-collapse:collapse; font-size:.87rem; }
        .gf-table thead tr  { background:#f8fafc; }
        .gf-table th        { padding:.75rem 1rem; text-align:left; font-weight:700;
                              color:#64748b; font-size:.78rem; text-transform:uppercase;
                              letter-spacing:.04em; border-bottom:1px solid #e2e8f0; }
        .gf-table td        { padding:.7rem 1rem; border-bottom:1px solid #f1f5f9;
                              color:#334155; }
        .gf-table tr:last-child td { border-bottom:none; }
        .gf-table tr:hover td      { background:#f8fafc; }
        .empty-state        { text-align:center; padding:3rem; color:#94a3b8; }
        .empty-state svg    { width:48px; opacity:.4; margin-bottom:.75rem; }

        /* ── Modales ── */
        .modal-overlay      { position:fixed; inset:0; background:rgba(15,23,42,.55);
                              display:flex; align-items:center; justify-content:center;
                              z-index:9999; padding:1rem; }
        .modal-box          { background:#fff; border-radius:18px; width:100%; max-width:480px;
                              box-shadow:0 20px 60px rgba(0,0,0,.25); overflow:hidden;
                              animation:modalIn .22s ease; }
        @keyframes modalIn  { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:scale(1)} }
        .modal-head         { background:#f97316; color:#fff; padding:1.1rem 1.5rem;
                              font-size:1.05rem; font-weight:700; text-align:center; }
        .modal-body         { padding:1.5rem; display:flex; flex-direction:column; gap:1rem; }
        .modal-footer       { padding:1rem 1.5rem; display:flex; justify-content:flex-end;
                              gap:.75rem; border-top:1px solid #f1f5f9; }
        .form-group         { display:flex; flex-direction:column; gap:.35rem; }
        .form-group label   { font-size:.82rem; font-weight:600; color:#475569; }
        .form-control       { border:2px solid #e2e8f0; border-radius:10px; padding:.55rem .9rem;
                              font-size:.9rem; outline:none; transition:border .2s; width:100%;
                              box-sizing:border-box; background:#fff; }
        .form-control:focus { border-color:#f97316; }
        .form-row           { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-error         { font-size:.75rem; color:#dc2626; margin-top:.15rem; }
        .form-hint          { font-size:.75rem; color:#94a3b8; margin-top:.1rem; }

        /* QR placeholder */
        .qr-area            { display:flex; flex-direction:column; align-items:center; gap:.5rem;
                              margin:.25rem 0; }
        .qr-img             { width:90px; height:90px; border:2px dashed #e2e8f0;
                              border-radius:8px; display:flex; align-items:center;
                              justify-content:center; background:#f8fafc; }

        /* Reasignar descripción */
        .reasignar-desc     { font-size:.85rem; color:#475569; line-height:1.65;
                              text-align:justify; background:#f8fafc; border-radius:10px;
                              padding:1rem; }

        /* Modal confirmar */
        .modal-confirm-icon { text-align:center; font-size:3rem; margin-bottom:.5rem; }
        .modal-confirm-text { text-align:center; color:#475569; font-size:.9rem;
                              line-height:1.6; }
    </style>

    {{-- ═══════════════════════════════════════════════════════════════
         FLASH MESSAGE
    ════════════════════════════════════════════════════════════════ --}}
    @if(session('success'))
        <div class="gf-flash">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2.5" stroke="currentColor" style="width:16px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════
         VISTA 1: BIBLIOTECA DE FOLDERES
    ══════════════════════════════════════════════════════════════ --}}
    @if($vista === 'folders')

        <div class="gf-header">
            <div>
                <div class="gf-title">Biblioteca de Folderes</div>
                <div class="gf-breadcrumb">/ Pacientes / Folders</div>
            </div>
            <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
                <button class="btn-secondary" wire:click="abrirReasignar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" style="width:16px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0
                                 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0
                                 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Reasignar pacientes
                </button>
                <button class="btn-primary" wire:click="abrirModalAgregar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.5" stroke="currentColor" style="width:16px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Nuevo Folder
                </button>
            </div>
        </div>

        @if(count($folders) > 0)
            <div class="gf-grid">
                @foreach($folders as $folder)
                <div class="folder-card">
                    <div class="fc-codigo">{{ $folder->codigo_archivo }}</div>
                    <div class="fc-meta">
                        @if($folder->estante)
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.8" stroke="#94a3b8" style="width:13px;flex-shrink:0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25
                                         2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75
                                         15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25
                                         2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1
                                         3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25
                                         2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18
                                         10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5
                                         15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1
                                         2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25
                                         2.25 0 0 1 13.5 18v-2.25Z"/>
                            </svg>
                            Estante: {{ $folder->estante }}
                        </span>
                        @endif
                        @if($folder->seccion)
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.8" stroke="#94a3b8" style="width:13px;flex-shrink:0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375
                                         0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375
                                         0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375
                                         0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                            Sección: {{ $folder->seccion }}
                        </span>
                        @endif
                        @if(!$folder->estante && !$folder->seccion)
                        <span style="color:#cbd5e1;font-style:italic;">Sin ubicación asignada</span>
                        @endif
                    </div>

                    {{-- Badge de pacientes --}}
                    @php $total = $totalesPorFolder[$folder->id_folder] ?? 0; @endphp
                    <div>
                        <span class="fc-badge">
                            {{ $total }} {{ $total === 1 ? 'paciente' : 'pacientes' }}
                        </span>
                    </div>

                    <div class="fc-actions">
                        <span class="fc-ver-link" wire:click="verDetalle({{ $folder->id_folder }})">
                            Ver pacientes
                        </span>
                        <button class="fc-edit-btn"
                                wire:click="abrirModalEditar({{ $folder->id_folder }})"
                                title="Editar folder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="2.2" stroke="currentColor" style="width:15px">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652
                                         2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5
                                         4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="gf-empty-grid">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" style="display:block;margin:0 auto .75rem;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5
                             0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227
                             1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0
                             0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5
                             1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25
                             2.25 0 0 1 20.25 9v.776"/>
                </svg>
                <p style="margin:0;font-size:.9rem;">No hay folders registrados aún.</p>
                <p style="margin:.35rem 0 0;font-size:.8rem;">
                    Crea el primer folder con el botón <strong>Nuevo Folder</strong>.
                </p>
            </div>
        @endif

    @endif


    {{-- ══════════════════════════════════════════════════════════════
         VISTA 2: DETALLE DE UN FOLDER
    ══════════════════════════════════════════════════════════════ --}}
    @if($vista === 'detalle_folder' && $folderSeleccionado)

        <div class="gf-header">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <button class="btn-secondary btn-sm" wire:click="volverFolders">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.5" stroke="currentColor" style="width:14px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Volver
                </button>
                <div>
                    <div class="gf-title">Folder: {{ $folderSeleccionado['codigo_archivo'] }}</div>
                    <div class="gf-breadcrumb">
                        / Pacientes / Folders / {{ $folderSeleccionado['codigo_archivo'] }}
                    </div>
                </div>
            </div>
            <button class="btn-primary"
                    wire:click="abrirModalEditar({{ $folderSeleccionado['id_folder'] }})">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2.2" stroke="currentColor" style="width:15px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652
                             2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5
                             4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                </svg>
                Modificar Folder
            </button>
        </div>

        {{-- Info del folder --}}
        <div class="detalle-header">
            <div class="dh-info">
                <h2>{{ $folderSeleccionado['codigo_archivo'] }}</h2>
                @if($folderSeleccionado['estante'])
                    <p>Estante: <strong>{{ $folderSeleccionado['estante'] }}</strong></p>
                @endif
                @if($folderSeleccionado['seccion'])
                    <p>Sección: <strong>{{ $folderSeleccionado['seccion'] }}</strong></p>
                @endif
                @if($folderSeleccionado['observaciones'])
                    <p style="margin-top:.3rem;font-style:italic;">
                        {{ $folderSeleccionado['observaciones'] }}
                    </p>
                @endif
            </div>
            @php $pacs = $this->getPacientesFolder(); @endphp
            <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
                <span class="badge badge-blue">
                    {{ count($pacs) }} {{ count($pacs) === 1 ? 'paciente' : 'pacientes' }}
                </span>
            </div>
        </div>

        {{-- Tabla de pacientes --}}
        <div class="gf-table-wrap">
            @if(count($pacs))
            <table class="gf-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CI / DNI</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pacs as $i => $pac)
                    <tr>
                        <td style="color:#94a3b8;font-size:.78rem;">{{ $i + 1 }}</td>
                        <td><strong>{{ $pac->ci_dni }}</strong></td>
                        <td>{{ $pac->nombre_completo }}</td>
                        <td>{{ $pac->telefono ?? '—' }}</td>
                        <td style="font-size:.8rem;color:#64748b;">
                            {{ $pac->fecha_registro
                                ? \Carbon\Carbon::parse($pac->fecha_registro)->format('d/m/Y')
                                : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     style="display:block;margin:0 auto .75rem;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501
                             20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12
                             21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
                <p style="margin:0;">Este folder aún no tiene pacientes asignados.</p>
            </div>
            @endif
        </div>

    @endif


    {{-- ══════════════════════════════════════════════════════════════
         MODAL: AGREGAR FOLDER
    ══════════════════════════════════════════════════════════════ --}}
    @if($modalAgregar)
    <div class="modal-overlay" wire:click.self="$set('modalAgregar', false)">
        <div class="modal-box">
            <div class="modal-head">Registrar Nuevo Folder</div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Código de archivo <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control"
                           wire:model="f_codigo_archivo"
                           placeholder="Ej: FLD-01, ORT-01"
                           style="text-transform:uppercase;">
                    <span class="form-hint">Se guardará en mayúsculas. Debe ser único.</span>
                    @error('f_codigo_archivo') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Estante</label>
                        <input type="text" class="form-control"
                               wire:model="f_estante"
                               placeholder="Ej: A, B, 1, 2">
                        @error('f_estante') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Sección</label>
                        <input type="text" class="form-control"
                               wire:model="f_seccion"
                               placeholder="Ej: Superior, Inferior">
                        @error('f_seccion') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones</label>
                    <textarea class="form-control" wire:model="f_observaciones"
                              rows="2" placeholder="Notas adicionales sobre este folder..."
                              style="resize:vertical;"></textarea>
                    @error('f_observaciones') <span class="form-error">{{ $message }}</span> @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn-secondary" wire:click="$set('modalAgregar', false)">
                    Cancelar
                </button>
                <button class="btn-primary" wire:click="guardarFolder">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.2" stroke="currentColor" style="width:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════
         MODAL: EDITAR / MODIFICAR FOLDER
    ══════════════════════════════════════════════════════════════ --}}
    @if($modalEditar && $folderSeleccionado)
    <div class="modal-overlay" wire:click.self="$set('modalEditar', false)">
        <div class="modal-box">
            <div class="modal-head">
                Modificar Folder
                <div style="font-weight:400;font-size:.9rem;margin-top:.2rem;">
                    {{ $folderSeleccionado['codigo_archivo'] }}
                </div>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Código de archivo <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control"
                           wire:model="f_codigo_archivo"
                           style="text-transform:uppercase;">
                    @error('f_codigo_archivo') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Estante</label>
                        <input type="text" class="form-control" wire:model="f_estante">
                        @error('f_estante') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Sección</label>
                        <input type="text" class="form-control" wire:model="f_seccion">
                        @error('f_seccion') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones</label>
                    <textarea class="form-control" wire:model="f_observaciones"
                              rows="2" style="resize:vertical;"></textarea>
                    @error('f_observaciones') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                {{-- QR placeholder --}}
                <div class="qr-area">
                    <div class="qr-img">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             fill="none" stroke="#94a3b8" stroke-width="1.5" style="width:38px">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="5" y="5" width="3" height="3" fill="#94a3b8" stroke="none"/>
                            <rect x="16" y="5" width="3" height="3" fill="#94a3b8" stroke="none"/>
                            <rect x="5" y="16" width="3" height="3" fill="#94a3b8" stroke="none"/>
                            <path d="M14 14h2v2h-2zm4 0h2v2h-2zm-4 4h2v2h-2zm4 0h2v2h-2z"
                                  fill="#94a3b8" stroke="none"/>
                        </svg>
                    </div>
                    <span style="font-size:.72rem;color:#94a3b8;">QR del folder</span>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn-danger"
                        wire:click="confirmarEliminar({{ $folderSeleccionado['id_folder'] }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.2" stroke="currentColor" style="width:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
                                 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244
                                 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456
                                 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114
                                 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964
                                 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09
                                 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Eliminar
                </button>
                <button class="btn-blue" wire:click="actualizarFolder">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.2" stroke="currentColor" style="width:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652
                                 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5
                                 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════
         MODAL: REASIGNAR PACIENTES
    ══════════════════════════════════════════════════════════════ --}}
    @if($modalReasignar)
    <div class="modal-overlay" wire:click.self="$set('modalReasignar', false)">
        <div class="modal-box">
            <div class="modal-head">Reasignar pacientes a su folder</div>
            <div class="modal-body">
                <p class="reasignar-desc">
                    Al iniciar la reasignación, el sistema revisará todos los pacientes que
                    actualmente no tienen un folder asignado o que se encuentran en un folder
                    incorrecto, y los ubicará en el folder que corresponda según las reglas
                    de negocio definidas. Esta operación no elimina ni modifica datos clínicos.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" wire:click="$set('modalReasignar', false)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" style="width:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                    Cerrar
                </button>
                <button class="btn-blue" wire:click="iniciarReasignacion">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" style="width:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125
                                 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0
                                 1-1.667-.986V5.653Z"/>
                    </svg>
                    Iniciar
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════
         MODAL: CONFIRMAR ELIMINACIÓN
    ══════════════════════════════════════════════════════════════ --}}
    @if($modalEliminar && $folderSeleccionado)
    <div class="modal-overlay" wire:click.self="$set('modalEliminar', false)">
        <div class="modal-box">
            <div class="modal-head" style="background:#dc2626;">Eliminar Folder</div>
            <div class="modal-body">
                <div class="modal-confirm-icon">🗂️</div>
                <p class="modal-confirm-text">
                    ¿Estás seguro de que deseas eliminar el folder
                    <strong>{{ $folderSeleccionado['codigo_archivo'] }}</strong>?<br><br>
                    Los pacientes vinculados a este folder quedarán
                    <strong>sin folder asignado</strong>, pero sus datos clínicos
                    no serán afectados. Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" wire:click="$set('modalEliminar', false)">
                    Cancelar
                </button>
                <button class="btn-danger" wire:click="eliminarFolder">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.2" stroke="currentColor" style="width:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
                                 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244
                                 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456
                                 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114
                                 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964
                                 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09
                                 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>