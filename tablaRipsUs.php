<?php
$id_factura = $_GET['id_factura'];
$numero_fac = $_GET['numero_fac'] ?? '';

?>
<link rel="stylesheet" type="text/css" href="./librerias/css/jquery.autocomplete.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="./librerias/js/jquery.js"></script>
<script type='text/javascript' src='./librerias/js/jquery.autocomplete.js'></script>
<script src="tablaRipsUs.js">	
</script>

<script>
    $(document).ready(function() {
    let id_factura = "<?php echo $id_factura; ?>";
    let numero_fac = "<?php echo $numero_fac; ?>";
    //alert(numero_fac);
    crearRips(id_factura);
    cargarTpDocumento();
    cargarTpUsuario();
    cargarSexo();
    cargarMunicipios();
    cargarZona();
    cargarPais();
    });
</script>

    <div class="card text">
        <h6>RIPS   <i class="text-danger">de la factura: <?php echo $numero_fac;?></i></h6>
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link active" href="#">Usuario</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsAc()">Consultas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsAp()">Procedimientos</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsAt()">Otros Servicios</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsJs()">Generar Json</a>                    
				</li>
                <li class="nav-item">
					<a class="nav-link" href="#" onclick="cerrar()">Cerrar</a>
				</li>
			</ul>
		</div>       
		<br>
		<div class="container">
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="card text-left">
	                    
	                    <div class="card-body">
							<table class="table table-hover table-sm table-bordered font13" id="tablaRipsUs">
								<thead style="background-color: #2574a9;color: white; font-weight: bold;">
									<tr>				
										<td>Tp Doc</td>
										<td>Número</td>
										<td>Nombre</td>
										<td>Tp Usuario</td>
										<td>Fecha Nac</td>
										<td>Sexo</td>
										<td colspan="3">Opciones</td>				
									</tr>
								</thead>
								
								<tbody style="background-color: white">
									
								</tbody>
								
							</table>
                            	                        
	                    </div>
	                    
	                </div>
	            </div>
	        </div>
	    </div>		
	</div>

<!-- Modal para Editar Usuario -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Usuario RIPS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="hidden" id="id_usuario" name="id_usuario">
                    <input type="hidden" id="id_factura" name="id_factura" value='<?php echo $id_factura;?>'>
                    <input type="hidden" id="numero_fac" name="numero_fac" value='<?php echo $numero_fac;?>'>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_documento">Tipo Documento:</label>
                                <select class="form-control" id="tipo_documento" name="tipo_documento" required>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numdocumento">Número de Documento:</label>
                                <input type="text" class="form-control" id="numdocumento" name="numdocumento" maxlength="20" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipousuario">Tipo de Usuario:</label>
                                <select class="form-control" id="tipousuario" name="tipousuario" required>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechanacimiento">Fecha Nacimiento:</label>
                                <input type="date" class="form-control" id="fechanacimiento" name="fechanacimiento" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codsexo">Sexo:</label>
                                <select class="form-control" id="codsexo" name="codsexo" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codpaisresidencia">Código País de Residencia:</label>
                                <select class="form-control" id="codpaisresidencia" name="codpaisresidencia" required>
                                    
                                </select>
                                <!--<input type="text" class="form-control" id="codpaisresidencia" name="codpaisresidencia" maxlength="3" value="170" required>-->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codmunicipioresidencia">Municipio de Residencia:</label>                                
                                <select class="form-control" id="codmunicipioresidencia" name="codmunicipioresidencia" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codzonaresidencia">Zona Residencia:</label>
                                <select class="form-control" id="codzonaresidencia" name="codzonaresidencia" required>
                                    
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="incapacidad">Incapacidad:</label>
                                <select class="form-control" id="incapacidad" name="incapacidad" required>
                                    <option value="">Seleccione...</option>
                                    <option value="NO">No</option>
                                    <option value="SI">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codpaisorigen">Código País Origen:</label>
                                <select class="form-control" id="codpaisorigen" name="codpaisorigen" required>
                                    
                                </select>
                                <!--<input type="text" class="form-control" id="codpaisorigen" name="codpaisorigen" maxlength="3" value="170" required>-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar<span class="fas fa-save"></span></button>
            </div>
        </div>
    </div>
</div>