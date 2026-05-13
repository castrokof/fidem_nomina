<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidacionBoxaludMenuSeeder extends Seeder
{
    public function run()
    {
        $existe = DB::table('menu')
            ->where('url', '/admin/validaciones-boxalud')
            ->orWhere('nombre', 'Pacientes Boxalud')
            ->exists();

        if ($existe) {
            $this->command->info('ValidacionBoxaludMenuSeeder: el ítem de menú ya existe, omitiendo.');
            return;
        }

        DB::table('menu')->insert([
            'nombre'  => 'Pacientes Boxalud',
            'url'     => '/admin/validaciones-boxalud',
            'icono'   => 'fas fa-hospital-user',
            'orden'   => 0,
            'menu_id' => 0,
        ]);

        $this->command->info('ValidacionBoxaludMenuSeeder: ítem de menú creado correctamente.');
    }
}
