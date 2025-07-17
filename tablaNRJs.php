<?php
$id_factura = $_GET['id_factura'];
?>
<link rel="stylesheet" type="text/css" href="../librerias/css/jquery.autocomplete.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!--<script type="text/javascript" src="../librerias/js/jquery.js"></script>
<script type='text/javascript' src='../librerias/js/jquery.autocomplete.js'></script>-->

<script src="tablaNRJs.js">
    var id_factura = "<?php echo $id_factura; ?>";	
</script>


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
					<a class="nav-link" href="#" onclick="ripsAt()">Otros Servicios</a>
				</li>
                <li class="nav-item">
					<a class="nav-link active" href="#">Generar Json</a>
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
									<!--<tr>				
										<td>Fecha</td>
                                        <td>Autorización</td>
										<td>ID Mipres</td>                                        
										<td>Código</td>
										<td>Nombre</td>
										<td>Cantidad</td>
                                        <td>Vr.Unitario</td>
										<td>Vr.Total</td>
										<td colspan="3">Opciones</td>				
									</tr>-->
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

