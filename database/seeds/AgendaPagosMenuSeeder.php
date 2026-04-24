<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgendaPagosMenuSeeder extends Seeder
{
    public function run()
    {
        // Evitar duplicados
        $existe = DB::table('menu')
            ->where('url', '/pagos')
            ->orWhere('nombre', 'Agenda de Pagos')
            ->first();

        if ($existe) {
            $this->command->warn('⚠ El menú de Agenda de Pagos ya existe (ID: ' . $existe->id . ')');
            return;
        }

        $ultimoOrden = DB::table('menu')->where('menu_id', 0)->max('orden');
        $ordenBase   = $ultimoOrden ? $ultimoOrden + 1 : 1;

        $menuId = DB::table('menu')->insertGetId([
            'menu_id'    => 0,
            'nombre'     => 'Agenda de Pagos',
            'url'        => '/pagos',
            'orden'      => $ordenBase,
            'icono'      => 'fas fa-calendar-check',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✓ Menú de Agenda de Pagos creado correctamente');
        $this->command->info('  - ID del menú: ' . $menuId . ' · Orden: ' . $ordenBase);
        $this->command->warn('⚠ IMPORTANTE: Asigne el menú a los roles desde el panel Menú-Rol');
        $this->command->info('  Ejecute: php artisan migrate  (si aún no corrió las migraciones del módulo)');
    }
}
