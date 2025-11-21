<!-- 
<td>
<a href="/observaciones?
documento={{ $dato->numdocum }}&
apertura={{ $dato->tipo_historia }}&
cuestionario={{ $dato->cuestionario }}&
respuesta={{ $dato->respuesta }}&
profesional={{ $dato->codigo_profesional }}&
apellido1={{ $dato->APELLIDO1 }}&
apellido2={{ $dato->APELLIDO2 }}&
nombre1={{ $dato->NOMBRE1 }}&
nombre2={{ $dato->NOMBRE2 }}&
eps={{ $dato->Entidad_salud }}&
telefono={{ $dato->Telefono }}&
pertinencia={{ $dato->Pertinencia }}&
medicamentos={{ $dato->Medicamentos }}&
nombremedicamento={{ $dato->nombreMedicamento }}&
observaciones={{ $dato->Observaciones }}"

class="btn-float bg-gradient-warning btn-sm tooltipsC" 
title="Ver Observaciones">

<i class="fa fa-fw fa-eye"></i>
</a>
</td> -->

























<!-- <div class="card-body with-border">
      <div class="card-body table-responsive p-2">

      <table id="informepsicologica" class="table table-hover table-sm table-condensed text-nowrap table-striped">

        <thead>
        <tr>
            
        <th>Acciones</th>
              <th>N° Documento</th>
              <th>Tipo HC</th>
              <th>Cuestionario</th>
              <th>Respuesta</th>
              <th>Profesional</th>
              <th>Primer apellido</th>
              <th>Segundo apellido</th>
              <th>Primer nombre</th>
              <th>Segundo nombre</th>
              <th>EPS</th>
              <th>Telefono</th>
              <th>Pertinencia</th>
              <th>Medicamentos</th>
              <th>Nombre Medicamentos</th>
              <th>Observaciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($datos as $dato)
                        
        <td>
  <a href="/observaciones?
    documento={{ $dato->numdocum }}&
    apertura={{ $dato->tipo_historia }}&
    cuestionario={{ $dato->cuestionario }}&
    respuesta={{ $dato->respuesta }}&
    profesional={{ $dato->codigo_profesional }}&
    apellido1={{ $dato->APELLIDO1 }}&
    apellido2={{ $dato->APELLIDO2 }}&
    nombre1={{ $dato->NOMBRE1 }}&
    nombre2={{ $dato->NOMBRE2 }}&
    eps={{ $dato->Entidad_salud }}&
    telefono={{ $dato->Telefono }}&
    pertinencia={{ $dato->Pertinencia }}&
    medicamentos={{ $dato->Medicamentos }}&
    nombremedicamento={{ $dato->nombreMedicamento }}&
    observaciones={{ $dato->Observaciones }}"
    
    class="btn-float bg-gradient-warning btn-sm tooltipsC" 
    title="Ver Observaciones">

    <i class="fa fa-fw fa-eye"></i>

  </a>
