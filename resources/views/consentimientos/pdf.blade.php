<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consentimiento Informado - {{ $consentimiento->plantilla->nombre }}</title>
    <style>
        body {
            font-family: helvetica, arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 15px;
        }
        
        /* Header con tabla */
        .header-table {
            width: 100%;
            border: 2px solid #000;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 8px;
            vertical-align: middle;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            border-right: 1px solid #000;
        }
        .header-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
        }
        .header-info {
            width: 25%;
            text-align: right;
            font-size: 8pt;
            border-left: 1px solid #000;
        }
        
        /* Tabla de información */
        .info-table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 9pt;
        }
        .info-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 25%;
        }
        
        /* Secciones */
        .section-box {
            border: 1px solid #000;
            margin-bottom: 15px;
        }
        .section-title {
            background-color: #e0e0e0;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 10pt;
            border-bottom: 1px solid #000;
        }
        .section-content {
            padding: 10px;
            font-size: 9pt;
            text-align: justify;
        }
        
        /* Tablas de firmas */
        .firma-table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .firma-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 9pt;
            vertical-align: top;
        }
        .firma-img {
            max-width: 180px;
            max-height: 70px;
            border: 1px solid #ccc;
            display: block;
            margin: 5px 0;
        }
        
        .checkbox { margin: 3px 0; }
        strong { font-weight: bold; }
        .center { text-align: center; }
        .right { text-align: right; }
    </style>
</head>
<body>

