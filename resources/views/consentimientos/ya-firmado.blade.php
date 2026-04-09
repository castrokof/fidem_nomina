<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento ya firmado - Clínica Fidem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 540px;
            width: 100%;
        }
        .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px 20px;
            text-align: center;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
        }
        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="icon-circle">
                <i class="fas fa-check-double"></i>
            </div>
            <h4 class="mb-1">Consentimiento ya firmado</h4>
            <p class="mb-0" style="opacity:0.9;font-size:0.9rem;">Este documento ya fue firmado exitosamente</p>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-success">
                <i class="fas fa-info-circle"></i>
                Este consentimiento informado ya cuenta con todas las firmas requeridas y no puede ser firmado nuevamente.
            </div>

            @if(isset($consentimiento))
            <div class="info-row">
                <strong><i class="fas fa-user text-muted mr-1"></i> Paciente:</strong><br>
                {{ $consentimiento->paciente_nombre }}
            </div>
            <div class="info-row">
                <strong><i class="fas fa-file-medical text-muted mr-1"></i> Procedimiento:</strong><br>
                {{ $consentimiento->plantilla->nombre ?? 'N/A' }}
            </div>
            <div class="info-row">
                <strong><i class="fas fa-user-md text-muted mr-1"></i> Profesional:</strong><br>
                {{ $consentimiento->profesional_nombre }}
            </div>
            <div class="info-row">
                <strong><i class="fas fa-calendar-check text-muted mr-1"></i> Estado:</strong><br>
                <span class="badge badge-success">Firmado</span>
            </div>
            @endif

            <div class="text-center mt-4 text-muted">
                <small><i class="fas fa-hospital"></i> Clínica Fidem &mdash; Si tiene alguna duda, comuníquese con el personal de la clínica.</small>
            </div>
        </div>
    </div>
</body>
</html>
