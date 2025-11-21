<form action="{{ route('guardar.paciente') }}" method="POST">
    @csrf 

<div class="container mt-4">
    <h3 class="mb-4">Datos del Pacienteeee</h3>

    <!-- Primera fila -->
    
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="documento" class="form-label" style="color: #000000;">N° Documento</label>
            <input type="text" style="color: #000000;" name="documento" id="documento" class="form-control UpperCase" value="{{old('documento')}}" readonly>
        </div>
         <div class="col-md-3">
            <label for="numhistoria" class="form-label" style="color: #000000;">Historia</label>
            <input type="text" style="color: #000000;" name="numhistoria" id="numhistoria" class="form-control UpperCase" value="{{old('numhistoria')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="evo" class="form-label" style="color: #000000;">ID Evolución</label>
            <input type="text" style="color: #000000;" name="evo" id="evo" class="form-control UpperCase" value="{{old('evo')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="fecha" class="form-label" style="color: #000000;">FechaApertura</label>
            <input type="text" style="color: #000000;" name="fecha" id="fecha" class="form-control UpperCase" value="{{old('fecha')}}" readonly>
        </div>
        
    </div>

    <!-- Segunda fila -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="historia" class="form-label" style="color: #000000;">FechaEvolución</label>
            <input type="text" style="color: #000000;" name="historia" id="historia" class="form-control UpperCase" value="{{old('historia')}}" readonly>
        </div>
        <!-- <div class="col-md-3">
            <label for="apertura" class="form-label" style="color: #000000;">Tipo HC</label>
            <input type="text" style="color: #000000;" name="apertura" id="apertura" class="form-control UpperCase" value="{{old('apertura')}}" readonly>
        </div> -->
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
            <label for="apellido" class="form-label" style="color: #000000;">1er Apellido</label>
            <input type="text" style="color: #000000;" name="apellido" id="apellido" class="form-control UpperCase" value="{{old('apellido')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="apellid" class="form-label" style="color: #000000;">2do Apellido</label>
            <input type="text" style="color: #000000;" name="apellid" id="apellid" class="form-control UpperCase" value="{{old('apellid')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="nombre" class="form-label" style="color: #000000;">1er Nombre</label>
            <input type="text" style="color: #000000;" name="nombre" id="nombre" class="form-control UpperCase" value="{{old('nombre')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="nombr" class="form-label" style="color: #000000;">2do Nombre</label>
            <input type="text" style="color: #000000;" name="nombr" id="nombr" class="form-control UpperCase" value="{{old('nombr')}}" readonly>
        </div>
    </div>

    <!-- Cuarta fila -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="eps" class="form-label" style="color: #000000;">EPS</label>
            <input type="text" style="color: #000000;" name="eps" id="eps" class="form-control UpperCase" value="{{old('eps')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="tel" class="form-label" style="color: #000000;">Teléfono</label>
            <input type="text" style="color: #000000;" name="tel" id="tel" class="form-control UpperCase" value="{{old('tel')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="tel1" class="form-label" style="color: #000000;">Telefono avi</label>
            <input type="text" style="color: #000000;" name="tel1" id="tel1" class="form-control UpperCase" value="{{old('tel1')}}" readonly>
        </div>

        <div class="col-md-3">
            <label for="dxp" class="form-label" style="color: #000000;">DX PPL</label>
            <input type="text" style="color: #000000;" name="dxp" id="dxp" class="form-control UpperCase" value="{{old('dxp')}}" readonly>
        </div>
        <div class="col-md-3">
            <label for="dxr" class="form-label" style="color: #000000;">DX RL</label>
            <input type="text" style="color: #000000;" name="dxr" id="dxr" class="form-control UpperCase" value="{{old('dxr')}}" readonly> 
        </div>       
    </div>

    <!-- Pertenencia -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label class="form-label fw-bold" style="color: #000000;">Pertinencia</label><br>
            <div class="form-check form-check-inline">
                <input type="radio" name="pertinencia" value="si" class="form-check-input" id="pertinente">
                <label class="form-check-label" for="pertinente" style="color: #000000;">Pertinente</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="pertinencia" value="no" class="form-check-input" id="no_pertinente">
                <label class="form-check-label" for="no_pertinente" style="color: #000000;">No pertinente</label>
            </div>
        </div>
    </div>

    <div class="row mb-4">
    <div class="col-md-12">
        <label class="form-label fw-bold">¿Se entregan medicamentos?</label><br>

        <div class="form-check form-check-inline">
            <input type="radio" name="medicamentos" value="si" class="form-check-input" id="Si">
            <label class="form-check-label" for="Si" style="color: #000000;">Sí</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" name="medicamentos" value="no" class="form-check-input" id="No">
            <label class="form-check-label" for="No" style="color: #000000;">No</label>
        </div>

        <!-- Campo oculto que se mostrará si seleccionan "Sí" -->
        <div class="mt-3" id="campoMedicamento" style="display: none;">
            <label for="nombreMedicamento" class="form-label" style="color: #000000;">Indique el medicamento:</label>
            <input type="text" class="form-control" id="nombreMedicamento" name="nombreMedicamento" placeholder="Escriba el nombre del medicamento">
        </div>
        
    </div>
</div>

<script>
    const radios = document.getElementsByName('medicamentos');
    const campoMedicamento = document.getElementById('campoMedicamento');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'si' && radio.checked) {
                campoMedicamento.style.display = 'block';
            } else if (radio.value === 'no' && radio.checked) {
                campoMedicamento.style.display = 'none';
            }
        });
    });
</script>

    <!-- Observación -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="observacion" class="form-label fw-bold">Observación</label>
            <textarea name="observacion" id="observacion" class="form-control" rows="5"></textarea>
        </div>
    </div>

    <!-- Botón -->
    <div class="row">
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </div>
</div>
</form>



    