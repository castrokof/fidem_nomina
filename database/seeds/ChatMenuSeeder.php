<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea el menú del Asistente Virtual (Chat con IA)
     *
     * @return void
     */
    public function run()
    {
        // Verificar si ya existe el menú del chat
        $existingMenu = DB::table('menu')
            ->where('nombre', 'Asistente Virtual')
            ->orWhere('url', '/admin/chat')
            ->first();

        if ($existingMenu) {
            $this->command->warn('⚠ El menú del Asistente Virtual ya existe (ID: ' . $existingMenu->id . ')');
            return;
        }

        // Obtener el último orden de menús principales
        $ultimoOrden = DB::table('menu')->where('menu_id', 0)->max('orden');
        $ordenBase = $ultimoOrden ? $ultimoOrden + 1 : 1;

        // Crear entrada de menú para el Chat/Asistente Virtual
        $menuId = DB::table('menu')->insertGetId([
            'menu_id' => 0,  // Es un menú principal (sin padre)
            'nombre' => 'Asistente Virtual',
            'url' => '/admin/chat',
            'orden' => $ordenBase,
            'icono' => 'fas fa-robot',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✓ Menú del Asistente Virtual creado correctamente');
        $this->command->info('  - ID del menú: ' . $menuId);
        $this->command->info('  - Orden: ' . $ordenBase);
        $this->command->warn('⚠ IMPORTANTE: Debe asignar permisos a los roles desde el panel de administración (Menú-Rol)');
        $this->command->info('');
        $this->command->info('📝 No olvides configurar CLAUDE_API_KEY en tu archivo .env');
    }
}
