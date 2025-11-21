@extends("theme.$theme.layout")

@section('titulo')
Encuestas Fisiatria
@endsection
@section("styles")

<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css"/>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>

<style>

/* ===== BOTONES ESTILO iOS - GLASSMORPHISM ===== */

/* Contenedor de botones horizontales */
.btn-group-ios {
  display: inline-flex;
  gap: 6px;
  padding: 4px;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

/* Contenedor de botones con texto */
.btn-stack-ios {
  display: inline-flex;
  flex-direction: column;
  gap: 4px;
  padding: 4px;
  background: rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

/* Contenedor vertical compacto */
.btn-vertical-ios {
  display: inline-flex;
  flex-direction: column;
  gap: 4px;
  padding: 4px;
  background: rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

/* Base de botón iOS */
.btn-ios {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 8px 12px;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  color: #ffffff;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  box-shadow: 
    0 2px 8px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  overflow: hidden;
  outline: none;
  -webkit-tap-highlight-color: transparent;
}

/* Efecto de brillo iOS */
.btn-ios::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.3),
    transparent
  );
  transition: left 0.5s ease;
}

.btn-ios:hover::before {
  left: 100%;
}

/* Iconos en botones */
.btn-ios i {
  font-size: 14px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Texto en botones */
.btn-ios span {
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.3px;
}

/* Hover effect */
.btn-ios:hover {
  transform: translateY(-2px) scale(1.02);
  box-shadow: 
    0 6px 16px rgba(0, 0, 0, 0.15),
    inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.btn-ios:hover i {
  transform: scale(1.1);
}

/* Active/Click effect */
.btn-ios:active {
  transform: translateY(0) scale(0.98);
  box-shadow: 
    0 2px 6px rgba(0, 0, 0, 0.1),
    inset 0 1px 3px rgba(0, 0, 0, 0.2);
  transition: all 0.1s ease;
}

/* === VARIANTES DE COLOR === */

/* Success (Verde) */
.btn-ios-success {
  background: linear-gradient(135deg, #34c759 0%, #30d158 100%);
  background-size: 200% 200%;
}

.btn-ios-success:hover {
  background-position: 100% 0;
  background: linear-gradient(135deg, #30d158 0%, #32d74b 100%);
}

.btn-ios-success:active {
  background: linear-gradient(135deg, #28a745 0%, #30d158 100%);
}

/* Warning (Naranja/Amarillo) */
.btn-ios-warning {
  background: linear-gradient(135deg, #ff9500 0%, #ffcc00 100%);
  color: #1d1d1f;
  background-size: 200% 200%;
}

.btn-ios-warning:hover {
  background-position: 100% 0;
  background: linear-gradient(135deg, #ffcc00 0%, #ffd60a 100%);
}

.btn-ios-warning:active {
  background: linear-gradient(135deg, #f90 0%, #fc0 100%);
}

/* Info (Azul) */
.btn-ios-info {
  background: linear-gradient(135deg, #007aff 0%, #5ac8fa 100%);
  background-size: 200% 200%;
}

.btn-ios-info:hover {
  background-position: 100% 0;
  background: linear-gradient(135deg, #0a84ff 0%, #64d2ff 100%);
}

.btn-ios-info:active {
  background: linear-gradient(135deg, #0051d5 0%, #007aff 100%);
}

/* Danger (Rojo) */
.btn-ios-danger {
  background: linear-gradient(135deg, #ff3b30 0%, #ff453a 100%);
  background-size: 200% 200%;
}

.btn-ios-danger:hover {
  background-position: 100% 0;
  background: linear-gradient(135deg, #ff453a 0%, #ff6259 100%);
}

.btn-ios-danger:active {
  background: linear-gradient(135deg, #d70015 0%, #ff3b30 100%);
}

/* === EFECTOS ESPECIALES === */

/* Ripple effect al hacer click */
.btn-ios::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  transform: translate(-50%, -50%);
  transition: width 0.6s ease, height 0.6s ease;
}

.btn-ios:active::after {
  width: 300px;
  height: 300px;
  opacity: 0;
  transition: all 0.4s ease;
}

/* === ESTADO DISABLED === */
.btn-ios:disabled {
  opacity: 0.4;
  cursor: not-allowed;
  transform: none;
  pointer-events: none;
}

/* === LOADING STATE === */
.btn-ios.loading {
  pointer-events: none;
  opacity: 0.7;
}

.btn-ios.loading i {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* === TOOLTIPS ESTILO iOS === */
.tooltip {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.tooltip-inner {
  background-color: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 500;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
  letter-spacing: 0.3px;
}

.tooltip.bs-tooltip-top .arrow::before {
  border-top-color: rgba(0, 0, 0, 0.85);
}

.tooltip.bs-tooltip-bottom .arrow::before {
  border-bottom-color: rgba(0, 0, 0, 0.85);
}

/* === EFECTOS EN LA TABLA === */
.table tbody tr {
  transition: all 0.3s ease;
}

.table tbody tr:hover .btn-group-ios,
.table tbody tr:hover .btn-stack-ios,
.table tbody tr:hover .btn-vertical-ios {
  background: rgba(255, 255, 255, 0.8);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  transform: scale(1.02);
}

/* Columna de acciones centrada */
.table td:first-child {
  text-align: center;
  vertical-align: middle;
  padding: 8px;
}

/* === ANIMACIONES DE ENTRADA === */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.btn-group-ios,
.btn-stack-ios,
.btn-vertical-ios {
  animation: fadeInUp 0.4s ease;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  /* Botones más pequeños en móvil */
  .btn-ios {
    padding: 6px 10px;
    font-size: 12px;
    border-radius: 6px;
  }
  
  .btn-ios i {
    font-size: 12px;
  }
  
  .btn-ios span {
    font-size: 11px;
  }
  
  /* Contenedores más compactos */
  .btn-group-ios,
  .btn-stack-ios,
  .btn-vertical-ios {
    gap: 3px;
    padding: 3px;
  }
  
  /* Cambiar a vertical en móvil */
  .btn-group-ios {
    flex-direction: column;
  }
}

/* === DARK MODE (Opcional) === */
@media (prefers-color-scheme: dark) {
  .btn-group-ios,
  .btn-stack-ios,
  .btn-vertical-ios {
    background: rgba(28, 28, 30, 0.7);
    border-color: rgba(255, 255, 255, 0.1);
  }
  
  .table tbody tr:hover .btn-group-ios,
  .table tbody tr:hover .btn-stack-ios,
  .table tbody tr:hover .btn-vertical-ios {
    background: rgba(28, 28, 30, 0.85);
  }
}

/* === FOCUS VISIBLE (Accesibilidad) === */
.btn-ios:focus-visible {
  outline: 2px solid rgba(0, 122, 255, 0.5);
  outline-offset: 2px;
}

/* === VARIANTE: Solo iconos sin padding extra === */
.btn-group-ios .btn-ios:not(:has(span)) {
  padding: 8px;
  width: 32px;
  height: 32px;
}

/* === SPRING ANIMATION === */
@keyframes spring {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

.btn-ios:active {
  animation: spring 0.3s ease;
}


/* Estilos complementarios que no afectan AdminLTE */
.card-primary.card-outline {
  border-top: 3px solid #007bff;
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

.card-header .card-title {
  font-weight: 600;
  color: #495057;
}

.form-group label {
  margin-bottom: 0.5rem;
  color: #495057;
}

.input-group-text {
  background-color: #e9ecef;
  border: 1px solid #ced4da;
}

.btn {
  font-weight: 500;
  padding: 0.5rem 1.25rem;
  border-radius: 0.25rem;
}

.btn i {
  margin-right: 0.5rem;
}

.card-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  padding: 1rem 1.25rem;
}

/* Mejoras visuales sutiles */
.form-control:focus,
.select2bs4:focus {
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.text-muted {
  font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
  .card-footer .col-12 {
    text-align: center !important;
  }
  
  .btn {
    width: 100%;
    margin-bottom: 0.5rem;
  }
  
  .btn:last-child {
    margin-bottom: 0;
  }
}


/* Estilos complementarios para la tabla - Compatible con AdminLTE */

/* Header de la tabla */
.table thead.thead-light th {
  background-color: #f4f6f9;
  color: #495057;
  font-weight: 600;
  font-size: 0.875rem;
  border-bottom: 2px solid #dee2e6;
  padding: 0.75rem;
  vertical-align: middle;
}

/* Iconos en headers */
.table thead th i {
  margin-right: 0.25rem;
  color: #6c757d;
}

/* Filas de la tabla */
.table tbody tr {
  transition: all 0.2s ease;
}

.table tbody tr:hover {
  background-color: #f8f9fa !important;
  cursor: pointer;
}

/* Celdas de la tabla */
.table td {
  padding: 0.75rem;
  vertical-align: middle;
  font-size: 0.875rem;
}

/* Tabla responsive */
.table-responsive {
  border-radius: 0;
}

/* Card personalizado */
.card-primary.card-outline {
  border-top: 3px solid #007bff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Card header */
.card-header {
  background-color: #ffffff;
  border-bottom: 1px solid #dee2e6;
  padding: 1rem 1.25rem;
}

.card-header .card-title {
  font-weight: 600;
  color: #495057;
  font-size: 1.1rem;
  margin: 0;
}

.card-header .card-title i {
  color: #007bff;
  margin-right: 0.5rem;
}

/* Badge en header */
.card-header .badge {
  font-size: 0.75rem;
  font-weight: 500;
  padding: 0.35rem 0.65rem;
}

/* Card footer */
.card-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  padding: 0.75rem 1.25rem;
}

/* Botones de exportación */
.btn-outline-primary,
.btn-outline-danger {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
  margin-left: 0.5rem;
}

/* Footer de tabla */
.table tfoot th {
  background-color: #f4f6f9;
  border-top: 2px solid #dee2e6;
  padding: 0.75rem;
}

/* Columnas específicas */
.table th.text-center,
.table td.text-center {
  text-align: center;
}

/* Estados visuales para campos específicos */
.badge-success {
  background-color: #28a745;
}

.badge-warning {
  background-color: #ffc107;
}

.badge-danger {
  background-color: #dc3545;
}

.badge-info {
  background-color: #17a2b8;
}

/* Botones de acción en la tabla */
.btn-action {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  margin: 0 0.125rem;
}

/* Loading state */
.dataTables_processing {
  background-color: rgba(255, 255, 255, 0.95) !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 0.25rem !important;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
}

/* Paginación personalizada */
.pagination {
  margin-bottom: 0;
}

.page-item.active .page-link {
  background-color: #007bff;
  border-color: #007bff;
}

/* Responsive ajustes */
@media (max-width: 768px) {
  .card-header .card-tools {
    margin-top: 0.5rem;
    width: 100%;
  }
  
  .card-header .badge {
    display: block;
    margin-bottom: 0.5rem;
  }
  
  .card-footer .text-right {
    text-align: center !important;
    margin-top: 0.5rem;
  }
  
  .btn-outline-primary,
  .btn-outline-danger {
    width: 48%;
    margin: 0.25rem 1%;
  }
  
  .table {
    font-size: 0.8rem;
  }
  
  .table th,
  .table td {
    padding: 0.5rem 0.25rem;
  }
}

/* Scroll horizontal suave */
.table-responsive {
  -webkit-overflow-scrolling: touch;
}

/* Animación de carga */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card {
  animation: fadeIn 0.3s ease;
}

/* Mejora visual para celdas vacías */
.table td:empty::after {
  content: '—';
  color: #adb5bd;
}

/* Highlighting para búsqueda */
.table tbody tr.selected {
  background-color: #e3f2fd !important;
}



/* ===== CARD ESTILO iOS ===== */
.card-ios {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 
    0 10px 40px rgba(0, 0, 0, 0.08),
    0 2px 8px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  margin-bottom: 2rem;
  animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.96);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* ===== TABS CONTAINER ===== */
.tabs-ios-container {
  position: relative;
  background: rgba(248, 249, 250, 0.6);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 8px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.nav-tabs-ios {
  display: flex;
  gap: 6px;
  padding: 0;
  margin: 0;
  list-style: none;
  position: relative;
  z-index: 2;
}

/* ===== NAV ITEMS ===== */
.nav-item-ios {
  flex: 1;
  min-width: 0;
}

.nav-link-ios {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  background: transparent;
  border: none;
  border-radius: 12px;
  color: #6c757d;
  font-weight: 500;
  font-size: 14px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

/* Tab Icon */
.tab-icon-ios {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.5);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.tab-icon-ios i {
  font-size: 14px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Tab Text */
.tab-text-ios {
  flex: 1;
  text-align: left;
  letter-spacing: 0.3px;
  transition: all 0.3s ease;
}

/* Badge en tabs */
.badge-ios {
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  color: #ffffff;
  min-width: 24px;
  text-align: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.badge-ios-primary {
  background: linear-gradient(135deg, #007aff 0%, #5ac8fa 100%);
}

.badge-ios-info {
  background: linear-gradient(135deg, #5ac8fa 0%, #64d2ff 100%);
}

.badge-ios-success {
  background: linear-gradient(135deg, #34c759 0%, #30d158 100%);
}

/* Hover effect */
.nav-link-ios:hover {
  color: #495057;
  text-decoration: none;
  background: rgba(255, 255, 255, 0.5);
}

.nav-link-ios:hover .tab-icon-ios {
  background: rgba(255, 255, 255, 0.8);
  transform: scale(1.05);
}

.nav-link-ios:hover .tab-icon-ios i {
  transform: scale(1.1);
}

/* Active state */
.nav-link-ios.active {
  color: #007aff;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 
    0 4px 12px rgba(0, 122, 255, 0.15),
    0 2px 4px rgba(0, 0, 0, 0.05);
}

.nav-link-ios.active .tab-icon-ios {
  background: linear-gradient(135deg, #007aff 0%, #5ac8fa 100%);
  transform: scale(1.1);
}

.nav-link-ios.active .tab-icon-ios i {
  color: #ffffff;
  transform: scale(1.15);
}

.nav-link-ios.active .badge-ios {
  transform: scale(1.1);
}

/* Indicador deslizante (opcional - se activa con JS) */
.tab-indicator-ios {
  position: absolute;
  bottom: 8px;
  left: 8px;
  height: calc(100% - 16px);
  background: rgba(255, 255, 255, 0.9);
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 122, 255, 0.15);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: none;
  z-index: 1;
}

/* ===== CARD BODY ===== */
.card-body-ios {
  padding: 24px;
  background: transparent;
}

.tab-content-ios {
  position: relative;
}

/* ===== TAB PANES ===== */
.tab-pane-ios {
  display: none;
  animation: fadeInContent 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.tab-pane-ios.show.active {
  display: block;
}

@keyframes fadeInContent {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===== TAB HEADERS ===== */
.tab-header-ios {
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 20px 24px;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.tab-title-ios {
  font-size: 20px;
  font-weight: 600;
  color: #1d1d1f;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.tab-title-ios i {
  font-size: 22px;
  color: #007aff;
}

.tab-subtitle-ios {
  font-size: 14px;
  color: #6c757d;
  margin: 0;
  letter-spacing: 0.2px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .nav-tabs-ios {
    flex-direction: column;
    gap: 4px;
  }
  
  .nav-link-ios {
    justify-content: flex-start;
    padding: 10px 16px;
  }
  
  .tab-text-ios {
    font-size: 13px;
  }
  
  .tab-icon-ios {
    width: 24px;
    height: 24px;
  }
  
  .tab-icon-ios i {
    font-size: 12px;
  }
  
  .badge-ios {
    font-size: 10px;
    padding: 2px 6px;
  }
  
  .card-body-ios {
    padding: 16px;
  }
  
  .tab-header-ios {
    padding: 16px 20px;
  }
  
  .tab-title-ios {
    font-size: 18px;
  }
  
  .tab-title-ios i {
    font-size: 20px;
  }
  
  .tab-indicator-ios {
    display: none;
  }
}

/* ===== DARK MODE ===== */
@media (prefers-color-scheme: dark) {
  .card-ios {
    background: rgba(28, 28, 30, 0.8);
    border-color: rgba(255, 255, 255, 0.1);
  }
  
  .tabs-ios-container {
    background: rgba(44, 44, 46, 0.6);
  }
  
  .nav-link-ios {
    color: #98989d;
  }
  
  .nav-link-ios.active {
    color: #0a84ff;
    background: rgba(58, 58, 60, 0.9);
  }
  
  .tab-header-ios {
    background: rgba(44, 44, 46, 0.6);
    border-color: rgba(255, 255, 255, 0.1);
  }
  
  .tab-title-ios {
    color: #f5f5f7;
  }
  
  .tab-title-ios i {
    color: #0a84ff;
  }
}

/* ===== ACCESSIBILITY ===== */
.nav-link-ios:focus-visible {
  outline: 2px solid rgba(0, 122, 255, 0.5);
  outline-offset: 2px;
}
</style>


@endsection


@section('scripts')


<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')

@include('fisiatria.form.formAuxiliarEnfermeria')

@include('fisiatria.tabs.tabsIndexAnalista')

@include('fisiatria.modal.modalindexresumen')

@include('fisiatria.modal.modalindexaddseguimiento')

@include('fisiatria.modal.modalindexaddseguimientoprofesional')



@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/gijgo-combined-1.9.13/js/gijgo.min.js")}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>


<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script>

$(document).ready(function(){
    
    
//Variables del form auxiliar Enfermeria y funcion de consulta

   var fechaini;
   var fechafin;
   var profesional;
   var eps;
   var idas;



fill_datatable_tabla();


// Callback para filtrar los datos de la tabla y detalle
$('#buscar').click(function(){

fechaini = $('#fechaini').val();
fechafin = $('#fechafin').val();
profesional = $('#profesional').val();
eps = $('#eps').val();


    if((fechaini != '' && fechafin != '') || profesional != '' ||  eps != ''){

        $('#fisiatria').DataTable().destroy();

        fill_datatable_tabla(fechaini, fechafin, profesional, eps);

    }else{

        Swal.fire({
        title: 'Debes digitar fecha inicial y fecha final O profesional o eps',
        icon: 'warning',
        buttons:{
            cancel: "Cerrar"

                }
        })
    }

});

$('#reset').click(function(){

$('#fechaini').val('');
$('#fechafin').val('');
$('#profesional').val('');
$('#eps').val('');
$('#fisiatria').DataTable().destroy();
fill_datatable_tabla();

});






function  fill_datatable_tabla(fechaini = '', fechafin = '', profesional = '', eps = ''){

      // Funcion para pintar con data table la pestaña de linea fisiatria
      var datatable =
    $('#fisiatria').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 20, "desc" ]],
    ajax:{
      url:"{{route('analistapsico1_f')}}",
      data:{ fechaini:fechaini, fechafin:fechafin, profesional:profesional, eps:eps,  _token:"{{ csrf_token() }}" },
              method: 'post'
          },
    columns: [
     {data:'action', order: false, searchable: false},
          {data:'id'},
          {data:'surname'},
          {data:'ssurname'},
          {data:'fname'},
          {data:'sname'},
          {data:'type_document'},
          {data:'document'},
          {data:'eapb'},
          {data:'fecha_solicitud'},
          {data:'profesional'},
          {data:'dx'},
          {data:'dispositivo_silla'},
          {data:'dispositivo_apoyo'},
          {data:'other'},
          {data:'solicitud_dispositivo'},
          {data:'antecedentes_dx_cancer'},
          {data:'antecedentes_toxina_espasticidad'},
          {data:'camilla_ambulancia'},
          {data:'tipo_solicitud'},
          {data:'reason_consultation'},
          {data:'observacion'},
          {data:'created_at'}
    ],

     //Botones----------------------------------------------------------------------

     "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

     buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copiar Registros',
               title:"Control de horas",
               className: "btn  btn-outline-primary btn-sm"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Exportar Excel',
               title:"Control de horas",
               className: "btn  btn-outline-success btn-sm"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'Exportar csv',
               className: "btn  btn-outline-warning btn-sm"

                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'Exportar pdf',
               className: "btn  btn-outline-secondary btn-sm"


                  }
               ],
               "columnDefs": [
                {

                            "render": function ( data, type, row ) {
                                if (row["consultation"] == 'SI') {
                                return data +' - Camilla';

                            }else{

                                    return data;

                                }

                                },
                                "targets":[18]
                }


               ],

                "createdRow": function(row, data, dataIndex) {
                if (data["consultation"] == 1) {
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-danger rounded-lg");
                }else{
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-dark rounded-lg");
                    }

                }


    });

}
      // Funcion para pintar con data table la pestaña de citas agendadas
      var datatable =
    $('#fisiatriaAgendada').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 21, "desc" ]],
    ajax:{
      url:"{{route('analistapsicoa1_f')}}",
      data:{  _token:"{{ csrf_token() }}" },
              method: 'post'
          },
    columns: [
          {data:'action', order: false, searchable: false},
          {data:'id'},
          {data:'surname'},
          {data:'ssurname'},
          {data:'fname'},
          {data:'sname'},
          {data:'type_document'},
          {data:'document'},
          {data:'eapb'},
          {data:'fecha_solicitud'},
          {data:'profesional'},
          {data:'dx'},
          {data:'dispositivo_silla'},
          {data:'dispositivo_apoyo'},
          {data:'other'},
          {data:'solicitud_dispositivo'},
          {data:'antecedentes_dx_cancer'},
          {data:'antecedentes_toxina_espasticidad'},
          {data:'camilla_ambulancia'},
          {data:'tipo_solicitud'},
          {data:'reason_consultation'},
          {data:'observacion'},
          {data:'created_at'}        
    
    ],

     //Botones----------------------------------------------------------------------

     "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

     buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copiar Registros',
               title:"Control de horas",
               className: "btn  btn-outline-primary btn-sm"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Exportar Excel',
               title:"Control de horas",
               className: "btn  btn-outline-success btn-sm"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'Exportar csv',
               className: "btn  btn-outline-warning btn-sm"

                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'Exportar pdf',
               className: "btn  btn-outline-secondary btn-sm"


                  }
               ],
               "columnDefs": [
                {

                            "render": function ( data, type, row ) {
                                if (row["consultation"] == 'SI') {
                                return data +' - Camilla';

                            }else{

                                    return data ;

                                }

                                },
                                "targets":[19]
                }


               ],

                "createdRow": function(row, data, dataIndex) {
                if (data["consultation"] == 1) {
                    $($(row).find("td")[19]).addClass("btn btn-sm btn-danger rounded-lg");
                }else{
                    $($(row).find("td")[19]).addClass("btn btn-sm btn-dark rounded-lg");
                    }

                }


    });

 // Funcion para pintar con data table la pestaña de seguimiento
 var datatable =
    $('#fisiatriaSeguimiento').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 20, "desc" ]],
    ajax:{
      url:"{{route('analistapsicos1_f')}}",
      data:{  _token:"{{ csrf_token() }}" },
              method: 'post'
          },
    columns: [
      {data:'action', order: false, searchable: false},
           
          {data:'id'},
          {data:'surname'},
          {data:'ssurname'},
          {data:'fname'},
          {data:'sname'},
          {data:'type_document'},
          {data:'document'},
          {data:'eapb'},
          {data:'fecha_solicitud'},
          {data:'profesional'},
          {data:'dx'},
          {data:'dispositivo_silla'},
          {data:'dispositivo_apoyo'},
          {data:'other'},
          {data:'solicitud_dispositivo'},
          {data:'antecedentes_dx_cancer'},
          {data:'antecedentes_toxina_espasticidad'},
          {data:'camilla_ambulancia'},
          {data:'tipo_solicitud'},
          {data:'reason_consultation'},
          {data:'observacion'},
          {data:'created_at'}   
    ],

     //Botones----------------------------------------------------------------------

     "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

     buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copiar Registros',
               title:"Control de horas",
               className: "btn  btn-outline-primary btn-sm"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Exportar Excel',
               title:"Control de horas",
               className: "btn  btn-outline-success btn-sm"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'Exportar csv',
               className: "btn  btn-outline-warning btn-sm"

                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'Exportar pdf',
               className: "btn  btn-outline-secondary btn-sm"


                  }
               ],
               "columnDefs": [
                {

                            "render": function ( data, type, row ) {
                                if (row["consultation"] == "SI") {
                                return data +' - Camilla';

                            }else{

                                    return data;

                                }

                                },
                                "targets":[18]
                }


               ],

                "createdRow": function(row, data, dataIndex) {
                if (data["consultation"] == 1) {
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-danger rounded-lg");
                }else{
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-dark rounded-lg");
                    }

                }


    });



//Función para abrir modal del detalle de la evolución y muestra las observaciones agregadas
$(document).on('click', '.resumen', function(){
    var idevo = $(this).attr('id');
    
    // Limpiar todos los campos
    $('#names').empty();
    $('#documents').empty();
    $('#evolution').empty();
    $('#observacion').empty();
    $('#names1').empty();
    $('#address').empty();
    $('#date_birth').empty();
    $('#celular').empty();
    $('#sex').empty();
    $('#consultation').empty();
    $('#created_at').empty();
    $('#observaciones_chat').empty();
    
    $.ajax({
        url: "evolucion_f/" + idevo,
        dataType: "json",
        success: function(data){
            
            // Acceder a la nueva estructura de datos
            var evolucion = data[0].evolucion;
            var usuarios = data[1].usuario;
            
            console.log('Evolución:', evolucion);
            console.log('Usuarios:', usuarios);
            
            // Llenar datos del paciente (columna izquierda)
            $('#names').append(evolucion.surname + " " + evolucion.fname);
            $('#documents').append(evolucion.type_document + "-" + evolucion.document);
            $('#date_birth').append(evolucion.fecha_solicitud);
            $('#celular').append(evolucion.eapb || 'N/A');
            $('#sex').append((evolucion.profesional || 'Sin asignar'));
            
            // Llenar datos del tab principal
            $('#names1').append(evolucion.surname + " " + evolucion.fname);
            $('#address').append("DX: " + evolucion.dx + " | Tipo: " + evolucion.tipo_solicitud + " | Eapb: " + (evolucion.eapb || 'N/A'));
            $('#evolution').append(evolucion.reason_consultation || 'N/A');
            $('#observacion').append(evolucion.observacion || 'Sin observaciones');
            $('#created_at').append("Fecha de solicitud: " + evolucion.fecha_solicitud);
            $('#consultation').append("Dispositivo Silla: " + (evolucion.dispositivo_silla || 'N/A') + " | Dispositivo Apoyo: " + (evolucion.dispositivo_apoyo || 'N/A'));
            
            // Procesar observaciones agregadas
            if(evolucion.observacionadd && evolucion.observacionadd.length > 0) {
                $.each(evolucion.observacionadd, function(i, itemobs){
                    // Buscar el usuario que creó la observación
                    var usuarioEncontrado = usuarios.find(u => u.id == itemobs.user_id);
                    
                    if(usuarioEncontrado) {
                        $('#observaciones_chat').append(
                            '<div class="direct-chat-msg">' +
                                '<div class="direct-chat-infos clearfix">' +
                                    '<span class="direct-chat-name float-left">Usuario: ' + usuarioEncontrado.usuario + '</span>' +
                                    '<span class="direct-chat-timestamp float-right">Fecha creación: ' + itemobs.created_at + '</span>' +
                                '</div>' +
                                '<div class="direct-chat-text">Observación: ' +
                                    itemobs.addobservacion +
                                '</div>' +
                            '</div>'
                        );
                    }
                });
            } else {
                $('#observaciones_chat').append('<p class="text-center text-muted">No hay observaciones registradas</p>');
            }
            
            $('.modal-title-resumen').text('Evolución');
            $('#modal-resumen').modal({backdrop: 'static', keyboard: false});
            $('#modal-resumen').modal('show');
        }
    }).fail(function(jqXHR, textStatus, errorThrown){
        if (jqXHR.status === 403) {
            Manteliviano.notificaciones('No tienes permisos para realizar esta acción', 'Sistema Ventas', 'warning');
        } else {
            Manteliviano.notificaciones('Error al cargar los datos', 'Error', 'error');
        }
    });
});


//Función para abrir modal y prevenir el cierre de agregar observaciones

$(document).on('click', '.seguimientoadd', function(){
 var idas = $(this).attr('value');

 $('#namesadd').empty();
 $('#documentsadd').empty();
 $('#evo_id').val(idas);
 $('#user_id').val({{Session()->get('usuario_id') ?? ''}});

$.ajax({
    url:"addseguimiento_f/"+idas+"",
  dataType:"json",
  success:function(data){
    $.each(data.add, function(i, items){
    $('#namesadd').append(items.surname + " " + items.fname);
    $('#documentsadd').append(items.type_document + "-" + items.document);
    $('.modal-title-addseguimiento').text('Add Seguimiento');
    $('#modal-addseguimiento').modal({backdrop: 'static', keyboard: false});
    $('#modal-addseguimiento').modal('show');

  });
  }


}).fail( function( jqXHR, textStatus, errorThrown ) {

if (jqXHR.status === 403) {

Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

}});


});

//Función para abrir modal y prevenir el cierre
   $(document).on('click', '.observacion', function(){
 var idas = $(this).attr('value');

 $('#namesadd').empty();
 $('#documentsadd').empty();
 $('#evo_id').val(idas);
 $('#user_id').val({{Session()->get('usuario_id') ?? ''}});

$.ajax({
    url:"addseguimiento_f/"+idas+"",
  dataType:"json",
  success:function(data){
    $.each(data.add, function(i, items){
    $('#namesadd').append(items.surname + " " + items.fname);
    $('#documentsadd').append(items.type_document + "-" + items.document);
    $('.modal-title-addseguimiento').text('Add Seguimiento');
    $('#modal-addseguimiento').modal({backdrop: 'static', keyboard: false});
    $('#modal-addseguimiento').modal('show');

  });
  }


}).fail( function( jqXHR, textStatus, errorThrown ) {

if (jqXHR.status === 403) {

Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Fidem', 'warning');

}});


});


//Función para abrir modal y prevenir el cierre de agregar observaciones y profesinal

$(document).on('click', '.agenda', function(){
 
idas = $(this).attr('value');

 $('#namesaddp').empty();
 
 $('#addobservacionp').empty();
 $('#documentsaddp').empty();
 $('#evo_idp').val(idas);
 $('#user_idp').val({{Session()->get('usuario_id') ?? ''}});

$.ajax({
    url:"addseguimiento_f/"+idas+"",
  dataType:"json",
  success:function(data){
    $.each(data.add, function(i, items){
    $('#namesaddp').append(items.surname + " " + items.fname);
    $('#documentsaddp').append(items.type_document + "-" + items.document);
    $('.modal-title-addseguimientopro').text('Add Profesional');
    $('#modal-addseguimientopro').modal({backdrop: 'static', keyboard: false});
    $('#modal-addseguimientopro').modal('show');

  });
  }


}).fail( function( jqXHR, textStatus, errorThrown ) {

if (jqXHR.status === 403) {

Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

}});


});

// Función que envia el id al controlador y cambia el estado del registro
    $(document).on('click', '.agendado', function () {
    
   
    
    var data = {
            id: idas,
            profesional: $('#profesionalp').val(),
            addobservacion: $('#addobservacionp').val(),
        _token: $('input[name=_token]').val()
    };
    
    console.log(data);

     ajaxRequest('agendado_f', data);
});



// Función que envia el id al controlador y cambia el estado del registro de desagendado
    $(document).on('click', '.desagenda', function () {
    var data = {
            id: $(this).attr('value'),
           
        _token: $('input[name=_token]').val()
    };

     ajaxRequest('agendado_f', data);
});

function ajaxRequest (url, data) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            $('#modal-addseguimientopro').modal('hide');
            $('#fisiatria').DataTable().ajax.reload();
            $('#fisiatriaAgendada').DataTable().ajax.reload();
            $('#fisiatriaSeguimiento').DataTable().ajax.reload();
            Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
        }
    });
}

// Función que envían los datos de la obervación al controlador
$('#form-generaladd').on('submit', function(event){
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';


        if($('#action').val() == 'Add')
        {
            text = "Estás por crear una observación"
            url = "{{route('guardar_observacion_f')}}";
            method = 'post';
        }

        if ($('#addobservacion').val() == '')
        {
        Swal.fire({
            title: "Debes de rellenar todos los campos del formulario",
            text: "Respuesta Citas fisiatria",
            icon: "warning",
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            });
         }else{

            Swal.fire({
            title: "¿Estás seguro?",
            text: text,
            icon: "success",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            }).then((result)=>{
            if(result.value){
            $.ajax({
                url:url,
                method:method,
                data: $('#form-generaladd').serialize(),
                dataType:"json",
                success:function(data){
                            if(data.success == 'ok') {
                            $('#form-generaladd')[0].reset();
                            $('#modal-addseguimiento').modal('hide');
                            $('#fisiatria').DataTable().ajax.reload();
                            $('#fisiatriaSeguimiento').DataTable().ajax.reload();
                            Swal.fire(
                                {
                                icon: 'success',
                                title: 'Observación agregada correctamente y estado en seguimiento',
                                showConfirmButton: false,
                                timer: 2000
                                }    )}else
                            if(data.success == 'ok1') {
                            $('#form-generaladd')[0].reset();
                            $('#modal-addseguimiento').modal('hide');
                            $('#fisiatriaSeguimiento').DataTable().ajax.reload();
                            Swal.fire(
                                {
                                icon: 'success',
                                title: 'Observación agregada correctamente',
                                showConfirmButton: false,
                                timer: 2000
                                }    )}
                            else if(data.errors != null) {
                            Swal.fire(
                                {
                                icon: 'error',
                                title: data.errors,
                                showConfirmButton: false,
                                timer: 3000
                                })
                            }
                        }

                  });
                            }
                 });

                }

      });





// Cuando DataTable se inicialice o actualice
  $('#fisiatria').on('draw.dt', function() {
    var info = $(this).DataTable().page.info();
    $('#totalRegistros').text(info.recordsDisplay + ' registro' + (info.recordsDisplay !== 1 ? 's' : ''));
  });
  
  // Tooltip para botones (si usas Bootstrap tooltip)
  $('[data-toggle="tooltip"]').tooltip();
  




 // Actualizar contadores de badges
  function actualizarContadores() {
    // Control Fisiatría
    if ($.fn.DataTable.isDataTable('#fisiatria')) {
      $('#count-control').text($('#fisiatria').DataTable().rows().count() || '0');
       $('#count-agendadas').text($('#fisiatriaAgendada').DataTable().rows().count() || '0');
    $('#count-seguimiento').text($('#fisiatriaSeguimiento').DataTable().rows().count() || '0');
    }
    // Agregar lógica para otros contadores según tus tablas
  }
  
  // Animar indicador deslizante
  function moverIndicador(element) {
    const indicator = $('.tab-indicator-ios');
    const parent = element.closest('.nav-item-ios');
    const parentOffset = parent.position();
    const width = parent.outerWidth();
    
    indicator.css({
      'left': parentOffset.left + 8 + 'px',
      'width': width + 'px'
    });
  }
  
  // Evento cuando se cambia de tab
  $('.nav-link-ios').on('click', function(e) {
    e.preventDefault();
    
    // Remover active de todos
    $('.nav-link-ios').removeClass('active');
    $('.tab-pane-ios').removeClass('show active');
    
    // Agregar active al clickeado
    $(this).addClass('active');
    const target = $(this).attr('href');
    $(target).addClass('show active');
    
    // Mover indicador
    moverIndicador($(this));
    
    // Guardar tab activo
    localStorage.setItem('activeTabIOS', target);
    
    // Recargar DataTable si es necesario
    if (target === '#content-control-fisiatria' && $.fn.DataTable.isDataTable('#fisiatria')) {
      $('#fisiatria').DataTable().ajax.reload(null, false);
    }
  });
  
  // Restaurar tab activo
  const activeTab = localStorage.getItem('activeTabIOS');
  if (activeTab) {
    $('.nav-link-ios[href="' + activeTab + '"]').trigger('click');
  } else {
    // Posicionar indicador en el tab activo inicial
    moverIndicador($('.nav-link-ios.active'));
  }
  
  // Actualizar contadores cuando se inicialicen las tablas
  $(document).on('init.dt draw.dt', function() {
    actualizarContadores();
  });
  
  // Ajustar indicador al redimensionar
  $(window).on('resize', function() {
    moverIndicador($('.nav-link-ios.active'));
  });


});


var idioma_espanol =
                 {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
                }
</script>
@endsection
