<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTablas([

            //'rol',
           // 'usuario',
           // 'usuario_rol',
           // 'menu',
           // 'menu_rol',


        ]


        );


           // $this->call(RolTablaSeeder::class);
          //  $this->call(UsuarioAdministradorSeeder::class);

            // Seeders del módulo CI-Fidem
            $this->call(EspecialidadSeeder::class);
            $this->call(ProfesionalSeeder::class);
            $this->call(PlantillaCISeeder::class);
            $this->call(MenuCIFidemSeeder::class);

            // Seeder del módulo Agenda de Pagos
            $this->call(AgendaPagosMenuSeeder::class);

            // Seeder del módulo Validación de Derechos
            $this->call(ValidacionDerechosMenuSeeder::class);

    }

    protected function truncateTablas(array $tablas){

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tablas as $tabla) {
            DB::table($tabla)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');


    }

}
