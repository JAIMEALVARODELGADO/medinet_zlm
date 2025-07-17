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
<script src="tablaNRAT.js"></script>

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
					<a class="nav-link" href="#" onclick="ripsAp()">Procedimientos</a>
				</li>
                <li class="nav-item">
					<a class="nav-link active" href="#">Otros Servicios</a>
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
										<td>Código</td>
										<td>Nombre</td>
										<td>Cantidad</td>
                                        <td>Vr.Unitario</td>
										<td>Vr.Total</td>
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
                <h5 class="modal-title" id="modalEditarLabel">Editar Otros Servicios RIPS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="text" id="id_otroservicio" name="id_otroservicio">
                    <!--<input type="text" id="id_detfac" name="id_detfac">-->
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechasuministrotecnologia">Fecha:</label>
                                <input type="date" class="form-control" id="fechasuministrotecnologia" name="fechasuministrotecnologia" required>
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
                                <label for="tipoos">Tipo de servicio:</label>
                                <select class="form-control" id="tipoos" name="tipoos" required>

                                </select>
                            </div>
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codtecnologia">Código de la tecnología:</label>                                
                                <input type="text" class="form-control" id="codtecnologia" name="codtecnologia" maxlength="20" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomtecnologia">Nombre de la tecnología:</label>                                
                                <input type="text" class="form-control" id="nomtecnologia" name="nomtecnologia" maxlength="20" required>
                            </div>
                        </div>
                    </div>                    

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cantidados">Cantidad:</label>
                                <input type="text" class="form-control" id="cantidados" name="cantidados" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vrunitos">Valor unitario:</label>
                                <input type="text" class="form-control" id="vrunitos" name="vrunitos" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vrservicio">Valor total:</label>
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
                <button type="button" class="btn btn-primary" onclick="guardarServicio()">Guardar<span class="fas fa-save"></span></button>
            </div>
        </div>
    </div>
</div>