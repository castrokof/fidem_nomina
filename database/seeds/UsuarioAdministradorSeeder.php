<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UsuarioAdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


         //Crear cargo menu-rol




       DB::table('usuario')->insert([
            'papellido'=>strtoupper('Castro'),
            'sapellido'=>strtoupper('Galeano'),
            'pnombre'=>strtoupper('Jhonnathan'),
            'snombre'=>null,
            'tipo_documento'=>strtoupper('CC'),
            'documento'=>'1130629762',
            'usuario'=>'jcastro',
            'password'=>bcrypt('123456'),
            'remenber_token'=>bcrypt('123456'),
            'email'=>'castrokof@gmail.com',
            'celular'=>'3175018125',
            'observacion'=>strtoupper('Prueba'),
            'ips'=>strtoupper('atencion fidem s.a.s'),
            'activo'=>'1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')

             ]);




        DB::table('usuario_rol')->insert([

            'rol_id'=>1,
            'usuario_id'=>1,



        ]);


        //Creación de menu

        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Admin',
            'url'=>'#',
            'orden'=>1,
            'icono'=>'far fa-building'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 1,
            'nombre'=>'Lista de Menus',
            'url'=>'admin/menu',
            'orden'=>1,
            'icono'=>'fa fa-cog fa-spin fa-3x fa-fw'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 1,
            'nombre'=>'Crear_menu',
            'url'=>'admin/menu/crear',
            'orden'=>2,
            'icono'=>'fas fa-clipboard-list'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 1,
            'nombre'=>'Roles',
            'url'=>'admin/rol',
            'orden'=>3,
            'icono'=>'fa fa-list'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 1,
            'nombre'=>'Asignar Menus',
            'url'=>'admin/menu-rol',
            'orden'=>4,
            'icono'=>'fa fa-tasks'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Registro Usuarios',
            'url'=>'#',
            'orden'=>2,
            'icono'=>'fa fa-users'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Registrar Turnos',
            'url'=>'#',
            'orden'=>5,
            'icono'=>'fas fa-clinic-medical'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 7,
            'nombre'=>'Reporte Turnos',
            'url'=>'hoursxuser',
            'orden'=>1,
            'icono'=>'fas fa-book-medical'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 6,
            'nombre'=>'Registrar Usuario',
            'url'=>'usuario',
            'orden'=>1,
            'icono'=>'fas fa-user-plus'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Registro Cargos',
            'url'=>'usuario',
            'orden'=>3,
            'icono'=>'fas fa-chart-line'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 10,
            'nombre'=>'Registrar Cargo',
            'url'=>'position',
            'orden'=>1,
            'icono'=>'fas fa-plus-circle'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Supervisar Turnos',
            'url'=>'#',
            'orden'=>4,
            'icono'=>'fas fa-tasks'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 12,
            'nombre'=>'Validar Turnos',
            'url'=>'informesh',
            'orden'=>1,
            'icono'=>'fas fa-check-double'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 12,
            'nombre'=>'Informe Liquidado',
            'url'=>'informe-liquid',
            'orden'=>2,
            'icono'=>'fas fa-file-invoice-dollar fa-2x'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Nomina fijos',
            'url'=>'#',
            'orden'=>5,
            'icono'=>'fas fa-file-invoice-dollar fa-2x'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 15,
            'nombre'=>'Crear nomina',
            'url'=>'nominaf',
            'orden'=>1,
            'icono'=>'fas fa-money-check-alt'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 1,
            'nombre'=>'Listas',
            'url'=>'#',
            'orden'=>'5',
            'icono'=>'fa fa-book'
        ]);

        DB::table('menu')->insert([

            'menu_id'=> 17,
            'nombre'=>'Detalle listas',
            'url'=>'detallelistas',
            'orden'=>'1',
            'icono'=>'fa fa-list'
        ]);

        DB::table('menu')->insert([

            'menu_id'=> 6,
            'nombre'=>'Registrar Empleado',
            'url'=>'empleado',
            'orden'=>'2',
            'icono'=>'fas fa-user-plus'
        ]);

        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Base Paliativos',
            'url'=>'#',
            'orden'=>'2',
            'icono'=>'fas fa-user-injured'
        ]);

        DB::table('menu')->insert([

            'menu_id'=> 20,
            'nombre'=>'Lista paliativos',
            'url'=>'paliativos-index',
            'orden'=>'1',
            'icono'=>'fas fa-users'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 0,
            'nombre'=>'Linea Psicologica',
            'url'=>'#',
            'orden'=>'3',
            'icono'=>'fas fa-phone'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 22,
            'nombre'=>'Reporte Linea',
            'url'=>'/reporte_psicologia',
            'orden'=>'1',
            'icono'=>'fas fa-book'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 22,
            'nombre'=>'Reporte Evolucion',
            'url'=>'/consultar_evolucion',
            'orden'=>'2',
            'icono'=>'fas fa-file-alt'
        ]);

        DB::table('menu')->insert([

            'menu_id'=> 1,
            'nombre'=>'Cargue Excel',
            'url'=>'#',
            'orden'=>'1',
            'icono'=>'far fa-file-excel'
        ]);
        DB::table('menu')->insert([

            'menu_id'=> 25,
            'nombre'=>'Cargar Excel',
            'url'=>'archivos',
            'orden'=>'1',
            'icono'=>'fas fa-file-import'
        ]);




        //Relación menu-rol

        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 1
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 2
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 3
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 4
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 5
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 7
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 8
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 6
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 9
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 2,
            'menu_id'=> 6
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 2,
            'menu_id'=> 9
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 2,
            'menu_id'=> 7
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 2,
            'menu_id'=> 8
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 10
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 11
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 12
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 13
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 14
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 15
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 16
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 17
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 18
        ]);

        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 19
        ]);

        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 20
        ]);


        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 21
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 22
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 23
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 24
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 25
        ]);
        DB::table('menu_rol')->insert([

            'rol_id'=> 1,
            'menu_id'=> 26
        ]);


        DB::table('listas')->insert([
            'slug'=>strtoupper('EMP'),
            'nombre'=>strtoupper('LISTA DE EMPRESAS'),
            'descripcion'=>strtoupper('LISTADO PARA CREAR LAS EMPRESAS'),
            'activo'=>'SI',
            'user_id'=>1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
              ]);
              DB::table('listas')->insert([
                'slug'=>strtoupper('BANK'),
                'nombre'=>strtoupper('BANCOS'),
                'descripcion'=>strtoupper('LISTA DE BANCOS'),
                'activo'=>'SI',
                'user_id'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                  ]);
                  DB::table('listas')->insert([
                    'slug'=>strtoupper('TYAC'),
                    'nombre'=>strtoupper('TYPE_ACCOUNT'),
                    'descripcion'=>strtoupper('LISTA DE TIPOS DE CUENTA'),
                    'activo'=>'SI',
                    'user_id'=>1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                      ]);
                      DB::table('listas')->insert([
                        'slug'=>strtoupper('CARG'),
                        'nombre'=>strtoupper('LISTA DE CARGOS'),
                        'descripcion'=>strtoupper('LISTA DE CARGOS'),
                        'activo'=>'SI',
                        'user_id'=>1,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                          ]);

                          DB::table('listas')->insert([
                            'slug'=>strtoupper('LEPS'),
                            'nombre'=>strtoupper('LISTA DE EPS'),
                            'descripcion'=>strtoupper('LISTA DE EPS'),
                            'activo'=>'SI',
                            'user_id'=>1,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                              ]);

                              DB::table('listas')->insert([
                                'slug'=>strtoupper('LARL'),
                                'nombre'=>strtoupper('LISTA DE ARL'),
                                'descripcion'=>strtoupper('LISTA DE ARL'),
                                'activo'=>'SI',
                                'user_id'=>1,
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                  ]);

                                  DB::table('listas')->insert([
                                    'slug'=>strtoupper('LAFP'),
                                    'nombre'=>strtoupper('LISTA DE FONDO DE PENSIONES'),
                                    'descripcion'=>strtoupper('LISTA DE FONDO DE PENSIONES'),
                                    'activo'=>'SI',
                                    'user_id'=>1,
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                      ]);
                                      DB::table('listas')->insert([
                                        'slug'=>strtoupper('LFCA'),
                                        'nombre'=>strtoupper('LISTA DE FONDO DE CESANTIAS'),
                                        'descripcion'=>strtoupper('LISTA DE FONDO DE CESANTIAS'),
                                        'activo'=>'SI',
                                        'user_id'=>1,
                                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                          ]);
                                          DB::table('listas')->insert([
                                            'slug'=> 'TDOC',
                                            'nombre'=> 'TIPO DE DOCUMENTOS',
                                            'descripcion'=> 'TIPOS DE DOCUMENTOS',
                                            'activo'=> 'SI',
                                            'user_id'=> 1
                                        ]);
                                        DB::table('listas')->insert([
                                            'slug'=> 'ESTA',
                                            'nombre'=> 'ESTADOS',
                                            'descripcion'=> 'ESTADOS DEL PACIENTE',
                                            'activo'=> 'SI',
                                            'user_id'=> 1
                                        ]);
                                        DB::table('listas')->insert([
                                            'slug'=> 'AMBI',
                                            'nombre'=> 'AMBITOS',
                                            'descripcion'=> 'AMBITOS DE ATENCIÓN DE PACIENTES',
                                            'activo'=> 'SI',
                                            'user_id'=> 1
                                        ]);
                                        DB::table('listas')->insert([
                                            'slug'=> 'TOBS',
                                            'nombre'=> 'TIPO DE OBSERVACION',
                                            'descripcion'=> 'TIPO DE OBSERVACION',
                                            'activo'=> 'SI',
                                            'user_id'=> 1
                                        ]);
                                        DB::table('listas')->insert([
                                            'slug'=> 'SUBT',
                                            'nombre'=> 'SUB TIPO DE OBSERVACION',
                                            'descripcion'=> 'SUB TIPO DE OBSERVACION',
                                            'activo'=> 'SI',
                                            'user_id'=> 1
                                        ]);

                                        DB::table('listas')->insert([
                                            'slug'=> 'SUBTA',
                                            'nombre'=> 'SUB TIPO DE ALERTA',
                                            'descripcion'=> 'SUB TIPO DE ALERTA',
                                            'activo'=> 'SI',
                                            'user_id'=> 1
                                        ]);







                      DB::table('listasdetalle')->insert([
                        ['slug'=>strtoupper('FIDEM'),'nombre'=>strtoupper('ATENCION FIDEM SAS'),'descripcion'=>strtoupper('IPS FIDEM'),
                        'activo'=>'SI', 'listas_id'=>1,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('MEDCOL'),'nombre'=>strtoupper('SALUD MEDCOL SAS'),'descripcion'=>strtoupper('FARMACIA MEDCOL'),
                        'activo'=>'SI', 'listas_id'=>1,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('TEMPUS'),'nombre'=>strtoupper('TEMPUS ATENCIÓN INTEGRAL SAS'),'descripcion'=>strtoupper('FARMACIA TEMPUS'),
                        'activo'=>'SI', 'listas_id'=>1,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                        ['slug'=>strtoupper('DAVA'),'nombre'=>strtoupper('DAVIVIENDA'),'descripcion'=>strtoupper('DAVIVIENDA'),
                        'activo'=>'SI', 'listas_id'=>2,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('BOGA'),'nombre'=>strtoupper('BANCO DE BOGOTA'),'descripcion'=>strtoupper('BANCO DE BOGOTA'),
                        'activo'=>'SI', 'listas_id'=>2,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('BANC'),'nombre'=>strtoupper('BANCOLOMBIA'),'descripcion'=>strtoupper('BANCOLOMBIA'),
                        'activo'=>'SI', 'listas_id'=>2,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('AVVS'),'nombre'=>strtoupper('BANCO AV VILLAS'),'descripcion'=>strtoupper('BANCO AV VILLAS'),
                        'activo'=>'SI', 'listas_id'=>2,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                        ['slug'=>strtoupper('AHOS'),'nombre'=>strtoupper('AHORROS'),'descripcion'=>strtoupper('AHORROS'),
                        'activo'=>'SI', 'listas_id'=>3,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('CORE'),'nombre'=>strtoupper('CORRIENTE'),'descripcion'=>strtoupper('CORRIENTE'),
                        'activo'=>'SI', 'listas_id'=>3,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


                        ['slug'=>strtoupper('MGRAL'),'nombre'=>strtoupper('MEDICO GENERAL'),'descripcion'=>strtoupper('MEDICO GENERAL'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('MESP'),'nombre'=>strtoupper('MEDICO ESPECIALISTA'),'descripcion'=>strtoupper('MEDICO ESPECIALISTA'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('PSIC'),'nombre'=>strtoupper('PSICOLOGIA'),'descripcion'=>strtoupper('PSICOLOGIA'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('AUXM'),'nombre'=>strtoupper('AUXILIAR DE ENFERMERIA'),'descripcion'=>strtoupper('AUXILIAR DE ENFERMERIA'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('AUXA'),'nombre'=>strtoupper('AUXILIAR ADMINISTRATIVO'),'descripcion'=>strtoupper('AUXILIAR ADMINISTRATIVO'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('MESA'),'nombre'=>strtoupper('MEDICO ESP ANESTESIOLOGIA'),'descripcion'=>strtoupper('MEDICO ESPECIALISTA EN ANESTESIOLOGIA'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('MESA'),'nombre'=>strtoupper('MEDICO ESP ALGOLOGIA'),'descripcion'=>strtoupper('MEDICO ESPECIALISTA EN ALGOLOGIA'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('MESO'),'nombre'=>strtoupper('MEDICO ESP ORTOPEDIA'),'descripcion'=>strtoupper('MEDICO ESPECIALISTA EN ORTOPEDIA'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('MESD'),'nombre'=>strtoupper('MEDICO ESP EN DOLOR Y CUIDADOS PALIATIVOS'),'descripcion'=>strtoupper('MEDICO ESPECIALISTA EN DOLOR Y CUIDADOS PALIATIVOS'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('CSST'),'nombre'=>strtoupper('COORDINADOR DE SST'),'descripcion'=>strtoupper('COORDINADOR DE SST'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('JDES'),'nombre'=>strtoupper('JEFE DE SISTEMAS'),'descripcion'=>strtoupper('JEFE DE SISTEMAS DE INFORMACIÓN'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('GGEN'),'nombre'=>strtoupper('GERENTE'),'descripcion'=>strtoupper('GERENTE'),
                        'activo'=>'SI', 'listas_id'=>4,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                        ['slug'=>strtoupper('COMF'),'nombre'=>strtoupper('EPS COMFENALCO VALLE'),'descripcion'=>strtoupper('EPS COMFENALCO VALLE'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('SANI'),'nombre'=>strtoupper('EPS SANITAS'),'descripcion'=>strtoupper('EPS SANITAS'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('SURA'),'nombre'=>strtoupper('EPS SURA'),'descripcion'=>strtoupper('EPS SURA'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('COOS'),'nombre'=>strtoupper('COOSALUD EPS'),'descripcion'=>strtoupper('COOSALUD EPS'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('ESOS'),'nombre'=>strtoupper('SOS EPS'),'descripcion'=>strtoupper('SOS EPS'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('COMF'),'nombre'=>strtoupper('COMFANDI EPS'),'descripcion'=>strtoupper('COMFANDI EPS'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('STOT'),'nombre'=>strtoupper('EPS SALUD TOTAL'),'descripcion'=>strtoupper('EPS SALUD TOTAL'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('NEPS'),'nombre'=>strtoupper('NUEVA EPS'),'descripcion'=>strtoupper('NUEVA EPS'),
                        'activo'=>'SI', 'listas_id'=>5,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                        ['slug'=>strtoupper('PORV'),'nombre'=>strtoupper('PORVENIR'),'descripcion'=>strtoupper('PORVENIR'),
                        'activo'=>'SI', 'listas_id'=>7,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('COLP'),'nombre'=>strtoupper('COLPENSIONES'),'descripcion'=>strtoupper('COLPENSIONES'),
                        'activo'=>'SI', 'listas_id'=>7,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('PROT'),'nombre'=>strtoupper('PROTECCION'),'descripcion'=>strtoupper('PROTECCION'),
                        'activo'=>'SI', 'listas_id'=>7,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                        ['slug'=>strtoupper('PROT'),'nombre'=>strtoupper('PROTECCION'),'descripcion'=>strtoupper('PROTECCION'),'activo'=>'SI', 'listas_id'=>8,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('COLF'),'nombre'=>strtoupper('COLFONDOS'),'descripcion'=>strtoupper('COLFONDOS'),
                        'activo'=>'SI', 'listas_id'=>8,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('PORV'),'nombre'=>strtoupper('PORVENIR'),'descripcion'=>strtoupper('PORVENIR'),
                        'activo'=>'SI', 'listas_id'=>8,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                        ['slug'=>strtoupper('AXA'),'nombre'=>strtoupper('AXA COLPATRIA SEGUROS S.A.'),'descripcion'=>strtoupper('AXA COLPATRIA SEGUROS S.A.'),'activo'=>'SI', 'listas_id'=>6,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('COLM'),'nombre'=>strtoupper('COLMENA SEGUROS S.A.'),'descripcion'=>strtoupper('COLMENA SEGUROS S.A.'),'activo'=>'SI', 'listas_id'=>6,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('AUR'),'nombre'=>strtoupper('COMPAÑÍA DE SEGUROS DE VIDA AURORA S.A.'),'descripcion'=>strtoupper('COMPAÑÍA DE SEGUROS DE VIDA AURORA S.A.'),'activo'=>'SI', 'listas_id'=>6,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('EQU'),'nombre'=>strtoupper('LA EQUIDAD SEGUROS'),'descripcion'=>strtoupper('LA EQUIDAD SEGUROS'),'activo'=>'SI', 'listas_id'=>6,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('POS'),'nombre'=>strtoupper('POSITIVA COMPAÑÍA DE SEGUROS S.A.'),'descripcion'=>strtoupper('POSITIVA COMPAÑÍA DE SEGUROS S.A.'),'activo'=>'SI', 'listas_id'=>6,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                        ['slug'=>strtoupper('SUR'),'nombre'=>strtoupper('SEGUROS GENERALES SURAMERICANA S.A.'),'descripcion'=>strtoupper('SEGUROS GENERALES SURAMERICANA S.A.'),'activo'=>'SI', 'listas_id'=>6,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                       ['slug'=> 'CC', 'nombre'=> 'CC','descripcion'=> 'CEDULA DE CIUDADANIA','activo'=> 'SI', 'listas_id'=> 9,  'user_id'=> 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=> 'TI','nombre'=> 'TI','descripcion'=> 'TARJETA DE IDENTIDAD','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'CE','nombre'=> 'CE','descripcion'=> 'CEDULA DE EXTRANJERIA','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'RC','nombre'=> 'RC','descripcion'=> 'REGISTRO CIVIL','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'PA','nombre'=> 'PA','descripcion'=> 'PASAPORTE','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'AS','nombre'=> 'AS','descripcion'=> 'ADULTO SIN IDENTIFICACIÓN','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'MS','nombre'=> 'MS','descripcion'=> 'MENOR SIN IDENTIFICACION','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'NI','nombre'=> 'NI','descripcion'=> 'NIT','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'NU','nombre'=> 'NU','descripcion'=> 'NU','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'PE','nombre'=> 'PE','descripcion'=> 'PERMISO ESPECIAL','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'SC','nombre'=> 'SC','descripcion'=> 'SALVO CONDUCTO','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'CD','nombre'=> 'CD','descripcion'=> 'CARNET DIPLOMATICO','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'RE','nombre'=> 'RE','descripcion'=> 'RESIDENTE ESPECIAL','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],
                       ['slug'=> 'PT','nombre'=> 'PT','descripcion'=> 'PROTECCIÓN TEMPORAL','activo'=> 'SI','listas_id'=> 9,'user_id'=> 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s') ],


                       ['slug'=>strtoupper('FALL'),'nombre'=>strtoupper('FALLECIDO'),'descripcion'=>strtoupper('FALLECIDO'),'activo'=>'SI', 'listas_id'=>10,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('SCON'),'nombre'=>strtoupper('SIN CONTACTO'),'descripcion'=>strtoupper('SIN CONTACTO'),'activo'=>'SI', 'listas_id'=>10,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('ASIG'),'nombre'=>strtoupper('ASIGNADO'),'descripcion'=>strtoupper('ASIGNADO'),'activo'=>'SI', 'listas_id'=>10,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                       ['slug'=>strtoupper('ATEND'),'nombre'=>strtoupper('ATENDIDO'),'descripcion'=>strtoupper('PACIENTE ATENDIDO'),'activo'=>'SI', 'listas_id'=>10,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('PNQC'),'nombre'=>strtoupper('PACIENTE NO ACEPTA CITA'),'descripcion'=>strtoupper('PACIENTE NO ACEPTA CITA'),'activo'=>'SI', 'listas_id'=>10,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                       ['slug'=>strtoupper('AMBL'),'nombre'=>strtoupper('AMBULATORIO'),'descripcion'=>strtoupper('AMBULATORIO'),'activo'=>'SI', 'listas_id'=>11,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('HOSP'),'nombre'=>strtoupper('DOMICILIARIO'),'descripcion'=>strtoupper('DOMICILIARIO'),'activo'=>'SI', 'listas_id'=>11,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                       ['slug'=>strtoupper('SEGU'),'nombre'=>strtoupper('SEGUIMIENTO'),'descripcion'=>strtoupper('SEGUIMIENTO PACIENTE'),'activo'=>'SI', 'listas_id'=>12,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('HOSP'),'nombre'=>strtoupper('HOSPITALIZADO'),'descripcion'=>strtoupper('HOSPITALIZACION DEL PACIENTE'),'activo'=>'SI', 'listas_id'=>12,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('ALER'),'nombre'=>strtoupper('ALERTA'),'descripcion'=>strtoupper('ALERTA DEL PACIENTE'),'activo'=>'SI', 'listas_id'=>12,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('EGRE'),'nombre'=>strtoupper('EGRESO'),'descripcion'=>strtoupper('SE USA CUANDO AL PACIENTE LE DAN SALIDA DEL PROGRAMA'),'activo'=>'SI', 'listas_id'=>12,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                       ['slug'=>strtoupper('DOLO'),'nombre'=>strtoupper('DOLOR'),'descripcion'=>strtoupper('DOLOR'),'activo'=>'SI', 'listas_id'=>13,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('OTRO'),'nombre'=>strtoupper('OTRO'),'descripcion'=>strtoupper('OTRO'),'activo'=>'SI', 'listas_id'=>13,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


                       ['slug'=>strtoupper('MEDI'),'nombre'=>strtoupper('MEDICAMENTO'),'descripcion'=>strtoupper('MEDICAMENTO'),'activo'=>'SI', 'listas_id'=>14,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('GRAV'),'nombre'=>strtoupper('GRAVE'),'descripcion'=>strtoupper('GRAVE'),'activo'=>'SI', 'listas_id'=>14,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
                       ['slug'=>strtoupper('CITA'),'nombre'=>strtoupper('ASIGNAR CITA'),'descripcion'=>strtoupper('ASIGNAR CITA PALIATIVOS O EXPERTO'),'activo'=>'SI', 'listas_id'=>14,'user_id'=>1,  'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
                        ]);
    }
}
