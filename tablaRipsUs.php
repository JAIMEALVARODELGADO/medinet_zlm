<?php
$id_factura = $_GET['id_factura'];

?>
<script src="tablaRipsUs.js">	
</script>
<script>
	id_factura = "<?php echo $id_factura; ?>";	
</script>

    <div class="card text">
        <h6>RIPS</h6>
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link active" href="#">Usuario</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsAc()">Consultas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_ripsAp.php">Procedimientos</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="mn_ripsOt.php">Otros Servicios</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="mn_ripsOt.php">Generar Json</a>
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

	                        <!--<div id="tablaDatafactura"></div>-->
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
                <form id="formEditarUsuario">
                    <input type="hidden" id="edit_id_usuario" name="id_usuario">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tipo_documento">Tipo Documento:</label>
                                <select class="form-control" id="edit_tipo_documento" name="tipo_documento" required>
                                    <option value="">Seleccione...</option>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                    <option value="RC">Registro Civil</option>
                                    <option value="PA">Pasaporte</option>
                                    <option value="CE">Cédula de Extranjería</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_numdocumento">Número Documento:</label>
                                <input type="text" class="form-control" id="edit_numdocumento" name="numdocumento" maxlength="20" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tipousuario">Tipo Usuario:</label>
                                <select class="form-control" id="edit_tipousuario" name="tipousuario" required>
                                    <option value="">Seleccione...</option>
                                    <option value="01">Contributivo</option>
                                    <option value="02">Subsidiado</option>
                                    <option value="03">Vinculado</option>
                                    <option value="04">Particular</option>
                                    <option value="05">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_fechanacimiento">Fecha Nacimiento:</label>
                                <input type="date" class="form-control" id="edit_fechanacimiento" name="fechanacimiento" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codsexo">Sexo:</label>
                                <select class="form-control" id="edit_codsexo" name="codsexo" required>
                                    <option value="">Seleccione...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codpaisresidencia">Código País Residencia:</label>
                                <input type="text" class="form-control" id="edit_codpaisresidencia" name="codpaisresidencia" maxlength="3" value="170" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codmunicipioresidencia">Código Municipio:</label>
                                <input type="text" class="form-control" id="edit_codmunicipioresidencia" name="codmunicipioresidencia" maxlength="5" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codzonaresidencia">Zona Residencia:</label>
                                <select class="form-control" id="edit_codzonaresidencia" name="codzonaresidencia" required>
                                    <option value="">Seleccione...</option>
                                    <option value="01">Rural</option>
                                    <option value="02">Urbana</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_incapacidad">Incapacidad:</label>
                                <select class="form-control" id="edit_incapacidad" name="incapacidad" required>
                                    <option value="">Seleccione...</option>
                                    <option value="NO">No</option>
                                    <option value="SI">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codpaisorigen">Código País Origen:</label>
                                <input type="text" class="form-control" id="edit_codpaisorigen" name="codpaisorigen" maxlength="3" value="170" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>