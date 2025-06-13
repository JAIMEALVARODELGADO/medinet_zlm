<?php
$id_factura = $_GET['id_factura'];

?>
<script>
	id_factura = "<?php echo $id_factura; ?>";
    function cerrar(){		
		$("#tablaDataRips").empty();
    }
    function ripsAc(){				
        $("#tablaDataRips").load("mn_RipsAc.php");
    }
	$(document).ready(function() {		
		crearRips();		
	});

	function crearRips() {
		var url = "procesos/rips_procesos.php?id_factura=" + id_factura
		+"&opcion=crearRips";
		console.log(url);
		fetch(url, {
			method: 'GET',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.then(response => response.text())
		.then(data => {
			//document.getElementById('resultado').innerHTML = data;
			cargarTablaRips(data);
		})
		.catch(error => {
			console.error('Error:', error);
		});
	}

	function cargarTablaRips(data) {
		alert(data);
		//document.getElementById('tablaDataRips').innerHTML = data;
		// Aquí puedes agregar más lógica si es necesario
	}
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

                            


	                        
	                        <!--<div id="tablaDatafactura"></div>-->
	                    </div>
	                    
	                </div>
	            </div>
	        </div>
	    </div>		
	</div>

	