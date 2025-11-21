<div class="container mt-4">
    <h4 class="mb-3">Buscar Paciente</h4>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="buscarDocumento" class="form-label">N° Documento</label>
            <input type="text" id="buscarDocumento" class="form-control" placeholder="Ingrese número de documento">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" id="btnBuscar" class="btn btn-primary">Buscar</button>
        </div>
    </div>
</div>




<form action="#" method="POST">
    @csrf 

<div class="container mt-4">
    <h3 class="mb-4">Datos del Paciente</h3>

    <!-- Primera fila -->
    
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="documento" class="form-label" style="color: #000000;">N° Documento</label>
            <input type="text" style="color: #000000;" name="documento" id="documento" class="form-control UpperCase" value="{{old('documento')}}" readonly>
        </div>

        <div class="col-md-3">
            <label for="apellido" class="form-label" style="color: #000000;">1er Apellido</label>
            <input type="text" style="color: #000000;" name="apellido" id="apellido" class="form-control UpperCase" value="{{old('apellido')}}" readonly>
        </div>
      
        <div class="col-md-3">
            <label for="nombre" class="form-label" style="color: #000000;">1er Nombre</label>
            <input type="text" style="color: #000000;" name="nombre" id="nombre" class="form-control UpperCase" value="{{old('nombre')}}" readonly>
        </div>
       
      
    </div>

    <!-- Segunda fila -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="cuestionario" class="form-label" style="color: #000000;">Cuestionario</label>
            <input type="text" style="color: #000000;" name="cuestionario" id="cuestionario" class="form-control UpperCase" value="{{old('cuestionario')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="respuesta" class="form-label" style="color: #000000;">Respuesta</label>
            <input type="text" style="color: #000000;" name="respuesta" id="respuesta" class="form-control UpperCase" value="{{old('respuesta')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="profesional" class="form-label" style="color: #000000;">Profesional</label>
            <input type="text" style="color: #000000;" name="profesional" id="profesional" class="form-control UpperCase" value="{{old('profesional')}}" readonly>
        </div>
    </div>

    <!-- Tercera fila -->
    <div class="row mb-3">
          <div class="col-md-3">
            <label for="fecha" class="form-label" style="color: #000000;">FechaApertura</label>
            <input type="text" style="color: #000000;" name="fecha" id="fecha" class="form-control UpperCase" value="{{old('fecha')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="historia" class="form-label" style="color: #000000;">FechaEvolución</label>
            <input type="text" style="color: #000000;" name="historia" id="historia" class="form-control UpperCase" value="{{old('historia')}}" readonly>
        </div>

         <div class="col-md-3">
            <label for="eps" class="form-label" style="color: #000000;">EPS</label>
            <input type="text" style="color: #000000;" name="eps" id="eps" class="form-control UpperCase" value="{{old('eps')}}" readonly>
        </div>
      
    </div>

    <!-- Cuarta fila -->
    <div class="row mb-4">
       
        <div class="col-md-3">
            <label for="tel" class="form-label" style="color: #000000;">Teléfono</label>
            <input type="text" style="color: #000000;" name="tel" id="tel" class="form-control UpperCase" value="{{old('tel')}}" readonly>
        </div>
            <div class="col-md-3">
                <label for="pertinencia" class="form-label" style="color: #000000;">Pertinencia</label>
                <input type="text" style="color: #000000;" name="pertinencia" id="pertinencia" class="form-control UpperCase" value="{{old('eps')}}" readonly>
            </div>

        <div class="col-md-3">
            <label for="medicamentos" class="form-label" style="color: #000000;">Medicamentos</label>
            <input type="text" style="color: #000000;" name="medicamentos" id="medicamentos" class="form-control UpperCase" value="{{old('eps')}}" readonly>
        </div>

        <div class="col-md-3">
            <label for="nombreMedicamento" class="form-label" style="color: #000000;">Nombre</label>
            <input type="text" style="color: #000000;" name="nombreMedicamento" id="nombreMedicamento" class="form-control UpperCase" value="{{old('eps')}}" readonly>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <label for="observacion" class="form-label fw-bold">Observación</label>
            <textarea name="observacion" id="observacion" class="form-control" rows="5" readonly></textarea>
        </div>
    </div>


</div>



    
   
   
</div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#btnBuscar').click(function () {
        const doc = $('#buscarDocumento').val();

        if (!doc) {
            alert('Por favor, ingresa un número de documento');
            return;
        }

        $.ajax({
            url: '/buscar-paciente/' + doc,
            method: 'GET',
            success: function (data) {
                $('#documento').val(data.documento);
                $('#apellido').val(data.apellido);
                $('#nombre').val(data.nombre);
                $('#cuestionario').val(data.cuestionario);
                $('#respuesta').val(data.respuesta);
                $('#profesional').val(data.profesional);
                $('#fecha').val(data.fecha);
                $('#historia').val(data.historia);
                $('#eps').val(data.eps);
                $('#tel').val(data.tel);
                $('#pertinencia').val(data.pertinencia);
                $('#medicamentos').val(data.medicamentos);
                $('#nombreMedicamento').val(data.nombreMedicamento);
                $('#observacion').val(data.observacion);
            },
            error: function () {
                alert('Paciente no encontrado');
            }
        });
    });
</script>



    