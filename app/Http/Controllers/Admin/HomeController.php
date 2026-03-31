<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Menu;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the home dashboard with site map
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all available menus for the user's role
        $menus = Menu::getMenu(true);

        // Get system modules information
        $modules = $this->getSystemModules();

        return view('home', compact('menus', 'modules'));
    }

    /**
     * Get system modules information
     */
    private function getSystemModules()
    {
        return [
            [
                'name' => 'Seguridad',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Gestión de usuarios, roles, permisos y menús',
                'color' => 'primary',
                'items' => [
                    ['name' => 'Usuarios', 'route' => 'usuario', 'permission' => true],
                    ['name' => 'Roles', 'route' => 'rol', 'permission' => true],
                    ['name' => 'Permisos', 'route' => 'permiso', 'permission' => true],
                    ['name' => 'Menús', 'route' => 'menu', 'permission' => true],
                ]
            ],
            [
                'name' => 'Nómina',
                'icon' => 'fas fa-money-bill-wave',
                'description' => 'Gestión de empleados, contratos, novedades y liquidación',
                'color' => 'success',
                'items' => [
                    ['name' => 'Empleados', 'route' => 'empleado', 'permission' => true],
                    ['name' => 'Contratos', 'route' => 'contratos', 'permission' => true],
                    ['name' => 'Novedades', 'route' => 'empleados_novedades', 'permission' => true],
                    ['name' => 'Liquidación', 'route' => 'nominaf', 'permission' => true],
                    ['name' => 'Informes', 'route' => 'informesnominaf', 'permission' => true],
                ]
            ],
            [
                'name' => 'Consentimientos Informados',
                'icon' => 'fas fa-file-signature',
                'description' => 'Gestión de consentimientos informados para pacientes',
                'color' => 'info',
                'items' => [
                    ['name' => 'Consentimientos', 'route' => 'consentimientos.index', 'permission' => true],
                    ['name' => 'Plantillas', 'route' => 'plantillas-ci.index', 'permission' => true],
                    ['name' => 'Pacientes', 'route' => 'pacientes.index', 'permission' => true],
                    ['name' => 'Profesionales', 'route' => 'profesionales.index', 'permission' => true],
                ]
            ],
            [
                'name' => 'Psicología',
                'icon' => 'fas fa-brain',
                'description' => 'Línea psicológica y gestión de evoluciones',
                'color' => 'warning',
                'items' => [
                    ['name' => 'Reporte Psicología', 'route' => 'reportepsico', 'permission' => true],
                    ['name' => 'Consultar Evolución', 'route' => 'analistapsico', 'permission' => true],
                    ['name' => 'Consultar Procedimiento', 'route' => 'consultaprocedimiento', 'permission' => true],
                    ['name' => 'Informe Psicológico', 'route' => 'informepsico', 'permission' => true],
                ]
            ],
            [
                'name' => 'Paliativos',
                'icon' => 'fas fa-heartbeat',
                'description' => 'Gestión de cuidados paliativos y pacientes',
                'color' => 'danger',
                'items' => [
                    ['name' => 'Base Paliativos', 'route' => 'indexpaliativos', 'permission' => true],
                    ['name' => 'Informes Paliativos', 'route' => 'informespaliativos', 'permission' => true],
                    ['name' => 'Observaciones', 'route' => 'observacionpaliativos', 'permission' => true],
                    ['name' => 'Fidem Contigo', 'route' => 'fidemcontigo.index', 'permission' => true],
                ]
            ],
            [
                'name' => 'Medicamentos Controlados',
                'icon' => 'fas fa-pills',
                'description' => 'Control de medicamentos y movimientos',
                'color' => 'secondary',
                'items' => [
                    ['name' => 'Medicamentos', 'route' => 'medicamentos-controlados.index', 'permission' => true],
                    ['name' => 'Movimientos', 'route' => 'medicamentos-controlados-movimientos.index', 'permission' => true],
                ]
            ],
        ];
    }
}
