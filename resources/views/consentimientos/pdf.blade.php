<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consentimiento Informado - {{ $consentimiento->plantilla->nombre }}</title>
    <style>
        body {
            font-family: helvetica, arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 10px;
        }

        /* Header con tabla */
        .header-table {
            width: 100%;
            border: 2px solid #000;
            margin-bottom: 8px;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 5px;
            vertical-align: middle;
        }
        .header-logo {
            width: 70px;
            text-align: center;
            border-right: 1px solid #000;
        }
        .header-title {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
        }
        .header-info {
            width: 22%;
            text-align: right;
            font-size: 7pt;
            border-left: 1px solid #000;
        }

        /* Tabla de información */
        .info-table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .info-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 8pt;
        }
        .info-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 22%;
        }

        /* Secciones */
        .section-box {
            border: 1px solid #000;
            margin-bottom: 8px;
        }
        .section-title {
            background-color: #e0e0e0;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 9pt;
            border-bottom: 1px solid #000;
        }
        .section-content {
            padding: 6px 8px;
            font-size: 8pt;
            text-align: justify;
        }

        /* Tablas de firmas */
        .firma-table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .firma-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 7pt;
            vertical-align: top;
        }
        .firma-img {
            width: 120px;
            height: 45px;
            border: 1px solid #ccc;
            display: block;
            margin: 2px 0;
            object-fit: contain;
        }

        .checkbox { margin: 2px 0; }
        strong { font-weight: bold; }
        .center { text-align: center; }
        .right { text-align: right; }
        p { margin: 0 0 5px 0; }
    </style>
</head>
<body>

<!-- HEADER CON LOGO -->
<table class="header-table">
    <tr>
        <td class="header-logo">
            @if(isset($logoFidemBase64) && $logoFidemBase64)
                <img src="{{ $logoFidemBase64 }}" alt="Logo FIDEM" style="max-width:70px;max-height:60px;object-fit:contain;">
            @else
                <div style="font-size:11pt;font-weight:bold;line-height:1.2;">FIDEM</div>
            @endif
        </td>
        <td>
            <div class="header-title">CLÍNICA FIDEM - CONSENTIMIENTO INFORMADO</div>
            <div style="text-align:center;font-size:8pt;margin-top:2px;">
                {{ $consentimiento->plantilla->nombre }}
            </div>
        </td>
        <td class="header-info">
            <strong>CÓDIGO:</strong> GC-SP-FO-029<br>
            <strong>VERSIÓN:</strong> 001<br>
            <strong>FECHA:</strong> {{ $variables['fecha_actual'] }}
        </td>
    </tr>
</table>

