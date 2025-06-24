<?php
$id_factura = $_GET['id_factura'];
?>
<script src="tablaNRAC.js">	
</script>
<script>
	id_factura = "<?php echo $id_factura; ?>";
</script>

    <div class="card text">
        <h6>RIPS</h6>
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="ripsUs()">Usuario</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="#" onclick="#">Consultas</a>
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
							<table class="table table-hover table-sm table-bordered font13" id="tablaRips">
								<thead style="background-color: #2574a9;color: white; font-weight: bold;">
									<tr>				
										<td>Fecha</td>
										<td>Autorización</td>
										<td>Cód.Consulta</td>
										<td>Finalidad</td>
										<td>Dx Principal</td>
										<td>Valor</td>
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

<!-- Modal para Editar Consulta -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Consulta RIPS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="hidden" id="id_consulta" name="id_consulta">
                    <input type="hidden" id="id_detalle" name="id_detalle">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechainicioatencion">Fecha:</label>
                                <input type="date" class="form-control" id="fechainicioatencion" name="fechainicioatencion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numautorizacion">Número de Autorización:</label>
                                <input type="text" class="form-control" id="numautorizacion" name="numautorizacion" maxlength="30" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codconsulta">Código de Consulta:</label>
                                <input type="text" class="form-control" id="codconsulta" name="codconsulta" maxlength="6" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modalidadgruposervicio">Modalidad Grupo de Servicio:</label>
                                <select class="form-control" id="modalidadgruposervicio" name="modalidadgruposervicio" required>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gruposervicio">Grupo de Servicios:</label>
                                <select class="form-control" id="gruposervicio" name="gruposervicio" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codservicio">Código del Servicio:</label>
                                <select class="form-control" id="codservicio" name="codservicio" required>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="finalidadtecnologiasalud">Finalidad:</label>                                
                                <select class="form-control" id="finalidadtecnologiasalud" name="finalidadtecnologiasalud" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="causamotivoatencion">Causa Externa</label>
                                <select class="form-control" id="causamotivoatencion" name="causamotivoatencion" required>
                                    
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coddiagnosticoprincipal">Dx Principal:</label>
                                <input type="text" class="form-control" id="coddiagnosticoprincipal" name="coddiagnosticoprincipal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codpaisorigen">Código País Origen:</label>
                                <input type="text" class="form-control" id="codpaisorigen" name="codpaisorigen" maxlength="3" value="170" required>
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