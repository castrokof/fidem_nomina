<?php

use Illuminate\Database\Seeder;
use App\Profesional;
use App\Especialidad;

class ProfesionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener especialidades para asociar
        $medicinaDelDolor = Especialidad::where('nombre', 'Medicina del Dolor')->first();
        $anestesiologia = Especialidad::where('nombre', 'Anestesiología')->first();

        $profesionales = [
            [
                'usuario_id' => null, // Sin login por ahora
                'especialidad_id' => $medicinaDelDolor ? $medicinaDelDolor->id : null,
                'codigo_usuario' => 'MED001',
                'nombres' => 'Santiago',
                'apellidos' => 'Sánchez',
                'tipo_documento' => 'CC',
                'numero_documento' => '1107034356',
                'registro_medico' => '1107034356',
                'tarjeta_profesional' => 'TP12345',
                'telefono' => '3001234567',
                'email' => 'ssanchez@clinicafidem.com',
                'firma_base64' => null, // Se registrará desde el panel
                'activo' => true
            ],
            [
                'usuario_id' => null,
                'especialidad_id' => $anestesiologia ? $anestesiologia->id : null,
                'codigo_usuario' => 'MED002',
                'nombres' => 'María',
                'apellidos' => 'López',
                'tipo_documento' => 'CC',
                'numero_documento' => '43567890',
                'registro_medico' => '43567890',
                'tarjeta_profesional' => 'TP67890',
                'telefono' => '3009876543',
                'email' => 'mlopez@clinicafidem.com',
                'firma_base64' => null,
                'activo' => true
            ]
        ];

        foreach ($profesionales as $profesional) {
            Profesional::firstOrCreate(
                ['codigo_usuario' => $profesional['codigo_usuario']],
                $profesional
            );
        }

        $this->command->info('Profesionales de ejemplo creados exitosamente.');
    }
}
