
<div class="row">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0 text-dark">
            <i class="fas fa-chart-line text-primary"></i> Informes Fisiatría
          </h1>
          <p class="text-muted mb-0">Consulta y genera reportes de atención</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-filter"></i> Filtros de Búsqueda
        </h3>
      </div>
      
      @csrf
      <div class="card-body">
        <form id="formInformes">
          <div class="row">
            
            <!-- Filtro de Fechas -->
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="fechaini" class="font-weight-bold">
                  <i class="far fa-calendar-alt text-primary"></i> Rango de Fechas
                </label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar"></i>
                    </span>
                  </div>
                  <input type="date" 
                         name="fechaini" 
                         id="fechaini" 
                         class="form-control"
                         placeholder="Fecha inicial">
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar"></i>
                    </span>
                  </div>
                  <input type="date" 
                         name="fechafin" 
                         id="fechafin" 
                         class="form-control"
                         placeholder="Fecha final">
                </div>
              </div>
            </div>

            <!-- Filtro de Profesional -->
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="profesional" class="font-weight-bold">
                  <i class="fas fa-user-md text-primary"></i> Profesional
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fas fa-user-md"></i>
                    </span>
                  </div>
                  <select name="profesional" 
                          id="profesional" 
                          class="form-control select2bs4">
                    <option value="">Todos los profesionales</option>
                    <option value="SANTIAGO SANCHEZ">Santiago Sánchez</option>
                    <option value="LUIS FERNANDO ROMAN">Luis Fernando Román</option>
                    <option value="SANDRA ROMANO">Sandra Romano</option>
                    <option value="ROLAND TREJOS">Roland Trejos</option>
                    <option value="VICTOR MARTINEZ">Víctor Martínez</option>
                    <option value="JAVIER BENAVIDES">Javier Benavides</option>
                    <option value="DIANA LOPEZ">Diana López</option>
                    <option value="LEONARDO ARCE">Leonardo Arce</option>
                    <option value="JIMENA CALLE">Jimena Calle</option>
                    <option value="KATALINA ESPINOSA">Katalina Espinosa</option>
                    <option value="DIANA MURCIA">Diana Murcia</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Filtro de EPS -->
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="eps" class="font-weight-bold">
                  <i class="fas fa-hospital text-primary"></i> EPS
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fas fa-hospital"></i>
                    </span>
                  </div>
                  <select name="eps" 
                          id="eps" 
                          class="form-control select2bs4">
                    <option value="">Todas las EPS</option>
                    <option value="COMFENALCO">Comfenalco</option>
                    <option value="COOSALUD">Coosalud</option>
                    <option value="EMSSANAR">Emssanar</option>
                    <option value="SOS">SOS</option>
                    <option value="SALUD TOTAL">Salud Total</option>
                    <option value="PARTICULAR">Particular</option>
                  </select>
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>

      <!-- Footer con botones -->
      <div class="card-footer">
        <div class="row">
          <div class="col-12 text-right">
            <button type="button" 
                    name="reset" 
                    id="reset" 
                    class="btn btn-default">
              <i class="fas fa-eraser"></i> Limpiar
            </button>
            <button type="submit" 
                    name="buscar" 
                    id="buscar" 
                    class="btn btn-primary">
              <i class="fas fa-search"></i> Buscar
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>