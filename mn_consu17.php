<?php
require("valida_sesion.php");
require_once "clases/conexion.php";
$obj=new conectar();
$conexion=$obj->conexion();
$conhis="SELECT id_aten FROM atencion WHERE id_agc='$_SESSION[gid_agc]'";
//echo $conhis;
$conhis=mysqli_query($conexion,$conhis);
if(mysqli_num_rows($conhis)!=0){
	$rowhis=mysqli_fetch_row($conhis);
	$id_aten=$rowhis[0];
}
else{
	$id_aten=0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Medinet V3</title>
	<?php 
		require_once "scripts.php";
		
	?>
	<link rel="stylesheet" type="text/css" href="../librerias/css/jquery.autocomplete.css">
	<script type="text/javascript" src="../librerias/js/jquery.js"></script>
	<script type='text/javascript' src='../librerias/js/jquery.autocomplete.js'></script>
	<script type='text/javascript' src='js/mn_consu17.js'></script>
</head>

<body>
	<?php
	require("encabezado.php");
	//require("menu.php")
	?>
	<div class="card text">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="mn_consu11.php">Historia de Consulta</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_consu15.php">Procedimientos</a>
				</li>
                <li class="nav-item">
					<a class="nav-link active" href="#">Procedim. Menores</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_consu12.php">Formula</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_consu13.php">Ordenes</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_consu16.php">Adjuntos</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_consu14.php">Finalizar Conulta</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_consu1.php">Pacientes Agendados</a>
				</li>
			</ul>
		</div>
		<!--<nav class="navbar navbar-expand-sm bg-light">			
			<ul class="navbar-nav">
				<span class="btn btn-secondary" data-toggle="modal" data-target="#modal_historial" title="Histórico de Procedimientos Menores">Procedimientos
					<i class="fas fa-procedures"></i>
				</span>
			</ul> 
		</nav>-->

		<br><h5>Procedimientos Menores</h5> 
		<div class="container-fluid">       
			<div class="card-body">
				<span class="btn btn-secondary" data-toggle="modal" data-target="#modalnuevoprocedimiento" title="Agrega Procedimientos">
					Nuevo <span class="fas fa-plus-circle"></span>
				</span>
                <hr>
                <div id="tablaDataprocedimientoMen"></div>				
			</div>
		</div>
		<!-- Modal Nuevo -->
		<div class="modal fade" id="modalnuevoprocedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-xl" role="document">
			    <div class="modal-content">
			        <div class="modal-header">
			            <h5 class="modal-title" id="exampleModalLabel">Agregar un Procedimiento Menor</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			            <form id="frm_nuevo">
							<input type="hidden" name="id_aten" id="id_aten" value="<?php echo $id_aten; ?>">
			                <label>Tipo de Procedimiento</label>
			                <select class="form-control" id="tipo_proc" name="tipo_proc" onchange="traePlantilla()">
                            	<option value=''></option>
								<option value='plantillacolonoscopia'>Colonoscopia</option>
								<option value='plantillaesofagogastroduodenoscopia'>Esofagogastroduodenoscopia</option>
                        	</select>
			                <label>Descripción</label>
			                <textarea rows="20" cols="120" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" style="font-size: 10px;"></textarea>
			            </form>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <span class="fas fa-angle-double-left"></span></button>
			            <button type="button" id="btnNuevo" class="btn btn-primary">Guardar <span class="fas fa-save"></span></button>
			        </div>
			    </div>
			</div>
		</div>

		<!-- Modal Editar -->
		<div class="modal fade" id="modaleditarprocedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			    <div class="modal-content">
			        <div class="modal-header">
			            <h5 class="modal-title" id="exampleModalLabel">Editar Procedimiento</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			            <form id="frm_editar">
							<label>Descripción</label>
			                <textarea rows="20" cols="120" class="form-control" id="descripcion_ed" name="descripcion_ed" placeholder="Descripción" style="font-size: 10px;"></textarea>
							<input type="hidden" name="id_procedimiento_ed" id="id_procedimiento_ed">
			
			            </form>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <span class="fas fa-angle-double-left"></span></button>
			            <button type="button" id="btnActualizar" class="btn btn-primary">Guardar <span class="fas fa-save"></span></button>
			        </div>
			    </div>
			</div>
		</div>

		<!-- Modal Historial de procedimientos -->
		<!--<div class="modal fade" id="modal_historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			        <div class="modal-header">
			            <h5 class="modal-title" id="exampleModalLabel">Histórico de Procedimientos</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			        	<div id="tablaDatahispro"></div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <span class="fas fa-angle-double-left"></span></button>			            
			        </div>
			    </div>
			</div>
		</div>-->

	</div>
</body>

</html>

<script type="text/javascript">
    $(document).ready(function(){
        $("#tablaDataprocedimientoMen").load("tablaprocedimientomen.php");
    });
</script>



<script type="text/javascript">
    $(document).ready(function(){
        $("#btnNuevo").click(function(){
            var datos=$('#frm_nuevo').serialize();
			//datos+= '&operador_proc=<?php echo $_SESSION['gusuario_log']; ?>';
			datos+= '&opcion=nuevo';
			
            $.ajax({
                type:"POST",
                data:datos,
                url:"procesos/crudProcedMenores.php",
                success:function(r){
					//console.log(r)
                    const data = JSON.parse(r);

					if (data.success) {
						$("#tablaDataprocedimientoMen").load("tablaprocedimientomen.php");
                		$('#frm_editar')[0].reset();
                		alertify.success(data.mensaje);

						// 🔴 Cerrar la ventana modal
                		//$('#modaleditarprocedimiento').modal('hide');
						var modal = document.getElementById('modaleditarprocedimiento');
						var modalInstance = bootstrap.Modal.getOrCreateInstance(modal); // crea si no existe
						modalInstance.hide();
					} else {
						alertify.error("Error: El registro no fue guardado");
					}
                }
            });
        });

        $('#btnActualizar').click(function(){
            var datos=$('#frm_editar').serialize();
			datos+= '&opcion=editar';
			//console.log(datos);
            $.ajax({
                type:"POST",
                data:datos,
                url:"procesos/crudProcedMenores.php",
                success:function(r){
					const data = JSON.parse(r);
                    if (data.success) {
                        $("#tablaDataprocedimientoMen").load("tablaprocedimientomen.php");
						$('#frm_editar')[0].reset();
                        alertify.success("Registro guardado");

						// 🔴 Cerrar la ventana modal
                		//$('#modaleditarprocedimiento').modal('hide');
						var modal = document.getElementById('modaleditarprocedimiento');
						var modalInstance = bootstrap.Modal.getOrCreateInstance(modal); // crea si no existe
						modalInstance.hide();
                    }
                    else{
                        alertify.error("Error: Registro no guardado");
                    }
                }
            });
        });
    });
</script>


<script type="text/javascript">
	

	function FrmActualizar(idproc){
		
        $.ajax({
            type:"POST",
            data: {
				id_procmenor: idproc,
				opcion: "consultar_Id"
			},
            url:"procesos/crudProcedMenores.php",
            success:function(r){
	 			var datos = JSON.parse(r);
				 if (datos.success) {
					$('#descripcion_ed').val(datos.data.descripcion);
					$('#id_procedimiento_ed').val(datos.data.id_procmenor);
				} else {
					alertify.error(datos.mensaje);
				}
            }
        })
    }

	function eliminarDatos(idproc){
		
		alertify.confirm(
			'Eliminar Procedimiento',
			'Desea eliminar el procedimiento ?' ,
			function(){ 
				$.ajax({
					type:"POST",
					//data:"idproc=" + idproc,
					data: {
						id_procmenor: idproc,
						opcion: "eliminar_Id"
					},
					url:"procesos/crudProcedMenores.php",
					dataType: "json",
					success:function(data){
						if(data.success){
							$("#tablaDataprocedimientoMen").load("tablaprocedimientomen.php");
							alertify.success("Registro Eliminado!");
						} else {
							alertify.error("Error: "+data.mensaje);
						}
					}

				});
			},
			function(){
				alertify.error("Acción cancelada");
			}
		);
	}
</script>

<script type="text/javascript">
	$().ready(function() {
		$("#dxprinc").autocomplete("procesos/autocomp_cie.php", {
			width: 460,
			matchContains: false,
			mustMatch: false,
			selectFirst: false
		});
		$("#dxprinc").result(function(event, data, formatted) {
			$("#dxprinc_proc").val(data[1]);
		});

		$("#dxrelac").autocomplete("procesos/autocomp_cie.php", {
			width: 460,
			matchContains: false,
			mustMatch: false,
			selectFirst: false
		});
		$("#dxrelac").result(function(event, data, formatted) {
			$("#dxrelac_proc").val(data[1]);
		});

		$("#complic").autocomplete("procesos/autocomp_cie.php", {
			width: 460,
			matchContains: false,
			mustMatch: false,
			selectFirst: false
		});
		$("#complic").result(function(event, data, formatted) {
			$("#complic_proc").val(data[1]);
		});

		$("#dxprincU").autocomplete("procesos/autocomp_cie.php", {
			width: 460,
			matchContains: false,
			mustMatch: false,
			selectFirst: false
		});
		$("#dxprincU").result(function(event, data, formatted) {
			$("#dxprinc_procU").val(data[1]);
		});

		$("#dxrelacU").autocomplete("procesos/autocomp_cie.php", {
			width: 460,
			matchContains: false,
			mustMatch: false,
			selectFirst: false
		});
		$("#dxrelacU").result(function(event, data, formatted) {
			$("#dxrelac_procU").val(data[1]);
		});

		$("#complicU").autocomplete("procesos/autocomp_cie.php", {
			width: 460,
			matchContains: false,
			mustMatch: false,
			selectFirst: false
		});
		$("#complicU").result(function(event, data, formatted) {
			$("#complic_procU").val(data[1]);
		});

	});

	 $(document).ready(function(){
        $("#tablaDatahispro").load("tablahistoprocedimientos.php");
    });
</script>


<!---Aqui desactivo la combinacion Ctrl-Click -->
<script type="text/javascript">
    $('a').click(function (e){  
    if (e.ctrlKey) {
        return false;
    }
    });
</script>