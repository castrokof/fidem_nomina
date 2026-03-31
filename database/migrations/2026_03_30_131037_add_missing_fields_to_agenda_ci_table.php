<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingFieldsToAgendaCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda_ci', function (Blueprint $table) {
            // Campos de solicitud y programación
            $table->string('orden', 10)->nullable()->after('codigo_consultorio');
            $table->dateTime('fecha_solicitud')->nullable()->after('fecha');
            $table->dateTime('fecha_solicitada')->nullable()->after('fecha_solicitud');
            $table->string('tipo_solicitud', 5)->nullable()->after('fecha_solicitada');
            
            // Campos de IPS y centro productivo
            $table->string('ips', 20)->nullable()->after('tipo_solicitud');
            $table->string('centroprod', 10)->nullable()->after('ips');
            
            // Campos de identificación del paciente (valores originales de API)
            $table->string('tipdocum', 5)->nullable()->after('paciente_tipo_doc');
            $table->string('numdocum', 30)->nullable()->after('tipdocum');
            
            // Campos de nombre desglosado (para mayor flexibilidad)
            $table->string('nombre1', 100)->nullable()->after('paciente_nombre');
            $table->string('nombre2', 100)->nullable()->after('nombre1');
            $table->string('apellido1', 100)->nullable()->after('nombre2');
            $table->string('apellido2', 100)->nullable()->after('apellido1');
            
            // Campos de estado y flags
            $table->string('nuevo', 1)->default('0')->after('apellido2');
            $table->string('estado', 5)->nullable()->after('nuevo');
            $table->string('atendido', 5)->nullable()->after('estado');
            
            // Observaciones y detalles clínicos
            $table->text('observaciones')->nullable()->after('atendido');
            
            // Campos de usuario externo
            $table->string('usuario_externo', 100)->nullable()->after('codigo_usuario');
            
            // Campos de facturación
            $table->string('ips_factura', 20)->nullable()->after('empresafac');
            $table->string('documento_factura', 10)->nullable()->after('ips_factura');
            $table->string('px_factura', 20)->nullable()->after('numero_factura');
            
            // Campo de cupo web
            $table->string('cupo_web', 1)->default('0')->after('contrato');
            
            // Campos de CUPS adicionales
            $table->string('cups_descripcion', 300)->nullable()->after('cups_codigo');
            
            // Campos de internacionalización (por si se usan en el futuro)
            $table->string('ips_internacion', 20)->nullable()->after('cups_descripcion');
            $table->string('documento_internacion', 20)->nullable()->after('ips_internacion');
            $table->string('orden_internacion', 30)->nullable()->after('documento_internacion');
            $table->dateTime('atencion_internacion')->nullable()->after('orden_internacion');
            $table->string('px_internacion', 20)->nullable()->after('atencion_internacion');
            
            // Campos adicionales de paciente
            $table->string('embarazo', 1)->default('0')->after('px_internacion');
            $table->string('regimenfac', 5)->nullable()->after('embarazo');
            $table->string('nivelfac', 2)->nullable()->after('regimenfac');
            $table->string('tipoafilfac', 5)->nullable()->after('nivelfac');
            
            // Índices para búsquedas frecuentes
            $table->index(['fecha', 'codigo_consultorio'], 'idx_fecha_consultorio');
            $table->index(['estado', 'atendido'], 'idx_estado_atendido');
            $table->index(['paciente_cedula'], 'idx_paciente_cedula');
            $table->index(['codigo_usuario', 'fecha'], 'idx_usuario_fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda_ci', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['fecha', 'codigo_consultorio']);
            $table->dropIndex(['estado', 'atendido']);
            $table->dropIndex(['paciente_cedula']);
            $table->dropIndex(['codigo_usuario', 'fecha']);
            
            // Eliminar campos agregados
            $table->dropColumn([
                'orden',
                'fecha_solicitud',
                'fecha_solicitada',
                'tipo_solicitud',
                'ips',
                'centroprod',
                'tipdocum',
                'numdocum',
                'nombre1',
                'nombre2',
                'apellido1',
                'apellido2',
                'nuevo',
                'estado',
                'atendido',
                'observaciones',
                'usuario_externo',
                'ips_factura',
                'documento_factura',
                'px_factura',
                'cupo_web',
                'cups_descripcion',
                'ips_internacion',
                'documento_internacion',
                'orden_internacion',
                'atencion_internacion',
                'px_internacion',
                'embarazo',
                'regimenfac',
                'nivelfac',
                'tipoafilfac',
            ]);
        });
    }
}