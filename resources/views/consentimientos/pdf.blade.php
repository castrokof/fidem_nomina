<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consentimiento Informado - {{$consentimiento->plantilla->nombre}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            margin: 20px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14pt;
        }
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .cabecera-info {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .cabecera-info p {
            margin: 3px 0;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: bold;
            border-left: 4px solid #333;
        }
        .content-body {
            text-align: justify;
            margin-bottom: 15px;
        }
        .firma-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .firma-bloque {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .firma-bloque p {
            margin: 5px 0;
            font-size: 10pt;
        }
        .firma-img {
            max-width: 200px;
            max-height: 80px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px;
            vertical-align: top;
        }
        .linea-firma {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 50px;
        }
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- CABECERA -->
    <div class="header">
        <h2>CLÍNICA FIDEM</h2>
        <p>Manizales, Colombia</p>
        <p><strong>CONSENTIMIENTO INFORMADO</strong></p>
        <h3>{{$consentimiento->plantilla->nombre}}</h3>
    </div>

    <!-- INFORMACIÓN DEL PACIENTE -->
    <div class="cabecera-info">
        <p><strong>Servicio:</strong> PROCEDIMIENTOS</p>
        <p><strong>Nº Identificación del Paciente:</strong> {{$consentimiento->paciente->tipo_documento}}-{{$consentimiento->paciente->numero_documento}}</p>
        <p><strong>Nombre del Paciente:</strong> {{$consentimiento->paciente->nombres}} {{$consentimiento->paciente->apellidos}}</p>
        @if($consentimiento->paciente->edad || $consentimiento->paciente->genero)
            <p><strong>Edad y Género:</strong>
                @if($consentimiento->paciente->edad)
                    {{$consentimiento->paciente->edad}} años
                @endif
                @if($consentimiento->paciente->genero)
                    - {{$consentimiento->paciente->genero}}
                @endif
            </p>
        @endif
        <p><strong>Fecha de la Cita:</strong> {{\Carbon\Carbon::parse($consentimiento->fecha_cita)->format('d/m/Y H:i')}}</p>
    </div>

    <!-- SECCIÓN INICIAL - Voluntad de información -->
    <div class="section-title">VOLUNTAD DE INFORMACIÓN</div>
    <div class="content-body">
        <p><strong>¿Deseo ser informado sobre mi enfermedad?</strong></p>
        @if($consentimiento->desea_ser_informado)
            <p>☑ Sí, deseo ser informado directamente</p>
            <p>☐ Deseo que la información sea proporcionada a mi familiar/tutor/representante</p>
        @else
            <p>☐ Sí, deseo ser informado directamente</p>
            <p>☑ Deseo que la información sea proporcionada a mi familiar/tutor/representante</p>
        @endif
    </div>

    <!-- CUERPO DEL DOCUMENTO -->
    <div class="section-title">INFORMACIÓN DEL PROCEDIMIENTO</div>
    <div class="content-body">
        {!! nl2br(e($consentimiento->plantilla->contenido)) !!}
    </div>

    <!-- SECCIÓN DE FIRMAS -->
    <div class="firma-section">
        <div class="section-title">DECLARACIONES Y FIRMAS</div>

        <!-- BLOQUE 1 - FIRMA DEL PACIENTE -->
        <div class="firma-bloque">
            <p><strong>PACIENTE - DECLARO:</strong></p>
            <p style="text-align: justify;">
                Que he comprendido adecuadamente la información que contiene este documento, que firmo el
                consentimiento para la realización del procedimiento que se describe en el mismo, que he
                recibido copia del mismo y que conozco que el consentimiento puede ser revocado por escrito
                en cualquier momento.
            </p>
            <table>
                <tr>
                    <td style="width: 50%;">
                        <p><strong>NOMBRE/APELLIDOS:</strong></p>
                        <p>{{$consentimiento->paciente->nombres}} {{$consentimiento->paciente->apellidos}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>IDENTIFICACIÓN:</strong></p>
                        <p>{{$consentimiento->paciente->tipo_documento}}-{{$consentimiento->paciente->numero_documento}}</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <p><strong>FIRMA:</strong></p>
                        @foreach($consentimiento->firmas as $firma)
                            @if($firma->tipo_firma == 'paciente')
                                <img src="{{$firma->firma_base64}}" alt="Firma Paciente" class="firma-img">
                            @endif
                        @endforeach
                    </td>
                    <td style="width: 50%;">
                        <p><strong>FECHA:</strong></p>
                        @foreach($consentimiento->firmas as $firma)
                            @if($firma->tipo_firma == 'paciente')
                                <p>{{\Carbon\Carbon::parse($firma->fecha_firma)->format('d/m/Y H:i')}}</p>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

        <!-- BLOQUE 2 - FIRMA DEL FAMILIAR/TUTOR/REPRESENTANTE (si existe) -->
        @if($consentimiento->acudientes->count() > 0)
            @foreach($consentimiento->acudientes as $acudiente)
                <div class="firma-bloque">
                    <p><strong>FAMILIAR/TUTOR/REPRESENTANTE - DECLARO:</strong></p>
                    <p style="text-align: justify;">
                        Que he comprendido adecuadamente la información que contiene este documento, que firmo el
                        consentimiento para la realización del procedimiento que se describe en el mismo, que he
                        recibido copia del mismo y que conozco que el consentimiento puede ser revocado por escrito
                        en cualquier momento.
                    </p>
                    <table>
                        <tr>
                            <td style="width: 50%;">
                                <p><strong>NOMBRE/APELLIDOS:</strong></p>
                                <p>{{$acudiente->nombres}} {{$acudiente->apellidos}}</p>
                            </td>
                            <td style="width: 50%;">
                                <p><strong>IDENTIFICACIÓN:</strong></p>
                                <p>{{$acudiente->tipo_documento}}-{{$acudiente->numero_documento}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <p><strong>PARENTESCO:</strong></p>
                                <p>{{$acudiente->parentesco}}</p>
                            </td>
                            <td style="width: 50%;">
                                <p><strong>TELÉFONO:</strong></p>
                                <p>{{$acudiente->telefono ?? 'N/A'}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <p><strong>FIRMA:</strong></p>
                                @foreach($consentimiento->firmas as $firma)
                                    @if($firma->tipo_firma == 'acudiente')
                                        <img src="{{$firma->firma_base64}}" alt="Firma Acudiente" class="firma-img">
                                    @endif
                                @endforeach
                            </td>
                            <td style="width: 50%;">
                                <p><strong>FECHA:</strong></p>
                                @foreach($consentimiento->firmas as $firma)
                                    @if($firma->tipo_firma == 'acudiente')
                                        <p>{{\Carbon\Carbon::parse($firma->fecha_firma)->format('d/m/Y H:i')}}</p>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            @endforeach
        @endif

        <!-- BLOQUE 3 - FIRMA DEL MÉDICO RESPONSABLE -->
        <div class="firma-bloque">
            <p><strong>MÉDICO RESPONSABLE - DECLARO:</strong></p>
            <p style="text-align: justify;">
                Haber informado al paciente y al familiar, tutor o representante del mismo del objeto y
                naturaleza del procedimiento que se le va a realizar, explicándole los riesgos y
                complicaciones posibles del mismo.
            </p>
            <table>
                <tr>
                    <td style="width: 50%;">
                        <p><strong>MÉDICO RESPONSABLE:</strong></p>
                        <p>{{$consentimiento->profesional->nombres}} {{$consentimiento->profesional->apellidos}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>REGISTRO MÉDICO:</strong></p>
                        <p>{{$consentimiento->profesional->registro_medico}}</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <p><strong>FIRMA:</strong></p>
                        @if($consentimiento->profesional->firma_base64)
                            <img src="{{$consentimiento->profesional->firma_base64}}" alt="Firma Profesional" class="firma-img">
                        @endif
                    </td>
                    <td style="width: 50%;">
                        <p><strong>FECHA:</strong></p>
                        @foreach($consentimiento->firmas as $firma)
                            @if($firma->tipo_firma == 'profesional')
                                <p>{{\Carbon\Carbon::parse($firma->fecha_firma)->format('d/m/Y H:i')}}</p>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <div style="margin-top: 30px; text-align: center; font-size: 9pt; border-top: 1px solid #000; padding-top: 10px;">
        <p>Documento generado electrónicamente por el Sistema de Consentimientos Informados de Clínica Fidem</p>
        <p>Fecha de generación: {{\Carbon\Carbon::now()->format('d/m/Y H:i:s')}}</p>
    </div>
</body>
</html>
