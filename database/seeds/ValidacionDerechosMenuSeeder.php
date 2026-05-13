<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidacionDerechosMenuSeeder extends Seeder
{
    public function run()
    {
        $existe = DB::table('menu')
            ->where('url', '/admin/validaciones-derechos')
            ->orWhere('nombre', 'Validación de Derechos')
            ->exists();

        if ($existe) {
            $this->command->info('ValidacionDerechosMenuSeeder: el ítem de menú ya existe, omitiendo.');
            return;
        }

        DB::table('menu')->insert([
            'nombre' => 'Validación de Derechos',
            'url'    => '/admin/validaciones-derechos',
            'icono'  => 'fas fa-shield-alt',
            'orden'  => 0,
            'menu_id'=> 0,
        ]);

        $this->command->info('ValidacionDerechosMenuSeeder: ítem de menú creado.');
    }
}
