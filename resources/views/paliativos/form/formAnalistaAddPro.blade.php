    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset("assets/$theme/dist/img/user_default.jpg  ") }}" alt="User profile picture">
                        </div>

                        <h2 id="namesaddpro" class="profile-username text-center text-muted"></h2>
                        <p id="documentsaddpro" class="text-muted text-center"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                 <a class="nav-link active" id="custom-tabs-one-datos-profesional-tab" data-toggle="pill"
                                    href="#custom-tabs-one-datos-profesional" role="tab"
                                    aria-controls="custom-tabs-one-datos-profesional" aria-selected="false">Profesional</a>
                               

                            </li>
                             <li class="nav-item">
                                <a class="nav-link "id="custom-tabs-one-datos-del-editpaciente-tab" data-toggle="pill"
                                     href="#custom-tabs-one-datos-del-editpaciente" role="tab"
                                    aria-controls="custom-tabs-one-datos-del-editpaciente"  aria-selected="false" >Editar Paciente</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                              <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-one-datos-profesional" role="tabpanel" aria-labelledby="custom-tabs-one-datos-profesional-tab">
                            <div class="post">
                             



                                <div class="col-lg-12 col-md-12 col-xs-12 p-0">
                                    <label for="addpro" class="col-xs-4 control-label requerido">Profesional</label>
                                    <select name="addpro" id="addpro" class="form-control select2bs4" style="width: 100%;" required>
                                    </select>

                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-xs-12 p-0">
                                    <label for="addzona" class="col-xs-4 control-label requerido">Zona</label>
                                    <input name="addzona" id="addzona" class="form-control select2bs4" style="width: 100%;" readonly>
                                    

                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 p-0">
                                    <label for="barrio" class="col-xs-4 control-label requerido">Barrio</label>
                                    <select name="barrio" id="barrio" class="form-control select2bs4" style="width: 100%;" required>
                                        <option value='20 de Julio'>20 de Julio</option>
                                        <option value='3 de Julio'>3 de Julio</option>
                                        <option value='Aguablanca'>Aguablanca</option>
                                        <option value='Aguacatal'>Aguacatal</option>
                                        <option value='Aguacatal'>Aguacatal</option>
                                        <option value='Alameda'>Alameda</option>
                                        <option value='Alférez Real'>Alférez Real</option>
                                        <option value='Alfonso Barberena A.'>Alfonso Barberena A.</option>
                                        <option value='Alfonso Bonilla Aragón'>Alfonso Bonilla Aragón</option>
                                        <option value='Alfonso López P. 1a. Etapa'>Alfonso López P. 1a. Etapa</option>
                                        <option value='Alfonso López P. 2a. Etapa'>Alfonso López P. 2a. Etapa</option>
                                        <option value='Alfonso López P. 3a. Etapa'>Alfonso López P. 3a. Etapa</option>
                                        <option value='Alirio Mora Beltrán'>Alirio Mora Beltrán</option>
                                        <option value='Alto Aguacatal'>Alto Aguacatal</option>
                                        <option value='Alto de Los Mangos'>Alto de Los Mangos</option>
                                        <option value='Alto Nápoles '>Alto Nápoles </option>
                                        <option value='Altos de Menga'>Altos de Menga</option>
                                        <option value='Altos de Santa Isabel -  La Morelia'>Altos de Santa Isabel -  La Morelia</option>
                                        <option value='Andalucía (PP3)'>Andalucía (PP3)</option>
                                        <option value='Antonio Nariño'>Antonio Nariño</option>
                                        <option value='Aranjuez'>Aranjuez</option>
                                        <option value='Arboledas'>Arboledas</option>
                                        <option value='Asturias'>Asturias</option>
                                        <option value='Atanasio Girardot'>Atanasio Girardot</option>
                                        <option value='Atenas - Mameyal'>Atenas - Mameyal</option>
                                        <option value='Barrio Caldas'>Barrio Caldas</option>
                                        <option value='Barrio Departamental'>Barrio Departamental</option>
                                        <option value='Barrio Eucarístico'>Barrio Eucarístico</option>
                                        <option value='Barrio Obrero'>Barrio Obrero</option>
                                        <option value='Base Aérea'>Base Aérea</option>
                                        <option value='Belalcázar'>Belalcázar</option>
                                        <option value='Belén'>Belén</option>
                                        <option value='Belisario Caicedo'>Belisario Caicedo</option>
                                        <option value='Bellavista'>Bellavista</option>
                                        <option value='Bello Horizonte'>Bello Horizonte</option>
                                        <option value='Benjamín Herrera'>Benjamín Herrera</option>
                                        <option value='Bochalema'>Bochalema</option>
                                        <option value='Bolivariano'>Bolivariano</option>
                                        <option value='Bosques del Limonar'>Bosques del Limonar</option>
                                        <option value='Bretaña'>Bretaña</option>
                                        <option value='Brisas de los Alamos'>Brisas de los Alamos</option>
                                        <option value='Brisas de Mayo'>Brisas de Mayo</option>
                                        <option value='Brisas del Limonar'>Brisas del Limonar</option>
                                        <option value='Buenos Aires'>Buenos Aires</option>
                                        <option value='Cachipay'>Cachipay</option>
                                        <option value='Calima'>Calima</option>
                                        <option value='Calimio Desepaz'>Calimio Desepaz</option>
                                        <option value='Calipso'>Calipso</option>
                                        <option value='Camino Real - Joaquín Borrero Sinisterra'>Camino Real - Joaquín Borrero Sinisterra</option>
                                        <option value='Camino Real - Los Fundadores'>Camino Real - Los Fundadores</option>
                                        <option value='Campoalegre'>Campoalegre</option>
                                        <option value='Caney'>Caney</option>
                                        <option value='Cantaclaro'>Cantaclaro</option>
                                        <option value='Cañaveral'>Cañaveral</option>
                                        <option value='Cañaveralejo - Seguros Patria'>Cañaveralejo - Seguros Patria</option>
                                        <option value='Cañaverales - Los Samanes'>Cañaverales - Los Samanes</option>
                                        <option value='Carabineros'>Carabineros</option>
                                        <option value='Cascajal'>Cascajal</option>
                                        <option value='Cauca Viejo'>Cauca Viejo</option>
                                        <option value='Centenario'>Centenario</option>
                                        <option value='Champagñat'>Champagñat</option>
                                        <option value='Chapinero'>Chapinero</option>
                                        <option value='Charco Azul'>Charco Azul</option>
                                        <option value='Chiminangos Primera Etapa'>Chiminangos Primera Etapa</option>
                                        <option value='Chiminangos Segunda Etapa'>Chiminangos Segunda Etapa</option>
                                        <option value='Chipichape'>Chipichape</option>
                                        <option value='Ciudad 2000'>Ciudad 2000</option>
                                        <option value='Ciudad Campestre'>Ciudad Campestre</option>
                                        <option value='Ciudad Capri'>Ciudad Capri</option>
                                        <option value='Ciudad Cordoba'>Ciudad Cordoba</option>
                                        <option value='Ciudad Los Alamos'>Ciudad Los Alamos</option>
                                        <option value='Ciudad Melendez'>Ciudad Melendez</option>
                                        <option value='Ciudad Melendez 2'>Ciudad Melendez 2</option>
                                        <option value='Ciudad Talanga'>Ciudad Talanga</option>
                                        <option value='Ciudad Pacifica'>Ciudad Pacifica</option>
                                        <option value='Ciudad Universitaria'>Ciudad Universitaria</option>
                                        <option value='Ciudadela Comfandi'>Ciudadela Comfandi</option>
                                        <option value='Ciudadela del Río'>Ciudadela del Río</option>
                                        <option value='Ciudadela Floralia'>Ciudadela Floralia</option>
                                        <option value='Ciudadela Pasoancho'>Ciudadela Pasoancho</option>
                                        <option value='Club Campestre'>Club Campestre</option>
                                        <option value='Club Campestre'>Club Campestre</option>
                                        <option value='Colinas del Sur'>Colinas del Sur</option>
                                        <option value='Colseguros Andes'>Colseguros Andes</option>
                                        <option value='Compartir'>Compartir</option>
                                        <option value='Comuneros I'>Comuneros I</option>
                                        <option value='Cristóbal Colón'>Cristóbal Colón</option>
                                        <option value='Cuarteles Napoles'>Cuarteles Napoles</option>
                                        <option value='Cuarto de Legua - Guadalupe'>Cuarto de Legua - Guadalupe</option>
                                        <option value='Desepaz Invicali'>Desepaz Invicali</option>
                                        <option value='Doce de Octubre'>Doce de Octubre</option>
                                        <option value='Dos Quebradas'>Dos Quebradas</option>
                                        <option value='Eduardo Santos'>Eduardo Santos</option>
                                        <option value='El Banqueo'>El Banqueo</option>
                                        <option value='El Bosque'>El Bosque</option>
                                        <option value='El Cabuyal'>El Cabuyal</option>
                                        <option value='El Calvario'>El Calvario</option>
                                        <option value='El Capricho'>El Capricho</option>
                                        <option value='El Carmen'>El Carmen</option>
                                        <option value='El Carmen - San Bartolo - PP11'>El Carmen - San Bartolo - PP11</option>
                                        <option value='El Cedro'>El Cedro</option>
                                        <option value='El Cerezo'>El Cerezo</option>
                                        <option value='El Cortijo'>El Cortijo</option>
                                        <option value='El Diamante'>El Diamante</option>
                                        <option value='El Diamante'>El Diamante</option>
                                        <option value='El Dorado'>El Dorado</option>
                                        <option value='El Estero'>El Estero</option>
                                        <option value='El Faro'>El Faro</option>
                                        <option value='El Futuro'>El Futuro</option>
                                        <option value='El Gran Limonar'>El Gran Limonar</option>
                                        <option value='El Gran Limonar - Cataya'>El Gran Limonar - Cataya</option>
                                        <option value='El Guabal'>El Guabal</option>
                                        <option value='El Guayabal'>El Guayabal</option>
                                        <option value='El Hormiguero (Cabecera)'>El Hormiguero (Cabecera)</option>
                                        <option value='El Hoyo'>El Hoyo</option>
                                        <option value='El Ingenio'>El Ingenio</option>
                                        <option value='El Jardín'>El Jardín</option>
                                        <option value='El Jardín'>El Jardín</option>
                                        <option value='El Jordán'>El Jordán</option>
                                        <option value='El Lido'>El Lido</option>
                                        <option value='El Limonar'>El Limonar</option>
                                        <option value='El Marañon'>El Marañon</option>
                                        <option value='El Morichal'>El Morichal</option>
                                        <option value='El Mortiñal'>El Mortiñal</option>
                                        <option value='El Nacional'>El Nacional</option>
                                        <option value='El Otoño'>El Otoño</option>
                                        <option value='El Pajuil'>El Pajuil</option>
                                        <option value='El Palomar'>El Palomar</option>
                                        <option value='El Paraiso'>El Paraiso</option>
                                        <option value='El Pato'>El Pato</option>
                                        <option value='El Peñón'>El Peñón</option>
                                        <option value='El Peón'>El Peón</option>
                                        <option value='El Piloto'>El Piloto</option>
                                        <option value='El Pinar'>El Pinar</option>
                                        <option value='El Poblado I'>El Poblado I</option>
                                        <option value='El Poblado II'>El Poblado II</option>
                                        <option value='El Pondaje'>El Pondaje</option>
                                        <option value='El Porvenir'>El Porvenir</option>
                                        <option value='El Prado'>El Prado</option>
                                        <option value='El Recuerdo'>El Recuerdo</option>
                                        <option value='El Refugio'>El Refugio</option>
                                        <option value='El Remanso'>El Remanso</option>
                                        <option value='El Retiro'>El Retiro</option>
                                        <option value='El Rodeo'>El Rodeo</option>
                                        <option value='El Rosario'>El Rosario</option>
                                        <option value='El Saladito (Cabecera)'>El Saladito (Cabecera)</option>
                                        <option value='El Sena'>El Sena</option>
                                        <option value='EL Trébol'>EL Trébol</option>
                                        <option value='El Troncal'>El Troncal</option>
                                        <option value='El Vallado'>El Vallado</option>
                                        <option value='El Verdal - Gonchelandia'>El Verdal - Gonchelandia</option>
                                        <option value='El Vergel'>El Vergel</option>
                                        <option value='Evaristo García'>Evaristo García</option>
                                        <option value='Fátima'>Fátima</option>
                                        <option value='Felidia (Cabecera)'>Felidia (Cabecera)</option>
                                        <option value='Fenalco Kennedy'>Fenalco Kennedy</option>
                                        <option value='Fepicol'>Fepicol</option>
                                        <option value='Flora Industrial'>Flora Industrial</option>
                                        <option value='Fonaviemcali'>Fonaviemcali</option>
                                        <option value='Francisco Eladio Ramirez'>Francisco Eladio Ramirez</option>
                                        <option value='Galeras'>Galeras</option>
                                        <option value='Golondrinas (Cabecera)'>Golondrinas (Cabecera)</option>
                                        <option value='Granada'>Granada</option>
                                        <option value='Guayaquil'>Guayaquil</option>
                                        <option value='Guillermo Valencia'>Guillermo Valencia</option>
                                        <option value='Horizontes'>Horizontes</option>
                                        <option value='Ignacio Rengifo'>Ignacio Rengifo</option>
                                        <option value='Industria de Licores'>Industria de Licores</option>
                                        <option value='Industrial'>Industrial</option>
                                        <option value='Jarillón Navarro'>Jarillón Navarro</option>
                                        <option value='Jarillon Río Cauca I '>Jarillon Río Cauca I </option>
                                        <option value='Jarillon Río Cauca II'>Jarillon Río Cauca II</option>
                                        <option value='Jarillon Río Cauca III'>Jarillon Río Cauca III</option>
                                        <option value='Jorge Eliecer Gaitán'>Jorge Eliecer Gaitán</option>
                                        <option value='Jorge Isaacs'>Jorge Isaacs</option>
                                        <option value='Jorge Zawadsky'>Jorge Zawadsky</option>
                                        <option value='José Holguín Garcés'>José Holguín Garcés</option>
                                        <option value='José Manuel Marroquín Primera Etapa'>José Manuel Marroquín Primera Etapa</option>
                                        <option value='José Manuel Marroquín Segunda Etapa'>José Manuel Marroquín Segunda Etapa</option>
                                        <option value='José María Cordoba'>José María Cordoba</option>
                                        <option value='Juanambú'>Juanambú</option>
                                        <option value='Julio Rincón'>Julio Rincón</option>
                                        <option value='Junín'>Junín</option>
                                        <option value='La Alborada'>La Alborada</option>
                                        <option value='La Alianza'>La Alianza</option>
                                        <option value='La Base'>La Base</option>
                                        <option value='La Buitrera (Cabecera)'>La Buitrera (Cabecera)</option>
                                        <option value='La Cajita'>La Cajita</option>
                                        <option value='La Campiña'>La Campiña</option>
                                        <option value='La Candelaria'>La Candelaria</option>
                                        <option value='La Carolina - Los Andes Bajo'>La Carolina - Los Andes Bajo</option>
                                        <option value='La Cascada'>La Cascada</option>
                                        <option value='La Castilla (Cabecera)'>La Castilla (Cabecera)</option>
                                        <option value='La Elvira (Cabecera)'>La Elvira (Cabecera)</option>
                                        <option value='La Esmeralda'>La Esmeralda</option>
                                        <option value='La Esperanza'>La Esperanza</option>
                                        <option value='La Esperanza'>La Esperanza</option>
                                        <option value='La Flora'>La Flora</option>
                                        <option value='La Floresta'>La Floresta</option>
                                        <option value='La Fonda'>La Fonda</option>
                                        <option value='La Fortaleza'>La Fortaleza</option>
                                        <option value='La Gran Colombia'>La Gran Colombia</option>
                                        <option value='La Hacienda'>La Hacienda</option>
                                        <option value='La Independencia'>La Independencia</option>
                                        <option value='La Isla'>La Isla</option>
                                        <option value='La Leonera (Cabecera)'>La Leonera (Cabecera)</option>
                                        <option value='La Libertad'>La Libertad</option>
                                        <option value='La Luisa'>La Luisa</option>
                                        <option value='La María'>La María</option>
                                        <option value='La María'>La María</option>
                                        <option value='La Merced'>La Merced</option>
                                        <option value='La Paila'>La Paila</option>
                                        <option value='La Paz'>La Paz</option>
                                        <option value='La Paz (Cabecera)'>La Paz (Cabecera)</option>
                                        <option value='La Playa'>La Playa</option>
                                        <option value='La Reforma - El Mango'>La Reforma - El Mango</option>
                                        <option value='La Rivera Primera Etapa'>La Rivera Primera Etapa</option>
                                        <option value='La Riverita'>La Riverita</option>
                                        <option value='La Selva'>La Selva</option>
                                        <option value='La Sirena'>La Sirena</option>
                                        <option value='La Sirena '>La Sirena </option>
                                        <option value='La Sultana'>La Sultana</option>
                                        <option value='La Vorágine'>La Vorágine</option>
                                        <option value='Las Acacias'>Las Acacias</option>
                                        <option value='Las Américas'>Las Américas</option>
                                        <option value='Las Brisas'>Las Brisas</option>
                                        <option value='Las Ceibas'>Las Ceibas</option>
                                        <option value='Las Delicias'>Las Delicias</option>
                                        <option value='Las Granjas'>Las Granjas</option>
                                        <option value='Las Nieves'>Las Nieves</option>
                                        <option value='Las Nieves'>Las Nieves</option>
                                        <option value='Las Orquídeas'>Las Orquídeas</option>
                                        <option value='Las Palmas'>Las Palmas</option>
                                        <option value='Las Quintas de Don Simón'>Las Quintas de Don Simón</option>
                                        <option value='Las Vegas'>Las Vegas</option>
                                        <option value='Las Vegas del Lili'>Las Vegas del Lili</option>
                                        <option value='Laureano Gómez'>Laureano Gómez</option>
                                        <option value='León XIII'>León XIII</option>
                                        <option value='Lili'>Lili</option>
                                        <option value='Lituania - Dalandia'>Lituania - Dalandia</option>
                                        <option value='Lleras Camargo'>Lleras Camargo</option>
                                        <option value='Lleras Restrepo'>Lleras Restrepo</option>
                                        <option value='Lleras Restrepo II Etapa'>Lleras Restrepo II Etapa</option>
                                        <option value='Loma de La Cajita'>Loma de La Cajita</option>
                                        <option value='Lomitas'>Lomitas</option>
                                        <option value='Los Alcázares'>Los Alcázares</option>
                                        <option value='Los Andes'>Los Andes</option>
                                        <option value='Los Andes (Cabecera)'>Los Andes (Cabecera)</option>
                                        <option value='Los Andes B - La Riviera'>Los Andes B - La Riviera</option>
                                        <option value='Los Cambulos'>Los Cambulos</option>
                                        <option value='Los Cárpatos'>Los Cárpatos</option>
                                        <option value='Los Chorros'>Los Chorros</option>
                                        <option value='Los Comuneros Segunda Etapa'>Los Comuneros Segunda Etapa</option>
                                        <option value='Los Conquistadores'>Los Conquistadores</option>
                                        <option value='Los Farallones'>Los Farallones</option>
                                        <option value='Los Guaduales'>Los Guaduales</option>
                                        <option value='Los Guayacanes'>Los Guayacanes</option>
                                        <option value='Los Lagos'>Los Lagos</option>
                                        <option value='Los Laureles'>Los Laureles</option>
                                        <option value='Los Libertadores'>Los Libertadores</option>
                                        <option value='Los Líderes'>Los Líderes</option>
                                        <option value='Los Limones'>Los Limones</option>
                                        <option value='Los Naranjos I'>Los Naranjos I</option>
                                        <option value='Los Naranjos II'>Los Naranjos II</option>
                                        <option value='Los Parques - Barranquilla'>Los Parques - Barranquilla</option>
                                        <option value='Los Pinos'>Los Pinos</option>
                                        <option value='Los Portales - Nuevo Rey'>Los Portales - Nuevo Rey</option>
                                        <option value='Los Robles'>Los Robles</option>
                                        <option value='Los Sauces'>Los Sauces</option>
                                        <option value='Lourdes'>Lourdes</option>
                                        <option value='Manuel María Buenaventura'>Manuel María Buenaventura</option>
                                        <option value='Manuela Beltrán'>Manuela Beltrán</option>
                                        <option value='Manzanares'>Manzanares</option>
                                        <option value='Maracaibo'>Maracaibo</option>
                                        <option value='Marañon Bajo'>Marañon Bajo</option>
                                        <option value='Marco Fidel Suárez'>Marco Fidel Suárez</option>
                                        <option value='Mariano Ramos'>Mariano Ramos</option>
                                        <option value='Mario Correa Rengifo'>Mario Correa Rengifo</option>
                                        <option value='Marroquín III'>Marroquín III</option>
                                        <option value='Mayapan - Las Vegas'>Mayapan - Las Vegas</option>
                                        <option value='Meléndez'>Meléndez</option>
                                        <option value='Menga'>Menga</option>
                                        <option value='Metropolitano del Norte'>Metropolitano del Norte</option>
                                        <option value='Miraflores'>Miraflores</option>
                                        <option value='Mojica'>Mojica</option>
                                        <option value='Mónaco'>Mónaco</option>
                                        <option value='Montañuelas'>Montañuelas</option>
                                        <option value='Montebello (Cabecera)'>Montebello (Cabecera)</option>
                                        <option value='Morgan'>Morgan</option>
                                        <option value='Municipal'>Municipal</option>
                                        <option value='Nápoles'>Nápoles</option>
                                        <option value='Navarro - La Chanca'>Navarro - La Chanca</option>
                                        <option value='Navarro (Cabecera)'>Navarro (Cabecera)</option>
                                        <option value='Normandía'>Normandía</option>
                                        <option value='Nueva Floresta'>Nueva Floresta</option>
                                        <option value='Nueva Tequendama '>Nueva Tequendama </option>
                                        <option value='Olaya Herrera'>Olaya Herrera</option>
                                        <option value='Olímpico'>Olímpico</option>
                                        <option value='Omar Torrijos'>Omar Torrijos</option>
                                        <option value='Pampa Linda'>Pampa Linda</option>
                                        <option value='Panamericano'>Panamericano</option>
                                        <option value='Pance (Cabecera)'>Pance (Cabecera)</option>
                                        <option value='Pance Suburbano (La Viga)'>Pance Suburbano (La Viga)</option>
                                        <option value='Parcelaciones Pance'>Parcelaciones Pance</option>
                                        <option value='Parque de La Bandera'>Parque de La Bandera</option>
                                        <option value='Parque de la Caña'>Parque de la Caña</option>
                                        <option value='Paseo de Los Almendros'>Paseo de Los Almendros</option>
                                        <option value='Paso del Comercio'>Paso del Comercio</option>
                                        <option value='Pasoancho'>Pasoancho</option>
                                        <option value='Peñas Blancas'>Peñas Blancas</option>
                                        <option value='Petecuy Primera Etapa'>Petecuy Primera Etapa</option>
                                        <option value='Petecuy Segunda Etapa'>Petecuy Segunda Etapa</option>
                                        <option value='Petecuy Tercera Etapa'>Petecuy Tercera Etapa</option>
                                        <option value='Pichindé (Cabecera)'>Pichindé (Cabecera)</option>
                                        <option value='Pico de Aguila'>Pico de Aguila</option>
                                        <option value='Piedrachiquita'>Piedrachiquita</option>
                                        <option value='Pízamos I'>Pízamos I</option>
                                        <option value='Pízamos II'>Pízamos II</option>
                                        <option value='Pízamos III - Las Dalias'>Pízamos III - Las Dalias</option>
                                        <option value='Planta de Tratamiento'>Planta de Tratamiento</option>
                                        <option value='Polvorines'>Polvorines</option>
                                        <option value='Popular'>Popular</option>
                                        <option value='Porvenir'>Porvenir</option>
                                        <option value='Potrero Grande'>Potrero Grande</option>
                                        <option value='PP1'>PP1</option>
                                        <option value='PP10'>PP10</option>
                                        <option value='PP2'>PP2</option>
                                        <option value='PP4'>PP4</option>
                                        <option value='PP5'>PP5</option>
                                        <option value='PP6'>PP6</option>
                                        <option value='PP8'>PP8</option>
                                        <option value='PP9'>PP9</option>
                                        <option value='Prados de Oriente'>Prados de Oriente</option>
                                        <option value='Prados del Limonar'>Prados del Limonar</option>
                                        <option value='Prados del Norte'>Prados del Norte</option>
                                        <option value='Prados del Sur'>Prados del Sur</option>
                                        <option value='Primavera'>Primavera</option>
                                        <option value='Primero de Mayo'>Primero de Mayo</option>
                                        <option value='Primitivo Crespo'>Primitivo Crespo</option>
                                        <option value='Promociones Populares B'>Promociones Populares B</option>
                                        <option value='Pueblo Joven'>Pueblo Joven</option>
                                        <option value='Pueblo Nuevo'>Pueblo Nuevo</option>
                                        <option value='Puerta del Sol'>Puerta del Sol</option>
                                        <option value='Puerto Mallarino'>Puerto Mallarino</option>
                                        <option value='Puerto Nuevo'>Puerto Nuevo</option>
                                        <option value='Quebrada Honda'>Quebrada Honda</option>
                                        <option value='República de Israel'>República de Israel</option>
                                        <option value='Ricardo Balcázar'>Ricardo Balcázar</option>
                                        <option value='Rodrigo Lara Bonilla'>Rodrigo Lara Bonilla</option>
                                        <option value='Saavedra Galindo'>Saavedra Galindo</option>
                                        <option value='Salomia'>Salomia</option>
                                        <option value='San Antonio'>San Antonio</option>
                                        <option value='San Antonio'>San Antonio</option>
                                        <option value='San Benito'>San Benito</option>
                                        <option value='San Carlos'>San Carlos</option>
                                        <option value='San Cayetano'>San Cayetano</option>
                                        <option value='San Cristobal'>San Cristobal</option>
                                        <option value='San Fernando Nuevo'>San Fernando Nuevo</option>
                                        <option value='San Fernando Viejo'>San Fernando Viejo</option>
                                        <option value='San Francisco'>San Francisco</option>
                                        <option value='San Juan Bosco'>San Juan Bosco</option>
                                        <option value='San Judas Tadeo I Etapa'>San Judas Tadeo I Etapa</option>
                                        <option value='San Judas Tadeo II Etapa'>San Judas Tadeo II Etapa</option>
                                        <option value='San Luís'>San Luís</option>
                                        <option value='San Luís II'>San Luís II</option>
                                        <option value='San Marino'>San Marino</option>
                                        <option value='San Miguel'>San Miguel</option>
                                        <option value='San Nicolas'>San Nicolas</option>
                                        <option value='San Pablo'>San Pablo</option>
                                        <option value='San Pascual'>San Pascual</option>
                                        <option value='San Pedro'>San Pedro</option>
                                        <option value='San Pedro Claver'>San Pedro Claver</option>
                                        <option value='San Vicente'>San Vicente</option>
                                        <option value='Santa Anita - La Selva'>Santa Anita - La Selva</option>
                                        <option value='Santa Barbara'>Santa Barbara</option>
                                        <option value='Santa Elena'>Santa Elena</option>
                                        <option value='Santa Fe'>Santa Fe</option>
                                        <option value='Santa Helena'>Santa Helena</option>
                                        <option value='Santa Isabel'>Santa Isabel</option>
                                        <option value='Santa Mónica '>Santa Mónica </option>
                                        <option value='Santa Mónica Belalcázar'>Santa Mónica Belalcázar</option>
                                        <option value='Santa Mónica Popular'>Santa Mónica Popular</option>
                                        <option value='Santa Rita'>Santa Rita</option>
                                        <option value='Santa Rosa'>Santa Rosa</option>
                                        <option value='Santa Teresita'>Santa Teresita</option>
                                        <option value='Santander'>Santander</option>
                                        <option value='Santo Domingo'>Santo Domingo</option>
                                        <option value='Sector Alto de Los Chorros'>Sector Alto de Los Chorros</option>
                                        <option value='Sector Alto Jordán'>Sector Alto Jordán</option>
                                        <option value='Sector Altos Normandía'>Sector Altos Normandía</option>
                                        <option value='Sector Asprosocial-Diamante'>Sector Asprosocial-Diamante</option>
                                        <option value='Sector Cañaveralejo Guadalupe'>Sector Cañaveralejo Guadalupe</option>
                                        <option value='Sector Laguna del Pondaje'>Sector Laguna del Pondaje</option>
                                        <option value='Sector Meléndez'>Sector Meléndez</option>
                                        <option value='Sector Patio Bonito'>Sector Patio Bonito</option>
                                        <option value='Sector Puente del Comercio'>Sector Puente del Comercio</option>
                                        <option value='Sector Tres Cruces'>Sector Tres Cruces</option>
                                        <option value='Senderos de la Flora'>Senderos de la Flora</option>
                                        <option value='Siete de Agosto'>Siete de Agosto</option>
                                        <option value='Siloé'>Siloé</option>
                                        <option value='Simón Bolívar'>Simón Bolívar</option>
                                        <option value='Sindical'>Sindical</option>
                                        <option value='Sucre'>Sucre</option>
                                        <option value='Sultana - Berlín - San Francisco '>Sultana - Berlín - San Francisco </option>
                                        <option value='Tejares - Cristales'>Tejares - Cristales</option>
                                        <option value='Terrón Colorado'>Terrón Colorado</option>
                                        <option value='Tierra Blanca'>Tierra Blanca</option>
                                        <option value='Torres de Comfandi'>Torres de Comfandi</option>
                                        <option value='U.D. Alberto Galindo -  Plaza de Toros'>U.D. Alberto Galindo -  Plaza de Toros</option>
                                        <option value='Ulpiano Lloreda'>Ulpiano Lloreda</option>
                                        <option value='Unicentro Cali'>Unicentro Cali</option>
                                        <option value='Unidad Residencial Bueno Madrid'>Unidad Residencial Bueno Madrid</option>
                                        <option value='Unidad Residencial El Coliseo'>Unidad Residencial El Coliseo</option>
                                        <option value='Unidad Residencial Santiago de Cali'>Unidad Residencial Santiago de Cali</option>
                                        <option value='Unión de Vivienda Popular'>Unión de Vivienda Popular</option>
                                        <option value='Urbanización Boyacá'>Urbanización Boyacá</option>
                                        <option value='Urbanización Calimio'>Urbanización Calimio</option>
                                        <option value='Urbanización Ciudad Jardín'>Urbanización Ciudad Jardín</option>
                                        <option value='Urbanización Colseguros'>Urbanización Colseguros</option>
                                        <option value='Urbanización El Angel del Hogar'>Urbanización El Angel del Hogar</option>
                                        <option value='Urbanización La Flora'>Urbanización La Flora</option>
                                        <option value='Urbanización La Merced'>Urbanización La Merced</option>
                                        <option value='Urbanización La Nueva Base'>Urbanización La Nueva Base</option>
                                        <option value='Urbanización Militar'>Urbanización Militar</option>
                                        <option value='Urbanización Nueva Granada'>Urbanización Nueva Granada</option>
                                        <option value='Urbanización Río Lili'>Urbanización Río Lili</option>
                                        <option value='Urbanización San Joaquin'>Urbanización San Joaquin</option>
                                        <option value='Urbanización Tequendama'>Urbanización Tequendama</option>
                                        <option value='Uribe Uribe'>Uribe Uribe</option>
                                        <option value='Valle Grande'>Valle Grande</option>
                                        <option value='Vendimia'>Vendimia</option>
                                        <option value='Venezuela - Urbanización Cañaveralejo'>Venezuela - Urbanización Cañaveralejo</option>
                                        <option value='Versalles'>Versalles</option>
                                        <option value='Villa Colombia'>Villa Colombia</option>
                                        <option value='Villa del Lago'>Villa del Lago</option>
                                        <option value='Villa del Prado - El Guabito'>Villa del Prado - El Guabito</option>
                                        <option value='Villa del Rosario'>Villa del Rosario</option>
                                        <option value='Villa del Sol'>Villa del Sol</option>
                                        <option value='Villa del Sur'>Villa del Sur</option>
                                        <option value='Villablanca'>Villablanca</option>
                                        <option value='Villacarmelo (Cabecera)'>Villacarmelo (Cabecera)</option>
                                        <option value='Villamercedes I - Villa Luz - Las Garzas'>Villamercedes I - Villa Luz - Las Garzas</option>
                                        <option value='Villanueva'>Villanueva</option>
                                        <option value='Vipasa'>Vipasa</option>
                                        <option value='Vista Hermosa'>Vista Hermosa</option>
                                        <option value='Yira Castro'>Yira Castro</option>
                                        <option value='Zona de reserva agrícola'>Zona de reserva agrícola</option>
                                        <option value='Zona de reserva agrícola'>Zona de reserva agrícola</option>
                                        <option value='Zonaméríca'>Zonaméríca</option>
                                        <option value='Candelaria'>Candelaria</option>
                                        <option value='Ciudad del campo'>Ciudad del campo</option>
                                        <option value='Yumbo'>Yumbo</option>
                                        <option value='Buenaventura'>Buenaventura</option>
                                        <option value='Jamundi'>Jamundi</option>
                                        <option value='Palmira'>Palmira</option>
                                        <option value='Villagorgona'>Villagorgona</option>
                                        <option value='Poblado campestre'>Poblado campestre</option>

                                    </select>

                                </div>

                            <div class="card-footer p-2">
                                <span class="float-right"><div class="row">
                                     <div class="col-xs-3">
                                      <input type ="submit" name="action_pro" id="action_pro" class="updatepro btn btn-success" value="Add"/>
                                      <input type ="hidden" name="action_prou" id="action_prou" class="btn btn-success" value="Add"/>
                                    </div>
                                    </div>
                                    </span>
                            </div>

                            </div>

                            </div>

                        
                       
                        <div class="tab-pane fade" id="custom-tabs-one-datos-del-editpaciente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-editpaciente-tab">
                            <div class="card-body accent-blue">
                                
                                     <div class="card-body">
                        <form  id="form-generalpaciente" class="form-horizontal" method="POST">
                            @csrf
                            @include('paliativos.form.formPacienteEdit')
                            <input type ="submit" name="action_paciente" id="action_paciente" class="updatepaciente btn btn-success" value="Add"/>
                            <input type ="hidden" name="action_pacienteu" id="action_pacienteu" class="btn btn-success" value="Add"/>
                            
                        </form>
                        </div>
                    
                         

                            </div>


                        </div>
                       </div>
                    
                     





                    </div>


                </div>
            </div>
        </div>
    </div>
