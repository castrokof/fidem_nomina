<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCIFidemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea el menú del módulo CI-Fidem (Consentimientos Informados)
     *
     * @return void
     */
    public function run()
    {
        // Obtener el último orden de menús principales
        $ultimoOrden = DB::table('menu')->where('menu_id', 0)->max('orden');
        $ordenBase = $ultimoOrden ? $ultimoOrden + 1 : 1;

        // Menú principal: Consentimientos Informados
        $menuPrincipalId = DB::table('menu')->insertGetId([
            'menu_id' => 0,
            'nombre' => 'Consentimientos Informados',
            'url' => '#',
            'orden' => $ordenBase,
            'icono' => 'fas fa-file-signature',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Submenú 1: Consentimientos (listado)
        DB::table('menu')->insert([
            'menu_id' => $menuPrincipalId,
            'nombre' => 'Consentimientos',
            'url' => '/admin/consentimientos',
            'orden' => 1,
            'icono' => 'fas fa-list',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Submenú 2: Profesionales
        DB::table('menu')->insert([
            'menu_id' => $menuPrincipalId,
            'nombre' => 'Profesionales',
            'url' => '/admin/profesionales',
            'orden' => 2,
            'icono' => 'fas fa-user-md',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Submenú 3: Pacientes
        DB::table('menu')->insert([
            'menu_id' => $menuPrincipalId,
            'nombre' => 'Pacientes',
            'url' => '/admin/pacientes',
            'orden' => 3,
            'icono' => 'fas fa-users',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Submenú 4: Plantillas CI
        DB::table('menu')->insert([
            'menu_id' => $menuPrincipalId,
            'nombre' => 'Plantillas CI',
            'url' => '/admin/plantillas-ci',
            'orden' => 4,
            'icono' => 'fas fa-file-alt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Submenú 5: Especialidades
        DB::table('menu')->insert([
            'menu_id' => $menuPrincipalId,
            'nombre' => 'Especialidades',
            'url' => '/admin/especialidades',
            'orden' => 5,
            'icono' => 'fas fa-stethoscope',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Submenú 6: Importar Plantillas
        DB::table('menu')->insert([
            'menu_id' => $menuPrincipalId,
            'nombre' => 'Importar Plantillas',
            'url' => '/admin/importar-plantillas',
            'orden' => 6,
            'icono' => 'fas fa-file-import',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✓ Menú de CI-Fidem creado correctamente');
        $this->command->info('  - Menú principal ID: ' . $menuPrincipalId);
        $this->command->info('  - Consentimientos Informados con 6 submenús');
        $this->command->warn('⚠ IMPORTANTE: Debe asignar permisos a los roles desde el panel de administración (Menú-Rol)');
    }
}
