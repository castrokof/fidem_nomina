<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertValidacionDerechosMenu extends Migration
{
    public function up()
    {
        $existe = DB::table('menu')
            ->where('url', '/admin/validaciones-derechos')
            ->orWhere('nombre', 'Validación de Derechos')
            ->exists();

        if ($existe) return;

        $ultimoOrden = DB::table('menu')->where('menu_id', 0)->max('orden');

        DB::table('menu')->insert([
            'menu_id' => 0,
            'nombre'  => 'Validación de Derechos',
            'url'     => '/admin/validaciones-derechos',
            'orden'   => $ultimoOrden ? $ultimoOrden + 1 : 1,
            'icono'   => 'fas fa-shield-alt',
        ]);
    }

    public function down()
    {
        DB::table('menu')->where('url', '/admin/validaciones-derechos')->delete();
    }
}