<!-- INFORMACIÓN DEL PACIENTE -->
<table class="info-table">
    <tr>
        <td class="info-label">PACIENTE:</td>
        <td>{{ $variables['paciente_nombre'] }}</td>
        <td class="info-label">ID:</td>
        <td>{{ $variables['paciente_tipo_doc'] }}-{{ $variables['paciente_cedula'] }}</td>
        <td class="info-label">EDAD:</td>
        <td>{{ $variables['paciente_edad'] ?? 'N/A' }}</td>
        <td class="info-label">GÉNERO:</td>
        <td>{{ $variables['paciente_genero'] ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="info-label">FECHA PROC.:</td>
        <td colspan="7">{{ $variables['fecha_procedimiento'] }}</td>
    </tr>
</table>

<!-- VOLUNTAD DE INFORMACIÓN -->
<div class="section-box">
    <div class="section-title">VOLUNTAD DE INFORMACIÓN</div>
    <div class="section-content">
        @if($consentimiento->desea_ser_informado)
            <p style="padding:5px;background-color:#f8f9fa;border-left:3px solid #28a745;margin:0;">
                <strong>✓ SÍ, deseo ser informado directamente</strong>
            </p>
        @else
            <p style="padding:5px;background-color:#f8f9fa;border-left:3px solid #dc3545;margin:0 0 5px 0;">
                <strong>✗ NO, deseo ser informado directamente</strong>
            </p>
            <p style="padding:5px;background-color:#fff3cd;margin:0;font-size:7pt;">
                <strong>MANIFIESTO MI DESEO DE NO SER INFORMADO Y PRESTO MI CONSENTIMIENTO</strong> para que se lleve a cabo el procedimiento descrito en este documento.
            </p>
        @endif
    </div>
</div>

<!-- IDENTIFICACIÓN Y DESCRIPCIÓN DEL PROCEDIMIENTO -->
<div class="section-box">
    <div class="section-title">IDENTIFICACIÓN Y DESCRIPCIÓN DEL PROCEDIMIENTO</div>
    <div class="section-content">
        {!! $contenidoRenderizado !!}
    </div>
</div>

<!-- FIRMAS -->
<div class="section-box">
    <div class="section-title">DECLARACIONES Y FIRMAS</div>
    <div class="section-content">
        <table class="firma-table">
            <!-- PACIENTE -->
            <tr>
                <td colspan="4" style="background-color:#f0f0f0;padding:3px 5px;font-weight:bold;font-size:8pt;">
                    PACIENTE:
                    @if($consentimiento->desea_ser_informado)
                        DECLARO que he comprendido la información, firmo el consentimiento y puedo revocarlo por escrito.
                    @else
                        MANIFIESTO MI DESEO DE NO SER INFORMADO Y PRESTO MI CONSENTIMIENTO.
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:35%;"><strong>Firma:</strong><br>
                    @if(isset($firmaPacienteBase64) && $firmaPacienteBase64)
                        <img src="{{ $firmaPacienteBase64 }}" class="firma-img">
                    @endif
                </td>
                <td style="width:25%;"><strong>Nombre:</strong><br>{{ $consentimiento->paciente->nombres }} {{ $consentimiento->paciente->apellidos }}</td>
                <td style="width:20%;"><strong>ID:</strong><br>{{ $consentimiento->paciente->tipo_documento }}-{{ $consentimiento->paciente->numero_documento }}</td>
                <td style="width:20%;"><strong>Fecha:</strong><br>
                    @if($consentimiento->firmaPaciente)
                        {{ \Carbon\Carbon::parse($consentimiento->firmaPaciente->firmado_at)->format('d/m/Y H:i') }}
                    @else
                        {{ $variables['fecha_actual'] }}
                    @endif
                </td>
            </tr>

            <!-- ACUDIENTE -->
            @if($consentimiento->acudiente && $firmaAcudienteBase64)
            <tr>
                <td colspan="4" style="background-color:#f0f0f0;padding:3px 5px;font-weight:bold;font-size:8pt;border-top:2px solid #000;">
                    FAMILIAR/TUTOR/REPRESENTANTE: DECLARO que he comprendido la información, firmo el consentimiento y puedo revocarlo por escrito.
                </td>
            </tr>
            <tr>
                <td><strong>Firma:</strong><br>
                    @if($firmaAcudienteBase64)
                        <img src="{{ $firmaAcudienteBase64 }}" class="firma-img">
                    @endif
                </td>
                <td><strong>Nombre:</strong><br>{{ $consentimiento->firmaAcudiente->firmante_nombre }}</td>
                <td><strong>ID:</strong><br>{{ $consentimiento->firmaAcudiente->firmante_cedula }}</td>
                <td><strong>Relación:</strong><br>{{ $consentimiento->firmaAcudiente->firmante_relacion }}</td>
            </tr>
            @endif

            <!-- MÉDICO -->
            <tr>
                <td colspan="4" style="background-color:#f0f0f0;padding:3px 5px;font-weight:bold;font-size:8pt;border-top:2px solid #000;">
                    MÉDICO RESPONSABLE: DECLARO haber informado al paciente del objeto, naturaleza, riesgos y complicaciones del procedimiento.
                </td>
            </tr>
            <tr>
                <td><strong>Firma:</strong><br>
                    @if(isset($firmaProfesionalBase64) && $firmaProfesionalBase64)
                        <img src="{{ $firmaProfesionalBase64 }}" class="firma-img">
                    @endif
                </td>
                <td><strong>Médico:</strong><br>{{ $consentimiento->profesional->nombres }} {{ $consentimiento->profesional->apellidos }}</td>
                <td><strong>Reg. Médico:</strong><br>{{ $consentimiento->profesional->registro_medico }}</td>
                <td><strong>Fecha:</strong><br>
                    @if($consentimiento->firmaProfesional)
                        {{ \Carbon\Carbon::parse($consentimiento->firmaProfesional->firmado_at)->format('d/m/Y H:i') }}
                    @else
                        {{ $variables['fecha_actual'] }}
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- PIE DE PÁGINA -->
<div style="margin-top:5px;text-align:center;font-size:6pt;border-top:1px solid #000;padding-top:3px;">
    <p>Documento generado electrónicamente - Clínica Fidem - {{ $variables['fecha_actual'] }}</p>
</div>

</body>
</html>