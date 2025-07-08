<?php
$id_factura = $_GET['id_factura'];
?>
<link rel="stylesheet" type="text/css" href="../librerias/css/jquery.autocomplete.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="../librerias/js/jquery.js"></script>
<script type='text/javascript' src='../librerias/js/jquery.autocomplete.js'></script>

<script>
	id_factura = "<?php echo $id_factura; ?>";
</script>
<script src="tablaNRAC.js"></script>


    <div class="card text">
        <h6>RIPS</h6>
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsUs()">Usuario</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="#" onclick="ripsAc()">Consultas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="#">Procedimientos</a>
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
<div class="modal fade" id="modalEditar" name="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
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
                                <label for="codconsu">Código de Consulta:</label>
                                <input type="text" class="form-control" id="codconsu" name="codconsu" maxlength='80' placeholder="Digite el código CUPS de la consulta" required> 
                                <input type="hidden" class="form-control" id="codconsulta" name="codconsulta" maxlength="6" required>
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
                                <label for="dxprinc">Dx Principal:</label>                                
                                <input type="text" class="form-control" id="dxprinc" name="dxprinc" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="coddiagnosticoprincipal" name="coddiagnosticoprincipal" required> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipodiagnosticoprincipal">Tipo de Dx Principal</label>
                                <select class="form-control" id="tipodiagnosticoprincipal" name="tipodiagnosticoprincipal" required>
                                    
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dxrel1">Dx Relacionado 1:</label>
                                <input type="text" class="form-control" id="dxrel1" name="dxrel1" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="coddiagnosticorelacionado1" name="coddiagnosticorelacionado1" required>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dxrel2">Dx Relacionado 2:</label>
                                <input type="text" class="form-control" id="dxrel2" name="dxrel2" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="coddiagnosticorelacionado2" name="coddiagnosticorelacionado2" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dxrel3">Dx Relacionado 3:</label>
                                <input type="text" class="form-control" id="dxrel3" name="dxrel3" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="coddiagnosticorelacionado3" name="coddiagnosticorelacionado3" required>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vrservicio">Valor:</label>
                                <input type="text" class="form-control" id="vrservicio" name="vrservicio" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="conceptorecaudo">Concepto de Recaudo:</label>
                                <select class="form-control" id="conceptorecaudo" name="conceptorecaudo" required>
                                    
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valorpagomoderador">Valor pago moderador:</label>
                                <input type="text" class="form-control" id="valorpagomoderador" name="valorpagomoderador" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">                            
                                <label for="numfevpagomoderador">Número FEV pago moderador:</label>
                                <input type="text" class="form-control" id="numfevpagomoderador" name="numfevpagomoderador" required>
                            </div>
                        </div>                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarConsulta()">Guardar<span class="fas fa-save"></span></button>
            </div>
        </div>
    </div>
</div>