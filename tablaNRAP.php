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
<script src="tablaNRAP.js"></script>


    <div class="card text">
        <h6>RIPS</h6>
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsUs()">Usuario</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsAc()">Consultas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="#">Procedimientos</a>
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
							<table class="table table-hover table-sm table-bordered font13" id="tablaRips">
								<thead style="background-color: #2574a9;color: white; font-weight: bold;">
									<tr>				
										<td>Fecha</td>
                                        <td>Autorización</td>
										<td>ID Mipres</td>                                        
										<td>Cód.Procedimiento</td>
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

<!-- Modal para Editar Procedimiento -->
<div class="modal fade" id="modalEditar" name="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Procedimiento RIPS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="hidden" id="id_procedimiento" name="id_procedimiento">
                    <input type="hidden" id="id_detfac" name="id_detfac">                    
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechainicioatencion">Fecha:</label>
                                <input type="datetime-local" class="form-control" id="fechainicioatencion" name="fechainicioatencion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idmipres">ID Mipres:</label>
                                <input type="text" class="form-control" id="idmipres" name="idmipres" maxlength="15" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numautorizacion">Autorización:</label>
                                <input type="text" class="form-control" id="numautorizacion" name="numautorizacion" maxlength="30" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codproced">Código del Procedimiento:</label>
                                <input type="text" class="form-control" id="codproced" name="codproced" maxlength='80' placeholder="Digite el código CUPS del procedimiento" required> 
                                <input type="hidden" class="form-control" id="codprocedimiento" name="codprocedimiento" maxlength="6" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="viaingresoserviciosalud">Vía de ingreso:</label>
                                <select class="form-control" id="viaingresoserviciosalud" name="viaingresoserviciosalud" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modalidadgruposerviciotecsal">Modalidad Grupo de Servicio:</label>
                                <select class="form-control" id="modalidadgruposerviciotecsal" name="modalidadgruposerviciotecsal" required>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gruposervicios">Grupo de Servicios:</label>
                                <select class="form-control" id="gruposervicios" name="gruposervicios" required>

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
                                <label for="dxprinc">Dx Principal:</label>                                
                                <input type="text" class="form-control" id="dxprinc" name="dxprinc" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="coddiagnosticoprincipal" name="coddiagnosticoprincipal" required> 
                            </div>                            
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dxrel">Dx Relacionado:</label>
                                <input type="text" class="form-control" id="dxrel" name="dxrel" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="coddiagnosticorelacionado" name="coddiagnosticorelacionado" required>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="compli">Complicación:</label>
                                <input type="text" class="form-control" id="compli" name="compli" maxlength='80' placeholder="Digite el código CIE10 o la descripción" required> 
                                <input type="hidden" class="form-control" id="codcomplicacion" name="codcomplicacion" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">                                              
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
                                <label for="conceptorecsaudo">Concepto de Recaudo:</label>
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
                <button type="button" class="btn btn-primary" onclick="guardarProcedimiento()">Guardar<span class="fas fa-save"></span></button>
            </div>
        </div>
    </div>
</div>