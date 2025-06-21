<?php
$id_factura = $_GET['id_factura'];

?>
<script src="tablaNRAP.js">	
</script>
<script>
	id_factura = "<?php echo $id_factura; ?>";    
</script>

    <div class="card text">
        <h6>RIPS</h6>
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="ripsUs()">Usuario</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="#">Consultas</a>
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
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card text-left">
                        <div class="card-body">
                            <table class="table table-hover table-sm table-bordered font13" id="tablaRipsUsAc">
                                <thead style="background-color: #2574a9;color: white; font-weight: bold;">
                                    <tr>				
                                        <td>Fecha</td>
                                        <td>Hora</td>
                                        <td>Tipo</td>
                                        <td>Servicio</td>
                                        <td>Diagnóstico</td>
                                        <td colspan="3">Opciones</td>				
                                    </tr>
                                </thead>
                                
                                <tbody style="background-color: white">
                                    
                                </tbody>
                                
                            </table>
                        </div>
                    </div>