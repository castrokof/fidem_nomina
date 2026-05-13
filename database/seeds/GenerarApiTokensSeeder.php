<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GenerarApiTokensSeeder extends Seeder
{
    /**
     * Genera api_token para todos los usuarios que no tengan uno.
     *
     * Ejecutar con:
     *   php artisan db:seed --class=GenerarApiTokensSeeder
     */
    public function run()
    {
        $usuarios = \App\Models\Seguridad\Usuario::whereNull('api_token')
            ->orWhere('api_token', '')
            ->get();

        foreach ($usuarios as $user) {
            $token = str_random(60);
            $user->api_token = $token;
            $user->save();

            $this->command->info("Usuario: {$user->name} | Token: {$token}");
        }

        $this->command->info("\n✅ Tokens generados para {$usuarios->count()} usuario(s).");
        $this->command->info("Copia el token del operario y pégalo en background.js y popup.js del plugin.");
    }
}