<!-- HEADER CON LOGO -->
<table class="header-table">
    <tr>
        <td class="header-logo">
            <div style="font-size:24pt;">🏥</div>
        </td>
        <td>
            <div class="header-title">CLÍNICA FIDEM</div>
            <div style="text-align:center;font-size:9pt;">Cali, Colombia</div>
            <div style="text-align:center;font-size:10pt;font-weight:bold;margin-top:5px;">
                CONSENTIMIENTO INFORMADO
            </div>
            <div style="text-align:center;font-size:9pt;margin-top:3px;">
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
        <td class="info-label">SERVICIO:</td>
        <td>PROCEDIMIENTOS</td>
        <td class="info-label">Nº IDENTIFICACIÓN:</td>
        <td>{{ $variables['paciente_tipo_doc'] }}-{{ $variables['paciente_cedula'] }}</td>
    </tr>
    <tr>
        <td class="info-label">NOMBRE PACIENTE:</td>
        <td colspan="3">{{ $variables['paciente_nombre'] }}</td>
    </tr>
    <tr>
        <td class="info-label">EDAD:</td>
        <td>{{ $variables['paciente_edad'] ?? 'N/A' }}</td>
        <td class="info-label">GÉNERO:</td>
        <td>{{ $variables['paciente_genero'] ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="info-label">FECHA PROCEDIMIENTO:</td>
        <td colspan="3">{{ $variables['fecha_procedimiento'] }}</td>
    </tr>
</table>

<!-- VOLUNTAD DE INFORMACIÓN -->
<div class="section-box">
    <div class="section-title">VOLUNTAD DE INFORMACIÓN</div>
    <div class="section-content">
        <p class="checkbox"><strong>¿DESEO SER INFORMADO sobre mi enfermedad y la intervención que me van a realizar?</strong></p>
        @if($consentimiento->desea_ser_informado)
            <p class="checkbox">☑ Sí, deseo ser informado directamente</p>
            <p class="checkbox">☐ Deseo que la información sea proporcionada a mi familiar/tutor/representante</p>
        @else
            <p class="checkbox">☐ Sí, deseo ser informado directamente</p>
            <p class="checkbox">☑ Deseo que la información sea proporcionada a mi familiar/tutor/representante</p>
        @endif
        
        <p style="margin-top:10px;"><strong>"MANIFIESTO MI DESEO DE NO SER INFORMADO Y PRESTO MI CONSENTIMIENTO"</strong> para que se lleve a cabo el procedimiento descrito en este documento.</p>
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
        
        <!-- PACIENTE -->
        <p><strong>PACIENTE - DECLARO:</strong></p>
        <p style="font-size:8pt;text-align:justify;">
            Que he comprendido adecuadamente la información que contiene este documento, que firmo el consentimiento 
            para la realización del procedimiento que se describe en el mismo, que he recibido copia del mismo y 
            que conozco que el consentimiento puede ser revocado por escrito en cualquier momento.
        </p>
        <table class="firma-table">
            <tr>
                <td style="width:50%;">
                    <strong>NOMBRE/APELLIDOS:</strong><br>
                    {{ $consentimiento->paciente->nombres }} {{ $consentimiento->paciente->apellidos }}
                </td>
                <td style="width:50%;">
                    <strong>IDENTIFICACIÓN:</strong><br>
                    {{ $consentimiento->paciente->tipo_documento }}-{{ $consentimiento->paciente->numero_documento }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>FIRMA:</strong><br>
                    @if(isset($firmaPacienteBase64) && $firmaPacienteBase64)
                        <img src="{{ $firmaPacienteBase64 }}" class="firma-img">
                    @else
                        <div style="width:180px;height:70px;border:1px dashed #ccc;margin:5px 0;"></div>
                    @endif
                </td>
                <td>
                    <strong>FECHA:</strong><br>
                    @if($consentimiento->firmaPaciente)
                        {{ \Carbon\Carbon::parse($consentimiento->firmaPaciente->firmado_at)->format('d/m/Y H:i') }}
                    @else
                        {{ $variables['fecha_actual'] }}
                    @endif
                </td>
            </tr>
        </table>
        
        <!-- ACUDIENTE -->
        @if($consentimiento->acudiente && $firmaAcudienteBase64)
            <p style="margin-top:15px;"><strong>FAMILIAR/TUTOR/REPRESENTANTE - DECLARO:</strong></p>
            <p style="font-size:8pt;text-align:justify;">
                Que he comprendido adecuadamente la información que contiene este documento y firmo el consentimiento.
            </p>
            <table class="firma-table">
                <tr>
                    <td style="width:50%;">
                        <strong>NOMBRE:</strong><br>
                        {{ $consentimiento->firmaAcudiente->firmante_nombre }}
                    </td>
                    <td style="width:50%;">
                        <strong>IDENTIFICACIÓN:</strong><br>
                        {{ $consentimiento->firmaAcudiente->firmante_cedula }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>RELACIÓN:</strong><br>
                        {{ $consentimiento->firmaAcudiente->firmante_relacion }}
                    </td>
                    <td>
                        <strong>FIRMA:</strong><br>
                        @if($firmaAcudienteBase64)
                            <img src="{{ $firmaAcudienteBase64 }}" class="firma-img">
                        @else
                            <div style="width:180px;height:70px;border:1px dashed #ccc;margin:5px 0;"></div>
                        @endif
                    </td>
                </tr>
            </table>
        @endif
        
        <!-- MÉDICO -->
        <p style="margin-top:15px;"><strong>MÉDICO RESPONSABLE - DECLARO:</strong></p>
        <p style="font-size:8pt;text-align:justify;">
            Haber informado al paciente y al familiar, tutor o representante del mismo del objeto y naturaleza 
            del procedimiento que se le va a realizar, explicándole los riesgos y complicaciones posibles del mismo.
        </p>
        <table class="firma-table">
            <tr>
                <td style="width:50%;">
                    <strong>MÉDICO RESPONSABLE:</strong><br>
                    {{ $consentimiento->profesional->nombres }} {{ $consentimiento->profesional->apellidos }}
                </td>
                <td style="width:50%;">
                    <strong>REGISTRO MÉDICO:</strong><br>
                    {{ $consentimiento->profesional->registro_medico }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>FIRMA:</strong><br>
                    @if(isset($firmaProfesionalBase64) && $firmaProfesionalBase64)
                        <img src="{{ $firmaProfesionalBase64 }}" class="firma-img">
                    @else
                        <div style="width:180px;height:70px;border:1px dashed #ccc;margin:5px 0;"></div>
                    @endif
                </td>
                <td>
                    <strong>FECHA:</strong><br>
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
<div style="margin-top:20px;text-align:center;font-size:8pt;border-top:1px solid #000;padding-top:8px;">
    <p>Documento generado electrónicamente por el Sistema de Consentimientos Informados de Clínica Fidem</p>
    <p>Fecha de generación: {{ $variables['fecha_actual'] }}</p>
</div>

</body>
</html>