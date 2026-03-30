<?php

use Illuminate\Database\Seeder;
use App\Especialidad;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $especialidades = [
            [
                'nombre' => 'Medicina del Dolor',
                'codigo' => 'MD001',
                'descripcion' => 'Especialidad enfocada en el manejo y tratamiento del dolor crónico',
                'activo' => true
            ],
            [
                'nombre' => 'Anestesiología',
                'codigo' => 'AN001',
                'descripcion' => 'Especialidad médica dedicada a la anestesia y manejo del dolor perioperatorio',
                'activo' => true
            ],
            [
                'nombre' => 'Cirugía General',
                'codigo' => 'CG001',
                'descripcion' => 'Especialidad quirúrgica que abarca procedimientos del sistema digestivo y tejidos blandos',
                'activo' => true
            ],
            [
                'nombre' => 'Ortopedia',
                'codigo' => 'OR001',
                'descripcion' => 'Especialidad médica dedicada al estudio y tratamiento del sistema musculoesquelético',
                'activo' => true
            ],
            [
                'nombre' => 'Gastroenterología',
                'codigo' => 'GA001',
                'descripcion' => 'Especialidad médica que estudia el aparato digestivo y sus enfermedades',
                'activo' => true
            ]
        ];

        foreach ($especialidades as $especialidad) {
            Especialidad::firstOrCreate(
                ['codigo' => $especialidad['codigo']],
                $especialidad
            );
        }

        $this->command->info('Especialidades creadas exitosamente.');
    }
}
