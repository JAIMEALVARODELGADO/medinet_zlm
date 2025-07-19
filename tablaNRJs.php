<?php
$id_factura = $_GET['id_factura'];
$numero_fac = $_GET['numero_fac'] ?? '';
?>
<link rel="stylesheet" type="text/css" href="../librerias/css/jquery.autocomplete.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!--<script type="text/javascript" src="../librerias/js/jquery.js"></script>
<script type='text/javascript' src='../librerias/js/jquery.autocomplete.js'></script>-->

<script src="tablaNRJs.js">
    id_factura = "<?php echo $id_factura; ?>";
	numero_fac = "<?php echo $numero_fac; ?>";
</script>


    <div class="card text">
		<h6>RIPS   <i class="text-danger">de la factura: <?php echo $numero_fac;?></i></h6>
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

							<div class="modal-footer">							
								<button type="button" class="btn btn-success" onclick="generarRipsJson()">Generar RIPS  </i><span class="fa-light fa-file"></span></button>
								<button type="button" class="btn btn-primary" onclick="descargarRipsJson()">Descargar RIPS  <span class="fas fa-save"></span></button>
							</div>	                        
	                    </div>	                    
	                </div>
	            </div>
	        </div>
	    </div>		
	</div>

