{{-- resources/views/rh/contratacion/index.blade.php --}}
{{-- Lista de candidatos | Fidem RRHH | Laravel 5.7 | PHP 7.1+ --}}

@extends("theme.$theme.layout")

@section('titulo', 'Candidatos en Proceso — Fidem RRHH')

@section('styles')
<style>
:root {
    --azul:   #1A3A5C; --azul-dk: #0f2540; --dorado: #C8A96E;
    --verde:  #2D7A5F; --rojo:    #C0392B; --naranja: #B85C2A;
    --morado: #5B3D8A; --bg:      #F0F4F8; --card:   #FFFFFF;
    --texto:  #1C2B3A; --muted:   #6B7F92; --borde:  #DDE4EC;
    --r: 12px; --sombra: 0 2px 16px rgba(26,58,92,0.09);
}
* { box-sizing: border-box; }
body { background: var(--bg); }

.topbar {
    background: linear-gradient(135deg, var(--azul-dk), var(--azul));
    padding: 20px 32px; display: flex; align-items: center; gap: 16px;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 4px 20px rgba(15,37,64,0.2);
}
.topbar-logo { width:42px; height:42px; background:var(--dorado); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; }
.topbar-info h1 { color:#fff; font-size:16px; font-weight:700; margin:0 0 2px; }
.topbar-info p  { color:var(--dorado); font-size:11px; margin:0; text-transform:uppercase; letter-spacing:1px; }
.btn-nuevo { margin-left:auto; background:var(--dorado); color:var(--azul-dk); border:none; padding:9px 20px; border-radius:20px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:opacity .15s; }
.btn-nuevo:hover { opacity:.85; color:var(--azul-dk); }

.contenido { padding: 28px 32px; max-width: 1200px; margin: 0 auto; }

.stats-row { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:14px; margin-bottom:24px; }
.stat-card { background:var(--card); border:1px solid var(--borde); border-radius:var(--r); padding:16px 18px; box-shadow:var(--sombra); }
.stat-card .num { font-size:28px; font-weight:800; color:var(--azul); }
.stat-card .lbl { font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }
.stat-card.verde  .num { color:var(--verde); }
.stat-card.morado .num { color:var(--morado); }

.filtros { background:var(--card); border:1px solid var(--borde); border-radius:var(--r); padding:14px 18px; margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:center; box-shadow:var(--sombra); }
.filtro-input { padding:8px 14px; border:1.5px solid var(--borde); border-radius:8px; font-size:13px; color:var(--texto); outline:none; font-family:inherit; transition:border-color .15s; min-width:160px; }
.filtro-input:focus { border-color:var(--azul); }
.btn-filtrar { padding:8px 18px; background:var(--azul); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
.btn-limpiar { padding:8px 14px; background:#fff; color:var(--muted); border:1.5px solid var(--borde); border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; }

.tabla-wrap { background:var(--card); border:1px solid var(--borde); border-radius:var(--r); overflow:hidden; box-shadow:var(--sombra); }
table { width:100%; border-collapse:collapse; }
thead th { background:var(--azul); color:#fff; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; padding:12px 16px; text-align:left; }
tbody tr { transition:background .12s; }
tbody tr:hover { background:#F5F8FC; }
tbody td { padding:13px 16px; font-size:13px; color:var(--texto); border-top:1px solid var(--borde); vertical-align:middle; }

.avatar-sm { width:34px; height:34px; border-radius:8px; background:var(--azul); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; margin-right:10px; vertical-align:middle; }
.avatar-sm.admin { background:var(--morado); }
.nombre-cargo { display:inline-block; vertical-align:middle; }
.nombre-cargo .nombre { font-weight:600; font-size:13px; color:var(--texto); display:block; }
.nombre-cargo .cargo  { font-size:11px; color:var(--muted); }

.badge { display:inline-block; padding:3px 10px; border-radius:14px; font-size:10px; font-weight:700; }
.badge-proceso   { background:#EEF3F9; color:var(--azul); }
.badge-aprobado  { background:#e8f5f0; color:var(--verde); }
.badge-rechazado { background:#fdecea; color:var(--rojo); }
.badge-vinculado { background:#fdf6ec; color:var(--naranja); }
.badge-asist     { background:#EEF3F9; color:var(--azul); }
.badge-admin     { background:#F0EBF8; color:var(--morado); }

.barra-mini-wrap { background:#EEF3F9; border-radius:6px; height:6px; width:80px; overflow:hidden; display:inline-block; vertical-align:middle; margin-left:6px; }
.barra-mini-fill { height:100%; border-radius:6px; background:var(--azul); }
.fase-badge { display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:600; }
.fase-dot   { width:8px; height:8px; border-radius:50%; background:var(--azul); }

.btn-ver { padding:6px 14px; background:var(--azul); color:#fff; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; transition:opacity .15s; display:inline-block; }
.btn-ver:hover { opacity:.8; color:#fff; }
.btn-ver.morado { background:var(--morado); }

.empty { text-align:center; padding:48px 20px; color:var(--muted); }
.empty .icon { font-size:40px; margin-bottom:12px; display:block; }

/* ── MODAL ── */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,37,64,0.6); z-index:500; align-items:center; justify-content:center; }
.modal-overlay.visible { display:flex; }
.modal-box { background:#fff; border-radius:16px; padding:32px; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(15,37,64,0.25); animation:modalIn .25s ease; }
@keyframes modalIn { from{opacity:0;transform:scale(0.95)} to{opacity:1;transform:scale(1)} }
.modal-box h2 { font-size:18px; font-weight:700; color:var(--azul); margin:0 0 20px; }
.form-group { margin-bottom:14px; }
.form-group label { display:block; font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
.form-control { width:100%; padding:10px 14px; border:1.5px solid var(--borde); border-radius:8px; font-size:14px; color:var(--texto); outline:none; transition:border-color .15s; font-family:inherit; }
.form-control:focus { border-color:var(--azul); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.modal-footer { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn-cancel  { padding:10px 20px; border:1.5px solid var(--borde); border-radius:8px; background:#fff; color:var(--muted); font-size:13px; cursor:pointer; }
.btn-guardar { padding:10px 24px; border:none; border-radius:8px; background:var(--azul); color:#fff; font-size:13px; font-weight:600; cursor:pointer; }

.toast-container { position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
.toast { background:#0f2540; color:#fff; padding:12px 18px; border-radius:10px; font-size:13px; display:flex; align-items:center; gap:10px; box-shadow:0 8px 24px rgba(15,37,64,0.3); animation:toastIn .3s ease; pointer-events:all; border-left:4px solid var(--dorado); max-width:300px; }
.toast.verde { border-left-color:var(--verde); }
.toast.rojo  { border-left-color:var(--rojo); }
@keyframes toastIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
.spinner { display:inline-block; width:13px; height:13px; border:2px solid rgba(255,255,255,0.4); border-top-color:#fff; border-radius:50%; animation:spin .6s linear infinite; }
@keyframes spin { to{transform:rotate(360deg)} }

@media(max-width:700px) {
    .contenido { padding:16px; }
    .topbar { padding:16px; }
    .form-row { grid-template-columns:1fr; }
    table thead th:nth-child(4),
    table thead th:nth-child(5),
    table tbody td:nth-child(4),
    table tbody td:nth-child(5) { display:none; }
}
</style>
@endsection

@section('contenido')

<div class="topbar">
    <div class="topbar-logo">🏥</div>
    <div class="topbar-info">
        <h1>Fidem Clínica del Dolor</h1>
        <p>Coordinación RRHH — Candidatos en Proceso</p>
    </div>
    <button class="btn-nuevo" onclick="abrirModal()">＋ Nuevo candidato</button>
</div>

<div class="contenido">

    {{-- STATS --}}
    @php
        $totalAll   = $candidatos->total();
        $enProceso  = $candidatos->getCollection()->where('estado','en_proceso')->count();
        $vinculados = $candidatos->getCollection()->where('estado','vinculado')->count();
        $asist      = $candidatos->getCollection()->where('tipo_personal','asistencial')->count();
        $admin      = $candidatos->getCollection()->where('tipo_personal','administrativo')->count();
    @endphp
    <div class="stats-row">
        <div class="stat-card"><div class="num">{{ $totalAll }}</div><div class="lbl">Total candidatos</div></div>
        <div class="stat-card"><div class="num">{{ $enProceso }}</div><div class="lbl">En proceso</div></div>
        <div class="stat-card verde"><div class="num">{{ $vinculados }}</div><div class="lbl">Vinculados</div></div>
        <div class="stat-card"><div class="num">{{ $asist }}</div><div class="lbl">Asistencial</div></div>
        <div class="stat-card morado"><div class="num">{{ $admin }}</div><div class="lbl">Administrativo</div></div>
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('rh.contratacion.index') }}" class="filtros">
        <input type="text" name="q" class="filtro-input" placeholder="🔍 Nombre, cédula o cargo..." value="{{ request('q') }}">
        <select name="tipo" class="filtro-input" style="max-width:180px">
            <option value="">Todos los tipos</option>
            <option value="asistencial"    {{ request('tipo')==='asistencial'    ? 'selected' : '' }}>Asistencial</option>
            <option value="administrativo" {{ request('tipo')==='administrativo' ? 'selected' : '' }}>Administrativo</option>
        </select>
        <select name="estado" class="filtro-input" style="max-width:180px">
            <option value="">Todos los estados</option>
            <option value="en_proceso" {{ request('estado')==='en_proceso' ? 'selected' : '' }}>En proceso</option>
            <option value="aprobado"   {{ request('estado')==='aprobado'   ? 'selected' : '' }}>Aprobado</option>
            <option value="vinculado"  {{ request('estado')==='vinculado'  ? 'selected' : '' }}>Vinculado</option>
            <option value="rechazado"  {{ request('estado')==='rechazado'  ? 'selected' : '' }}>Rechazado</option>
        </select>
        <button type="submit" class="btn-filtrar">Filtrar</button>
        <a href="{{ route('rh.contratacion.index') }}" class="btn-limpiar">Limpiar</a>
    </form>

    {{-- TABLA --}}
    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Fase</th>
                    <th>Progreso</th>
                    <th>Inicio</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidatos as $c)
                @php $esAdminRow = ($c->tipo_personal === 'administrativo'); @endphp
                <tr>
                    <td>
                        <span class="avatar-sm {{ $esAdminRow ? 'admin' : '' }}">
                            {{ strtoupper(substr($c->nombre_completo, 0, 1)) }}
                        </span>
                        <span class="nombre-cargo">
                            <span class="nombre">{{ $c->nombre_completo }}</span>
                            <span class="cargo">{{ $c->cargo }}{{ $c->area ? ' · '.$c->area : '' }}</span>
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $esAdminRow ? 'badge-admin' : 'badge-asist' }}">
                            {{ $esAdminRow ? '🗂 Admin' : '🩺 Asist.' }}
                        </span>
                    </td>
                    <td>
                        @php $badgeKey = ($c->estado === 'en_proceso') ? 'proceso' : $c->estado; @endphp
                        <span class="badge badge-{{ $badgeKey }}">
                            {{ strtoupper(str_replace('_',' ',$c->estado)) }}
                        </span>
                    </td>
                    <td>
                        <span class="fase-badge">
                            <span class="fase-dot"></span>
                            Fase {{ $c->fase_actual }} / 7
                        </span>
                    </td>
                    <td>
                        <span style="font-size:12px;font-weight:700;color:#1A3A5C">{{ $c->progreso_porcentaje }}%</span>
                        <span class="barra-mini-wrap">
                            <span class="barra-mini-fill" style="width:{{ $c->progreso_porcentaje }}%"></span>
                        </span>
                    </td>
                    <td style="font-size:12px;color:#6B7F92">
                        {{ $c->fecha_inicio_proceso ? $c->fecha_inicio_proceso->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        <a href="{{ route('rh.contratacion.show', $c->id) }}"
                           class="btn-ver {{ $esAdminRow ? 'morado' : '' }}">
                            Ver ruta →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <span class="icon">📋</span>
                            <p>No hay candidatos registrados. ¡Crea el primero!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($candidatos->hasPages())
    <div style="margin-top:20px;display:flex;justify-content:center;">
        {{ $candidatos->appends(request()->query())->links() }}
    </div>
    @endif

</div>

{{-- ══ MODAL NUEVO CANDIDATO ══ --}}
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal-box">
        <h2>➕ Nuevo Candidato</h2>
        <form id="form-nuevo" onsubmit="guardarCandidato(event)">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre completo *</label>
                    <input type="text" name="nombre_completo" class="form-control" required placeholder="Nombre completo">
                </div>
                <div class="form-group">
                    <label>Cédula *</label>
                    <input type="text" name="cedula" class="form-control" required placeholder="N° cédula">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Cargo *</label>
                    <input type="text" name="cargo" class="form-control" required placeholder="Ej: Médico, Auxiliar admin">
                </div>
                <div class="form-group">
                    <label>Tipo de personal *</label>
                    <select name="tipo_personal" class="form-control">
                        <option value="asistencial">Asistencial</option>
                        <option value="administrativo">Administrativo</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Área</label>
                    <input type="text" name="area" class="form-control" placeholder="Ej: Urgencias, Facturación">
                </div>
                <div class="form-group">
                    <label>Fecha inicio proceso</label>
                    <input type="date" name="fecha_inicio_proceso" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <input type="text" name="observaciones" class="form-control" placeholder="Notas adicionales (opcional)">
            </div>
             <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar" id="btn-guardar">Guardar e Iniciar Proceso</button>
            <div class="modal-footer">
               
            </div>
        </form>
    </div>
</div>

<div class="toast-container" id="toast-container"></div>

@endsection

@section('scriptsPlugins')
<script>
var CSRF_TOKEN = '{{ csrf_token() }}';

function abrirModal()  { document.getElementById('modal-nuevo').classList.add('visible'); }
function cerrarModal() { document.getElementById('modal-nuevo').classList.remove('visible'); }

document.getElementById('modal-nuevo').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

function guardarCandidato(e) {
    e.preventDefault();
    var btn  = document.getElementById('btn-guardar');
    var form = document.getElementById('form-nuevo');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Guardando...';

    var data = {};
    new FormData(form).forEach(function(v, k) { data[k] = v; });

    fetch('{{ route("rh.contratacion.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify(data)
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success && d.redirect) {
            toast('✓ Candidato creado', 'verde');
            setTimeout(function() { window.location = d.redirect; }, 600);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'Guardar e Iniciar Proceso';
            toast(d.message || 'Error al guardar', 'rojo');
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = 'Guardar e Iniciar Proceso';
        toast('Error de conexión', 'rojo');
    });
}

function toast(msg, tipo) {
    var c = document.getElementById('toast-container');
    var t = document.createElement('div');
    t.className = 'toast ' + (tipo || '');
    t.innerHTML = '<span>' + msg + '</span>';
    c.appendChild(t);
    setTimeout(function() { t.remove(); }, 3000);
}
</script>
@endsection
