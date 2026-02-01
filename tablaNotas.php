<?php
session_start();
//Aqui evito la utilizacion de cache con fines de refrescar tablas
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

require_once "clases/conexion.php";
$obj=new conectar();
$conexion=$obj->conexion();
$conhis="SELECT ne.id_ne, ne.id_agc, ne.fecha_ne, ne.descripcion, 
CONCAT(p.pnom_per,' ' ,p.snom_per,' ' , p.pape_per,' ' ,p.sape_per) as nombre_operador
FROM notasenfermeria ne
LEFT JOIN persona p on p.id_persona = ne.operador_ne 
WHERE id_agc='$_SESSION[gid_agc]'
ORDER BY ne.fecha_ne DESC";

$result=mysqli_query($conexion,$conhis);
//echo $conhis;
?>
<div>
	<table class="table table-hover table-sm table-bordered font13" id="tablaanam">
		<thead style="background-color: #2574a9;color: white; font-weight: bold;">
			<tr>
				<td>Fecha</td>
				<td>Descripción</td>
				<td>Profesional</td>				
			</tr>
		</thead>		

		<tbody style="background-color: white">
			<?php
			while($row=mysqli_fetch_array($result)){
				?>
				<tr>
					<td><?php echo $row['fecha_ne'];?></td>
					<td><?php echo $row['descripcion'];?></td>
                    <td><?php echo $row['nombre_operador'];?></td>
				</tr>
				<?php
			}
			?>
		</tbody>		
	</table>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tablaanam').DataTable();
	} );
</script>