</td>

                        
                                                       
                         <td>
                          <button type="button" id="{{$dato->numdocum ?? ''}}" data-observaciones="{{ $dato->Acciones ?? ''}}"
                            data-documento="{{ $dato->numdocum ?? '' }}"
                            data-apertura="{{ $dato->tipo_historia ?? '' }}"
                            data-cuestionario="{{ $dato->cuestionario ?? '' }}"
                            data-respuesta="{{ $dato->respuesta ?? '' }}"
                            data-profesional="{{ $dato->codigo_profesional ?? '' }}"
                            data-apellido="{{ $dato->APELLIDO1 ?? '' }}"
                            data-apellid="{{ $dato->APELLIDO2 ?? '' }}"
                            data-nombre="{{ $dato->NOMBRE1 ?? '' }}"
                            data-nombr="{{ $dato->NOMBRE2 ?? '' }}"
                            data-eps="{{ $dato->Entidad_salud ?? '' }}"
                            data-tel="{{ $dato->Telefono ?? '' }}"
                            data-pertinencia="{{ $dato->Pertinencia ?? '' }}"
                            data-medicamentos="{{ $dato->Medicamentos ?? '' }}"
                            data-nombreMedicamento="{{ $dato->nombreMedicamento ?? '' }}"
                            data-observaciones="{{ $dato->Observaciones ?? ''  }}"
                           name="Editar" title="observaciones"  class = "observaciones btn-float  bg-gradient-warning btn-sm tooltipsC"><i class="fa fa-fw fa-plus-circle"></i></i></a>
                          </button>

                            <button type="button" id="{{$dato->numdocum ?? ''}}" data-observaciones="{{ $dato->ID_EVOLUCION ?? '' }}"
                                    
                           class="ver-observaciones btn-float bg-gradient-info btn-sm tooltipsC" title="Ver Observaciones">
                                    <i class="fa fa-fw fa-eye"></i>
                                </button>
                           </td> 

                         @extends('layouts.app')  O el layout que uses

                        @section('content')

                        <h3>Observaciones del Paciente</h3>

                        <table class="table table-bordered">
                            <tr><th>Documento</th><td>{{ request()->get('documento') }}</td></tr>
                            <tr><th>Tipo HC</th><td>{{ request()->get('apertura') }}</td></tr>
                            <tr><th>Cuestionario</th><td>{{ request()->get('cuestionario') }}</td></tr>
                            <tr><th>Respuesta</th><td>{{ request()->get('respuesta') }}</td></tr>
                            <tr><th>Profesional</th><td>{{ request()->get('profesional') }}</td></tr>
                            <tr><th>Apellidos</th><td>{{ request()->get('apellido1') }} {{ request()->get('apellido2') }}</td></tr>
                            <tr><th>Nombres</th><td>{{ request()->get('nombre1') }} {{ request()->get('nombre2') }}</td></tr>
                            <tr><th>EPS</th><td>{{ request()->get('eps') }}</td></tr>
                            <tr><th>Teléfono</th><td>{{ request()->get('telefono') }}</td></tr>
                            <tr><th>Pertinencia</th><td>{{ request()->get('pertinencia') }}</td></tr>
                            <tr><th>Medicamentos</th><td>{{ request()->get('medicamentos') }}</td></tr>
                            <tr><th>Nombre Medicamento</th><td>{{ request()->get('nombremedicamento') }}</td></tr>
                            <tr><th>Observaciones</th><td>{{ request()->get('observaciones') }}</td></tr>
                            <td>
                            <a href="/observaciones?documento={{ $dato->numdocum }}&apertura={{ $dato->tipo_historia }}&cuestionario={{ $dato->cuestionario }}&respuesta={{ $dato->respuesta }}&profesional={{ $dato->codigo_profesional }}&apellido1={{ $dato->APELLIDO1 }}&apellido2={{ $dato->APELLIDO2 }}&nombre1={{ $dato->NOMBRE1 }}&nombre2={{ $dato->NOMBRE2 }}&eps={{ $dato->Entidad_salud }}&telefono={{ $dato->Telefono }}&pertinencia={{ $dato->Pertinencia }}&medicamentos={{ $dato->Medicamentos }}&nombremedicamento={{ $dato->nombreMedicamento }}&observaciones={{ $dato->Observaciones }}"

                            class="btn-float bg-gradient-warning btn-sm tooltipsC" 
                            title="Ver Observaciones">

                            <i class="fa fa-fw fa-eye"></i>

                            </a>
                        </td>
                        </table>

                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>

                        @endsection

                        
                        

                         <td>{{$dato->Acciones ?? ''}}</td>
                        <td> {{$dato->numdocum ?? ''}}</td>
                        <td> {{$dato->tipo_historia ?? ''}}</td>
                        <td> {{$dato->cuestionario ?? ''}}</td>
                        <td> {{$dato->respuesta ?? ''}}</td>
                        <td> {{$dato->codigo_profesional ?? ''}}</td>
                        <td> {{$dato->APELLIDO1 ?? ''}}</td>
                        <td> {{$dato->APELLIDO2 ?? ''}}</td>
                        <td> {{$dato->NOMBRE1 ?? ''}}</td>
                        <td> {{$dato->NOMBRE2 ?? ''}}</td>
                        <td> {{$dato->Entidad_salud ?? ''}}</td> 
                        <td> {{$dato->Telefono ?? ''}}</td> 
                        <td> {{$dato->Pertinencia ?? ''}}</td>
                        <td> {{$dato->Medicamentos ?? ''}}</td> 
                        <td> {{$dato->nombreMedicamento ?? ''}}</td> 
                        <td> {{$dato->observaciones ?? ''}} </td>                                          
                    </tr>
                   
      @endforeach
        </tbody>
      </table>
    </div>

</div>
  -->
