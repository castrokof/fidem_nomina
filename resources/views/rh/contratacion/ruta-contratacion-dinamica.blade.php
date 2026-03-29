{{-- resources/views/rh/contratacion/ruta-contratacion-dinamica.blade.php --}}
{{-- Fidem Clínica del Dolor | Laravel 5.7 | PHP 7.1+ compatible --}}
{{--
    VARIABLES esperadas desde el controlador:
    $candidato    → modelo ContratacionCandidato (o null)
    $checklist    → colección ContratacionChecklist del candidato
    $faseActual   → int 1-7
    $tipoPersonal → 'asistencial' | 'administrativo'
    $progresoPct  → int 0-100
--}}

@extends("theme.$theme.layout")

@section('titulo', 'Ruta de Contratación — Fidem RRHH')

@section('styles')
<style>
:root {
    --azul:    #1A3A5C; --azul-dk: #0f2540; --dorado: #C8A96E;
    --verde:   #2D7A5F; --rojo:    #C0392B; --naranja: #B85C2A;
    --morado:  #5B3D8A; --bg:      #F0F4F8; --card:    #FFFFFF;
    --texto:   #1C2B3A; --muted:   #6B7F92; --borde:   #DDE4EC;
    --r: 12px; --sombra: 0 2px 16px rgba(26,58,92,0.09);
    --tipo-color: #1A3A5C;
    --tipo-light: #EEF3F9;
}
body.tipo-administrativo { --tipo-color: #5B3D8A; --tipo-light: #F0EBF8; }
* { box-sizing: border-box; }
.ruta-wrap { background: var(--bg); min-height: 100vh; padding: 0 0 60px; }

.ruta-topbar {
    background: linear-gradient(135deg, var(--azul-dk) 0%, var(--tipo-color) 100%);
    padding: 22px 32px; display: flex; align-items: center; gap: 18px;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 4px 20px rgba(15,37,64,0.25); transition: background 0.4s;
}
.topbar-logo { width:44px; height:44px; background:var(--dorado); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
.topbar-info h1 { color:#fff; font-size:17px; font-weight:700; margin:0 0 2px; }
.topbar-info p  { color:var(--dorado); font-size:11px; margin:0; letter-spacing:1px; text-transform:uppercase; }
.topbar-right   { margin-left:auto; display:flex; gap:10px; align-items:center; }
.badge-tipo { padding:6px 14px; border-radius:20px; font-size:11px; font-weight:700; border:1.5px solid rgba(255,255,255,0.3); color:#fff; }
.btn-nuevo { background:var(--dorado); color:var(--azul-dk); border:none; padding:9px 20px; border-radius:20px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:opacity .15s; }
.btn-nuevo:hover { opacity:.85; color:var(--azul-dk); }
.ruta-body { padding: 28px 32px; max-width: 1200px; margin: 0 auto; }

.tipo-banner { border-radius:var(--r); padding:14px 20px; display:flex; align-items:center; gap:14px; margin-bottom:20px; font-size:13px; border-left:5px solid var(--tipo-color); background:var(--tipo-light); }
.tipo-banner strong { color:var(--tipo-color); }
.tipo-banner p { margin:0; color:var(--texto); }

.candidato-card { background:var(--card); border:1px solid var(--borde); border-radius:var(--r); padding:22px 26px; margin-bottom:20px; display:flex; align-items:center; gap:20px; box-shadow:var(--sombra); }
.candidato-avatar { width:56px; height:56px; border-radius:14px; background:var(--tipo-color); color:#fff; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:700; flex-shrink:0; }
.candidato-info h2 { font-size:18px; font-weight:700; color:var(--texto); margin:0 0 3px; }
.candidato-info p  { font-size:13px; color:var(--muted); margin:0; }
.candidato-meta    { margin-left:auto; display:flex; flex-direction:column; align-items:flex-end; gap:6px; }

.badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-proceso   { background:#EEF3F9; color:var(--azul); }
.badge-aprobado  { background:#e8f5f0; color:var(--verde); }
.badge-rechazado { background:#fdecea; color:var(--rojo); }
.badge-vinculado { background:#fdf6ec; color:var(--naranja); }

.progreso-general { background:var(--card); border:1px solid var(--borde); border-radius:var(--r); padding:18px 24px; margin-bottom:24px; box-shadow:var(--sombra); }
.pg-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
.pg-header h3 { font-size:13px; font-weight:600; color:var(--texto); margin:0; }
.pg-porcentaje { font-size:22px; font-weight:800; color:var(--tipo-color); }
.barra-wrap { background:#EEF3F9; border-radius:8px; height:10px; overflow:hidden; }
.barra-fill { height:100%; background:linear-gradient(90deg, var(--tipo-color) 0%, var(--dorado) 100%); border-radius:8px; transition:width 0.5s cubic-bezier(.4,0,.2,1); }
.pg-fases { display:flex; align-items:center; margin-top:14px; }
.pg-fase-dot { display:flex; flex-direction:column; align-items:center; gap:4px; flex:0 0 auto; }
.pg-dot { width:28px; height:28px; border-radius:50%; border:2px solid var(--borde); background:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:var(--muted); transition:all 0.3s; cursor:pointer; }
.pg-dot.completada { background:var(--verde); border-color:var(--verde); color:#fff; }
.pg-dot.activa     { background:var(--tipo-color); border-color:var(--tipo-color); color:#fff; box-shadow:0 0 0 4px rgba(26,58,92,0.15); }
.pg-dot-label { font-size:9px; color:var(--muted); text-align:center; max-width:64px; }
.pg-linea { flex:1; height:2px; background:var(--borde); margin-bottom:18px; }
.pg-linea.completada { background:var(--verde); }

.fases-grid { display:flex; flex-direction:column; gap:16px; }
.fase-card { background:var(--card); border:1px solid var(--borde); border-radius:var(--r); overflow:hidden; box-shadow:var(--sombra); }
.fase-card.activa     { border-left:4px solid var(--tipo-color); }
.fase-card.completada { border-left:4px solid var(--verde); opacity:0.87; }
.fase-card.bloqueada  { border-left:4px solid var(--borde); opacity:0.55; pointer-events:none; }
.fase-header { display:flex; align-items:center; gap:14px; padding:16px 20px; cursor:pointer; user-select:none; transition:background 0.15s; }
.fase-header:hover { background:#fafbfd; }
.fase-num { width:36px; height:36px; border-radius:50%; background:var(--tipo-color); color:#fff; display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:700; flex-shrink:0; }
.fase-card.completada .fase-num { background:var(--verde); }
.fase-card.bloqueada  .fase-num { background:var(--borde); color:var(--muted); }
.fase-titulo-wrap { flex:1; }
.fase-titulo { font-size:14px; font-weight:700; color:var(--texto); margin:0 0 2px; }
.fase-sub    { font-size:11px; color:var(--muted); margin:0; }
.fase-mini { display:flex; align-items:center; gap:10px; margin-left:auto; }
.fase-mini-pct { font-size:12px; font-weight:700; color:var(--tipo-color); }
.fase-mini-barra-wrap { width:80px; background:#EEF3F9; border-radius:6px; height:6px; overflow:hidden; }
.fase-mini-fill { height:100%; background:var(--tipo-color); border-radius:6px; transition:width 0.4s; }
.fase-card.completada .fase-mini-fill { background:var(--verde); }
.chevron { font-size:13px; color:var(--muted); transition:transform 0.25s; margin-left:8px; }
.chevron.abierto { transform:rotate(180deg); }

.fase-items { border-top:1px solid var(--borde); padding:12px 20px 16px; display:none; }
.fase-items.visible { display:block; }

.check-item { display:flex; align-items:flex-start; gap:14px; padding:10px 6px; border-bottom:1px solid #F0F4F8; border-radius:6px; transition:background 0.15s; }
.check-item:last-of-type { border-bottom:none; }
.check-item:hover { background:#FAFBFD; }
.check-box { width:22px; height:22px; border-radius:6px; border:2px solid var(--borde); background:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; margin-top:2px; transition:all 0.2s; font-size:13px; color:transparent; }
.check-box.checked { background:var(--verde); border-color:var(--verde); color:#fff; }
.check-content { flex:1; }
.check-label { font-size:13px; font-weight:600; color:var(--texto); cursor:pointer; display:block; margin-bottom:2px; }
.check-label.tachado { text-decoration:line-through; color:var(--muted); }
.check-desc { font-size:11px; color:var(--muted); margin:2px 0 4px; }
.tag { display:inline-block; padding:2px 7px; border-radius:8px; font-size:10px; font-weight:700; }
.tag-obligatorio { background:#FFF2EC; color:var(--naranja); }
.tag-bloqueante  { background:#FDECEA; color:var(--rojo); }
.tag-opcional    { background:#EEF3F9; color:var(--muted); }
.tag-segun_cargo { background:#F0EBF8; color:var(--morado); }
.check-meta { display:flex; flex-direction:column; align-items:flex-end; gap:3px; flex-shrink:0; }
.check-fecha       { font-size:10px; color:var(--muted); }
.check-responsable { font-size:10px; color:var(--verde); font-weight:600; }

.rethus-alerta { background:linear-gradient(135deg, var(--azul-dk), var(--azul)); border-radius:10px; padding:14px 18px; display:flex; align-items:center; gap:14px; margin-bottom:14px; }
.rethus-alerta .icon { font-size:22px; flex-shrink:0; }
.rethus-alerta p { color:rgba(255,255,255,0.85); font-size:12px; margin:0; }
.rethus-alerta strong { color:var(--dorado); }
.info-alerta { background:#F0EBF8; border-left:4px solid var(--morado); border-radius:10px; padding:12px 16px; margin-bottom:14px; font-size:12px; color:var(--texto); }
.info-alerta strong { color:var(--morado); }

.btn-fase { display:inline-flex; align-items:center; gap:8px; margin-top:14px; padding:10px 22px; background:var(--tipo-color); color:#fff; border:none; border-radius:24px; font-size:13px; font-weight:600; cursor:pointer; transition:opacity .15s, transform .1s; }
.btn-fase:hover { opacity:.85; transform:translateY(-1px); }
.btn-fase:disabled { opacity:.4; cursor:not-allowed; transform:none; }
.btn-fase.verde { background:var(--verde); }

.modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,37,64,0.6); z-index:500; align-items:center; justify-content:center; }
.modal-overlay.visible { display:flex; }
.modal-box { background:#fff; border-radius:16px; padding:32px; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(15,37,64,0.25); animation:modalIn .25s ease; }
@keyframes modalIn { from{opacity:0;transform:scale(0.95)} to{opacity:1;transform:scale(1)} }
.modal-box h2 { font-size:18px; font-weight:700; color:var(--tipo-color); margin:0 0 20px; }
.form-group { margin-bottom:14px; }
.form-group label { display:block; font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
.form-control { width:100%; padding:10px 14px; border:1.5px solid var(--borde); border-radius:8px; font-size:14px; color:var(--texto); outline:none; transition:border-color .15s; font-family:inherit; }
.form-control:focus { border-color:var(--tipo-color); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.modal-footer { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn-cancel  { padding:10px 20px; border:1.5px solid var(--borde); border-radius:8px; background:#fff; color:var(--muted); font-size:13px; cursor:pointer; }
.btn-guardar { padding:10px 24px; border:none; border-radius:8px; background:var(--tipo-color); color:#fff; font-size:13px; font-weight:600; cursor:pointer; }

.toast-container { position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
.toast { background:var(--azul-dk); color:#fff; padding:12px 18px; border-radius:10px; font-size:13px; display:flex; align-items:center; gap:10px; box-shadow:0 8px 24px rgba(15,37,64,0.3); animation:toastIn .3s ease; pointer-events:all; border-left:4px solid var(--dorado); max-width:300px; }
.toast.verde { border-left-color:var(--verde); }
.toast.rojo  { border-left-color:var(--rojo); }
@keyframes toastIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
.spinner { display:inline-block; width:13px; height:13px; border:2px solid rgba(255,255,255,0.4); border-top-color:#fff; border-radius:50%; animation:spin .6s linear infinite; }
@keyframes spin { to{transform:rotate(360deg)} }

@media(max-width:700px) {
    .ruta-body { padding:16px; }
    .ruta-topbar { padding:16px; flex-wrap:wrap; }
    .candidato-card { flex-wrap:wrap; }
    .candidato-meta { margin-left:0; align-items:flex-start; }
    .form-row { grid-template-columns:1fr; }
    .pg-dot-label { display:none; }
}
</style>
@endsection

@section('contenido')
@php
    $tipo    = isset($tipoPersonal) ? $tipoPersonal : 'asistencial';
    $fa      = isset($faseActual)   ? (int)$faseActual : 1;
    $esAdmin = ($tipo === 'administrativo');

    // ── FASE 1 ──────────────────────────────────────────
    $fase1_comun = [
        ['key'=>'hv_recibida',       'label'=>'Hoja de vida recibida',              'desc'=>'Formato libre o Formato Único Función Pública con soportes escaneados',          'tag'=>'obligatorio'],
        ['key'=>'titulo_revisado',   'label'=>'Título / Certificado de formación',   'desc'=>'Diploma o certificado según el nivel del cargo (técnico, tecnólogo, profesional)','tag'=>'obligatorio'],
        ['key'=>'entrevista_citada', 'label'=>'Candidato citado a entrevista',       'desc'=>'Confirmación de disponibilidad y agenda con el responsable del área',            'tag'=>'obligatorio'],
    ];
    $fase1_asist = [['key'=>'rethus_validado',  'label'=>'RETHUS verificado',                   'desc'=>'Consulta en rethus.minsalud.gov.co — soporte impreso adjunto',    'tag'=>'bloqueante']];
    $fase1_admin = [['key'=>'rethus_validado',  'label'=>'RETHUS (si cargo aplica)',            'desc'=>'Solo si el cargo tiene funciones de apoyo clínico habilitadas',    'tag'=>'segun_cargo']];
    $items1 = $esAdmin ? array_merge($fase1_comun, $fase1_admin) : array_merge($fase1_comun, $fase1_asist);

    // ── FASE 2 ──────────────────────────────────────────
    $fase2_comun = [
        ['key'=>'entrevista_rh',     'label'=>'Entrevista Coordinador RRHH',        'desc'=>'Presentación de la clínica, condiciones del cargo, valores institucionales',    'tag'=>'obligatorio'],
        ['key'=>'entrevista_jefe',   'label'=>'Entrevista Jefe de Área',             'desc'=>'Evaluación de competencias técnicas y perfil del cargo solicitado',             'tag'=>'obligatorio'],
        ['key'=>'candidato_aprobado','label'=>'Candidato aprobado y notificado',     'desc'=>'Decisión final comunicada para iniciar proceso documental',                     'tag'=>'obligatorio'],
    ];
    $fase2_admin = [['key'=>'prueba_tecnica', 'label'=>'Prueba técnica aplicada', 'desc'=>'Excel, digitación, ortografía, atención al usuario — según el cargo', 'tag'=>'segun_cargo']];
    $items2 = $esAdmin ? array_merge($fase2_comun, $fase2_admin) : $fase2_comun;

    // ── FASE 3 ──────────────────────────────────────────
    $fase3_comun = [
        ['key'=>'cedula',             'label'=>'Cédula de ciudadanía',               'desc'=>'Copia ampliada al 150%',                                                        'tag'=>'obligatorio'],
        ['key'=>'diploma',            'label'=>'Diploma / Certificado de formación',  'desc'=>'Según nivel del cargo',                                                         'tag'=>'obligatorio'],
        ['key'=>'acta_grado',         'label'=>'Acta de grado (si es profesional)',   'desc'=>'Original o copia autenticada',                                                  'tag'=>'obligatorio'],
        ['key'=>'exp_laboral',        'label'=>'Soportes de experiencia laboral',     'desc'=>'Certificaciones de empleos anteriores',                                         'tag'=>'obligatorio'],
        ['key'=>'ant_judicial',       'label'=>'Antecedentes judiciales',             'desc'=>'Policía Nacional — vigencia 3 meses',                                           'tag'=>'obligatorio'],
        ['key'=>'ant_disciplinario',  'label'=>'Antecedentes disciplinarios',         'desc'=>'Procuraduría General de la Nación — vigencia 3 meses',                          'tag'=>'obligatorio'],
        ['key'=>'ant_fiscal',         'label'=>'Antecedentes fiscales',               'desc'=>'Contraloría General de la República',                                           'tag'=>'obligatorio'],
        ['key'=>'medidas_correctivas','label'=>'RNMC — Medidas correctivas',          'desc'=>'Registro Nacional de Medidas Correctivas',                                      'tag'=>'obligatorio'],
    ];
    $fase3_asist = [
        ['key'=>'tarjeta_prof',  'label'=>'Tarjeta profesional vigente',  'desc'=>'Según profesión — obligatoria en salud',                              'tag'=>'bloqueante'],
        ['key'=>'rethus_soporte','label'=>'Certificado RETHUS impreso',   'desc'=>'Soporte o captura de la consulta realizada en Fase 1',                'tag'=>'bloqueante'],
        ['key'=>'bls_acls',      'label'=>'Certificación BLS / ACLS',     'desc'=>'Obligatorio para personal asistencial',                               'tag'=>'obligatorio'],
    ];
    $fase3_admin = [
        ['key'=>'rut',        'label'=>'RUT (si prestador de servicios)',     'desc'=>'Para contratos por honorarios',                                   'tag'=>'segun_cargo'],
        ['key'=>'acuerdo_conf','label'=>'Acuerdo de confidencialidad firmado','desc'=>'Acceso a datos sensibles de pacientes — Ley 1581/2012',            'tag'=>'bloqueante'],
    ];
    $items3 = $esAdmin ? array_merge($fase3_comun, $fase3_admin) : array_merge($fase3_comun, $fase3_asist);

    // ── FASE 4 ──────────────────────────────────────────
    $fase4_comun = [
        ['key'=>'vax_influenza','label'=>'Influenza — dosis anual',     'desc'=>'Debe estar vigente al momento del ingreso',                               'tag'=>'obligatorio'],
        ['key'=>'vax_covid',    'label'=>'COVID-19 — esquema completo', 'desc'=>'Certificado digital o carnet físico con refuerzo',                        'tag'=>'obligatorio'],
        ['key'=>'vax_tetanos',  'label'=>'Tétanos / dT o Tdap',        'desc'=>'Refuerzo cada 10 años — verificar fecha del último',                      'tag'=>'obligatorio'],
    ];
    $fase4_asist = [
        ['key'=>'vax_hep_b',    'label'=>'Hepatitis B — 3 dosis + refuerzo','desc'=>'Solicitar serología anti-HBs si hay duda sobre el esquema',          'tag'=>'obligatorio'],
        ['key'=>'vax_varicela', 'label'=>'Varicela — 2 dosis o inmunidad',   'desc'=>'Si no tiene antecedente documentado de la enfermedad',               'tag'=>'obligatorio'],
        ['key'=>'vax_mmr',      'label'=>'Triple Viral MMR — 2 dosis',       'desc'=>'Sarampión, paperas, rubéola',                                         'tag'=>'obligatorio'],
        ['key'=>'vax_fiebre_am','label'=>'Fiebre Amarilla',                   'desc'=>'Según riesgo epidemiológico regional',                               'tag'=>'opcional'],
    ];
    $fase4_admin = [
        ['key'=>'vax_mmr',  'label'=>'Triple Viral MMR','desc'=>'Sarampión, paperas, rubéola',                                                             'tag'=>'opcional'],
        ['key'=>'vax_hep_b','label'=>'Hepatitis B',      'desc'=>'Si tiene contacto con documentos clínicos o áreas compartidas con pacientes',            'tag'=>'segun_cargo'],
    ];
    $items4 = $esAdmin ? array_merge($fase4_comun, $fase4_admin) : array_merge($fase4_comun, $fase4_asist);

    // ── FASE 5 ──────────────────────────────────────────
    $fase5_comun = [
        ['key'=>'exam_preocupacional','label'=>'Examen preocupacional realizado','desc'=>'Valoración médica general según el perfil y riesgos del cargo',   'tag'=>'obligatorio'],
        ['key'=>'exam_aptitud',       'label'=>'Concepto de aptitud laboral',    'desc'=>'Médico laboral emite: Apto / Apto con restricciones / No apto',    'tag'=>'bloqueante'],
    ];
    $fase5_asist = [['key'=>'exam_parclinicos','label'=>'Paraclínicos de ingreso',   'desc'=>'Hemograma, glicemia, perfil lipídico, HBsAg, anti-HCV',        'tag'=>'obligatorio']];
    $fase5_admin = [['key'=>'exam_optometria', 'label'=>'Optometría (si aplica)',     'desc'=>'Cargos con uso intensivo de monitor o trabajo de precisión visual','tag'=>'segun_cargo']];
    $items5 = $esAdmin ? array_merge($fase5_comun, $fase5_admin) : array_merge($fase5_comun, $fase5_asist);

    // ── FASE 6 (igual para ambos) ────────────────────────
    $items6 = [
        ['key'=>'afil_eps',   'label'=>'EPS — Afiliación o traslado',       'desc'=>'Verificar periodo mínimo de permanencia en la EPS anterior',             'tag'=>'obligatorio'],
        ['key'=>'afil_afp',   'label'=>'AFP — Fondo de Pensiones',          'desc'=>'Colpensiones o fondo privado. Formato firmado',                           'tag'=>'obligatorio'],
        ['key'=>'afil_arl',   'label'=>'ARL — Antes del primer día',        'desc'=>'Afiliación a la ARL de Fidem ANTES del inicio laboral. Sin excepción',    'tag'=>'bloqueante'],
        ['key'=>'afil_ccf',   'label'=>'Caja de Compensación Familiar',     'desc'=>'Acceso a subsidios, recreación y servicios',                              'tag'=>'obligatorio'],
        ['key'=>'cuenta_banco','label'=>'Certificación bancaria',           'desc'=>'Para pago de nómina. Original con fecha reciente',                        'tag'=>'obligatorio'],
    ];

    // ── FASE 7 ──────────────────────────────────────────
    $fase7_comun = [
        ['key'=>'contrato_firmado','label'=>'Contrato laboral firmado',      'desc'=>'Tipo: fijo, indefinido o prestación. Cláusulas de confidencialidad',     'tag'=>'bloqueante'],
        ['key'=>'induccion',       'label'=>'Inducción institucional',       'desc'=>'Misión, visión, valores, humanización, SGSST, protocolos. Acta firmada',  'tag'=>'obligatorio'],
        ['key'=>'accesos_sistemas','label'=>'Accesos a sistemas creados',   'desc'=>'Correo, RFAST (según perfil) y demás sistemas del cargo',                  'tag'=>'obligatorio'],
        ['key'=>'dotacion',        'label'=>'Dotación y carnet entregados',  'desc'=>'Carnet institucional, EPP y elementos de trabajo según cargo',             'tag'=>'obligatorio'],
    ];
    $fase7_admin = [['key'=>'induccion_cargo','label'=>'Inducción al cargo por jefe inmediato','desc'=>'Procesos, herramientas y flujos internos del área','tag'=>'obligatorio']];
    $items7 = $esAdmin ? array_merge($fase7_comun, $fase7_admin) : $fase7_comun;

    $todosLosItems = [1=>$items1, 2=>$items2, 3=>$items3, 4=>$items4, 5=>$items5, 6=>$items6, 7=>$items7];

    // Helpers compatibles PHP 7.x (sin match/fn)
    $getTagLabel = function(string $t) {
        if ($t === 'bloqueante')  return '⛔ BLOQUEANTE';
        if ($t === 'obligatorio') return '⚠ Obligatorio';
        if ($t === 'segun_cargo') return '🔹 Según cargo';
        return 'Opcional';
    };
    $getFaseStatus = function(int $num) use ($fa) {
        if ($fa > $num)  return 'completada';
        if ($fa === $num) return 'activa';
        return 'bloqueada';
    };
    $getCompletado = function(string $key) use ($checklist) {
        if (!isset($checklist)) return false;
        $ci = $checklist->where('item_key', $key)->first();
        return $ci && $ci->completado ? true : false;
    };
    $getCompletadoPor = function(string $key) use ($checklist) {
        if (!isset($checklist)) return null;
        $ci = $checklist->where('item_key', $key)->first();
        return ($ci && $ci->completado) ? ($ci->completado_por ?? 'RRHH') : null;
    };
    $getCompletadoAt = function(string $key) use ($checklist) {
        if (!isset($checklist)) return null;
        $ci = $checklist->where('item_key', $key)->first();
        return ($ci && $ci->completado && $ci->completado_at) ? $ci->completado_at->format('d/m/Y H:i') : null;
    };

    $faseDefs = [
        1 => ['titulo'=>'Recepción de Hoja de Vida y Preselección',     'sub'=>'Evaluación inicial antes de citar a entrevista'],
        2 => ['titulo'=>'Entrevista y Evaluación',                       'sub'=>'Valoración de competencias y perfil del cargo'],
        3 => ['titulo'=>'Recolección y Verificación Documental',         'sub'=>'Todos los documentos antes de firmar contrato'],
        4 => ['titulo'=>'Verificación Esquema de Vacunación',            'sub'=>'Obligatorio para todo el personal de la institución'],
        5 => ['titulo'=>'Examen Médico de Ingreso',                      'sub'=>'Res. 2346/2007 — Ministerio de Trabajo'],
        6 => ['titulo'=>'Afiliaciones al Sistema de Seguridad Social',   'sub'=>'Previo o simultáneo a la firma del contrato'],
        7 => ['titulo'=>'Firma de Contrato y Onboarding',                'sub'=>'Vinculación formal e inducción institucional'],
    ];

    // Recopilar keys opcionales para JS (compatible PHP 7.x)
    $keysOpcionales = [];
    foreach ($todosLosItems as $fItems) {
        foreach ($fItems as $fItem) {
            if (in_array($fItem['tag'], ['opcional', 'segun_cargo'])) {
                $keysOpcionales[] = $fItem['key'];
            }
        }
    }
    $keysOpcionales = array_unique($keysOpcionales);
@endphp

<div class="ruta-wrap" id="ruta-wrap">

    <div class="ruta-topbar">
        <div class="topbar-logo">🏥</div>
        <div class="topbar-info">
            <h1>Fidem Clínica del Dolor</h1>
            <p>Coordinación RRHH — Ruta de Contratación</p>
        </div>
        <div class="topbar-right">
            <span class="badge-tipo">{{ $esAdmin ? '🗂 Administrativo' : '🩺 Asistencial' }}</span>
            <button class="btn-nuevo" onclick="abrirModal()">＋ Nuevo candidato</button>
        </div>
    </div>

    <div class="ruta-body">

        {{-- BANNER TIPO --}}
        <div class="tipo-banner">
            <span style="font-size:22px">{{ $esAdmin ? '🗂' : '🩺' }}</span>
            <p>
                <strong>Proceso {{ $esAdmin ? 'Administrativo' : 'Asistencial' }}</strong> —
                @if($esAdmin)
                    RETHUS solo si el cargo tiene funciones clínicas habilitadas. Vacunación reducida. Acuerdo de confidencialidad obligatorio (Ley 1581/2012).
                @else
                    RETHUS obligatorio y bloqueante. Vacunación completa, BLS/ACLS y tarjeta profesional vigente requeridos.
                @endif
            </p>
        </div>

        {{-- CANDIDATO --}}
        @if(isset($candidato) && $candidato)
        <div class="candidato-card">
            <div class="candidato-avatar">{{ strtoupper(substr($candidato->nombre_completo, 0, 1)) }}</div>
            <div class="candidato-info">
                <h2>{{ $candidato->nombre_completo }}</h2>
                <p>{{ $candidato->cargo }}{{ $candidato->area ? ' · '.$candidato->area : '' }} · CC {{ $candidato->cedula }}</p>
            </div>
            <div class="candidato-meta">
                @php
                    $estadoKey = ($candidato->estado === 'en_proceso') ? 'proceso' : $candidato->estado;
                @endphp
                <span class="badge badge-{{ $estadoKey }}">{{ strtoupper(str_replace('_',' ',$candidato->estado)) }}</span>
                <span style="font-size:11px;color:var(--muted)">
                    Inicio: {{ $candidato->fecha_inicio_proceso ? $candidato->fecha_inicio_proceso->format('d/m/Y') : 'N/A' }}
                </span>
            </div>
        </div>
        @endif

        {{-- PROGRESO GENERAL --}}
        <div class="progreso-general">
            <div class="pg-header">
                <h3>Progreso General del Proceso</h3>
                <span class="pg-porcentaje" id="pct-general">{{ isset($progresoPct) ? $progresoPct : 0 }}%</span>
            </div>
            <div class="barra-wrap">
                <div class="barra-fill" id="barra-general" style="width:{{ isset($progresoPct) ? $progresoPct : 0 }}%"></div>
            </div>
            <div class="pg-fases">
                @php $faseNombres = ['Preselección','Entrevista','Documentos','Vacunación','Examen Méd.','Afiliaciones','Onboarding']; @endphp
                @foreach($faseNombres as $i => $nombre)
                    @php $num = $i + 1; @endphp
                    @if($i > 0)
                        <div class="pg-linea {{ $num <= $fa ? 'completada' : '' }}"></div>
                    @endif
                    <div class="pg-fase-dot" onclick="irFase({{ $num }})">
                        <div class="pg-dot {{ $num < $fa ? 'completada' : ($num === $fa ? 'activa' : '') }}" id="dot-{{ $num }}">
                            {{ $num < $fa ? '✓' : $num }}
                        </div>
                        <span class="pg-dot-label">{{ $nombre }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FASES --}}
        <div class="fases-grid">
            @foreach($todosLosItems as $numFase => $itemsFase)
            @php $st = $getFaseStatus($numFase); @endphp
            <div class="fase-card {{ $st }}" id="fase-{{ $numFase }}" data-fase="{{ $numFase }}">
                <div class="fase-header" onclick="toggleFase({{ $numFase }})">
                    <div class="fase-num">{{ $fa > $numFase ? '✓' : $numFase }}</div>
                    <div class="fase-titulo-wrap">
                        <p class="fase-titulo">{{ $faseDefs[$numFase]['titulo'] }}</p>
                        <p class="fase-sub">{{ $faseDefs[$numFase]['sub'] }}</p>
                    </div>
                    <div class="fase-mini">
                        <span class="fase-mini-pct" id="pct-fase-{{ $numFase }}">0%</span>
                        <div class="fase-mini-barra-wrap">
                            <div class="fase-mini-fill" id="barra-fase-{{ $numFase }}" style="width:0%"></div>
                        </div>
                    </div>
                    <span class="chevron {{ $fa === $numFase ? 'abierto' : '' }}" id="chevron-{{ $numFase }}">▼</span>
                </div>

                <div class="fase-items {{ $fa === $numFase ? 'visible' : '' }}" id="items-fase-{{ $numFase }}">

                    @if($numFase === 1 && !$esAdmin)
                        <div class="rethus-alerta">
                            <span class="icon">🪪</span>
                            <p><strong>RETHUS — Paso bloqueante:</strong> Validar en rethus.minsalud.gov.co antes de avanzar.</p>
                        </div>
                    @endif
                    @if($numFase === 1 && $esAdmin)
                        <div class="info-alerta">
                            <strong>ℹ Personal administrativo:</strong> RETHUS solo aplica si el cargo tiene funciones clínicas habilitadas.
                        </div>
                    @endif
                    @if($numFase === 4 && $esAdmin)
                        <div class="info-alerta">
                            <strong>💉 Vacunación administrativa:</strong> Influenza, COVID-19 y Tétanos son obligatorias. Las demás son recomendadas.
                        </div>
                    @endif

                    @foreach($itemsFase as $item)
                    @php
                        $estaCompletado = $getCompletado($item['key']);
                        $quienCompleto  = $getCompletadoPor($item['key']);
                        $cuandoCompleto = $getCompletadoAt($item['key']);
                    @endphp
                    <div class="check-item" id="item-{{ $item['key'] }}">
                        <div class="check-box {{ $estaCompletado ? 'checked' : '' }}"
                             id="box-{{ $item['key'] }}"
                             onclick="toggleItem('{{ $item['key'] }}', {{ $numFase }})">
                            {{ $estaCompletado ? '✓' : '' }}
                        </div>
                        <div class="check-content">
                            <span class="check-label {{ $estaCompletado ? 'tachado' : '' }}"
                                  id="label-{{ $item['key'] }}"
                                  onclick="toggleItem('{{ $item['key'] }}', {{ $numFase }})">
                                {{ $item['label'] }}
                            </span>
                            <p class="check-desc">{{ $item['desc'] }}</p>
                            <span class="tag tag-{{ $item['tag'] }}">{{ $getTagLabel($item['tag']) }}</span>
                        </div>
                        <div class="check-meta">
                            @if($quienCompleto)<span class="check-responsable">{{ $quienCompleto }}</span>@endif
                            @if($cuandoCompleto)<span class="check-fecha">{{ $cuandoCompleto }}</span>@endif
                        </div>
                    </div>
                    @endforeach

                    @if($numFase < 7)
                        <button class="btn-fase" onclick="avanzarFase({{ $numFase }})" id="btn-fase-{{ $numFase }}">
                            Avanzar a Fase {{ $numFase + 1 }} — {{ $faseDefs[$numFase + 1]['titulo'] }} →
                        </button>
                    @else
                        <button class="btn-fase verde" onclick="completarProceso()" id="btn-fase-7">
                            ✓ Proceso completo — Vincular candidato
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

{{-- MODAL NUEVO CANDIDATO --}}
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
                        <option value="asistencial"    {{ !$esAdmin ? 'selected' : '' }}>Asistencial</option>
                        <option value="administrativo" {{ $esAdmin  ? 'selected' : '' }}>Administrativo</option>
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
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar" id="btn-guardar">Guardar e Iniciar Proceso</button>
            </div>
        </form>
    </div>
</div>

<div class="toast-container" id="toast-container"></div>
@endsection

@section('scriptsPlugins')
<script>
var CANDIDATO_ID  = {{ isset($candidato) && $candidato ? $candidato->id : 'null' }};
var FASE_ACTUAL   = {{ $fa }};
var TIPO_PERSONAL = '{{ $tipo }}';
var CSRF_TOKEN    = '{{ csrf_token() }}';

if (TIPO_PERSONAL === 'administrativo') {
    document.body.classList.add('tipo-administrativo');
}

// Estado local del checklist
var estadoChecklist = {};
@if(isset($checklist) && $checklist)
    @foreach($checklist as $ci)
    estadoChecklist['{{ $ci->item_key }}'] = {{ $ci->completado ? 'true' : 'false' }};
    @endforeach
@endif

// Ítems por fase
var itemsPorFase = {
    @foreach($todosLosItems as $nf => $ifs)
    {{ $nf }}: {!! json_encode(array_column($ifs, 'key')) !!},
    @endforeach
};

// Keys opcionales (no bloquean avance)
var itemsOpcionales = {!! json_encode(array_values($keysOpcionales)) !!};

// ── ACORDEÓN ──────────────────────────────────────────────
function toggleFase(num) {
    var items   = document.getElementById('items-fase-' + num);
    var chevron = document.getElementById('chevron-' + num);
    if (!items) return;
    var abierto = items.classList.contains('visible');
    items.classList.toggle('visible', !abierto);
    chevron.classList.toggle('abierto', !abierto);
}
function irFase(num) {
    var el = document.getElementById('fase-' + num);
    if (!el) return;
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    var items = document.getElementById('items-fase-' + num);
    if (items && !items.classList.contains('visible')) toggleFase(num);
}

// ── TOGGLE ÍTEM ───────────────────────────────────────────
function toggleItem(key, fase) {
    if (!CANDIDATO_ID) { toast('Primero crea el candidato', 'rojo'); return; }
    var box    = document.getElementById('box-' + key);
    var label  = document.getElementById('label-' + key);
    var actual = estadoChecklist[key] || false;
    var nuevo  = !actual;

    box.classList.toggle('checked', nuevo);
    box.innerHTML = nuevo ? '✓' : '';
    if (label) label.classList.toggle('tachado', nuevo);
    estadoChecklist[key] = nuevo;
    recalcularProgreso();

    fetch('{{ route("rh.contratacion.toggle") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ candidato_id: CANDIDATO_ID, item_key: key, fase: fase, completado: nuevo })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            toast((nuevo ? '✓ ' : '— ') + d.item_nombre, nuevo ? 'verde' : '');
        } else {
            estadoChecklist[key] = actual;
            box.classList.toggle('checked', actual);
            box.innerHTML = actual ? '✓' : '';
            if (label) label.classList.toggle('tachado', actual);
            recalcularProgreso();
            toast('Error al guardar', 'rojo');
        }
    })
    .catch(function() { toast('Error de conexión', 'rojo'); });
}

// ── PROGRESO ──────────────────────────────────────────────
function recalcularProgreso() {
    var totalG = 0, compG = 0;
    for (var f = 1; f <= 7; f++) {
        var keys = itemsPorFase[f] || [];
        var comp = 0;
        keys.forEach(function(k) { if (estadoChecklist[k]) comp++; });
        var pct = keys.length ? Math.round(comp / keys.length * 100) : 0;
        var pe = document.getElementById('pct-fase-' + f);
        var be = document.getElementById('barra-fase-' + f);
        if (pe) pe.textContent = pct + '%';
        if (be) be.style.width = pct + '%';
        totalG += keys.length;
        compG  += comp;
    }
    var gen = totalG ? Math.round(compG / totalG * 100) : 0;
    document.getElementById('pct-general').textContent   = gen + '%';
    document.getElementById('barra-general').style.width = gen + '%';
}

// ── AVANZAR FASE ──────────────────────────────────────────
function avanzarFase(faseActual) {
    if (!CANDIDATO_ID) { toast('Primero crea el candidato', 'rojo'); return; }
    var keys = itemsPorFase[faseActual] || [];
    var incompletos = keys.filter(function(k) {
        return !estadoChecklist[k] && itemsOpcionales.indexOf(k) === -1;
    });
    if (incompletos.length) {
        toast('Faltan ' + incompletos.length + ' ítem(s) obligatorio(s)', 'rojo');
        return;
    }
    var btn = document.getElementById('btn-fase-' + faseActual);
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Guardando...';

    fetch('{{ url("/rh/contratacion") }}/' + CANDIDATO_ID + '/fase', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ fase: faseActual + 1 })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            toast('✓ Avanzando a Fase ' + (faseActual + 1), 'verde');
            setTimeout(function() { window.location.reload(); }, 700);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'Avanzar a Fase ' + (faseActual + 1) + ' →';
            toast(d.message || 'Error', 'rojo');
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = 'Avanzar →';
        toast('Error de conexión', 'rojo');
    });
}

function completarProceso() {
    var keys = itemsPorFase[7] || [];
    var inc  = keys.filter(function(k) { return !estadoChecklist[k] && itemsOpcionales.indexOf(k) === -1; });
    if (inc.length) { toast('Faltan ' + inc.length + ' ítem(s) en Fase 7', 'rojo'); return; }
    if (!confirm('¿Confirma que el proceso está completo y desea vincular al candidato?')) return;

    fetch('{{ url("/rh/contratacion") }}/' + CANDIDATO_ID + '/fase', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ fase: 8, estado: 'vinculado' })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            toast('🎉 ¡Candidato vinculado!', 'verde');
            setTimeout(function() { location.reload(); }, 900);
        }
    });
}

// ── MODAL ─────────────────────────────────────────────────
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

// ── TOAST ─────────────────────────────────────────────────
function toast(msg, tipo) {
    var c = document.getElementById('toast-container');
    var t = document.createElement('div');
    t.className = 'toast ' + (tipo || '');
    t.innerHTML = '<span>' + msg + '</span>';
    c.appendChild(t);
    setTimeout(function() { t.remove(); }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    recalcularProgreso();
});
</script>
@endsection
