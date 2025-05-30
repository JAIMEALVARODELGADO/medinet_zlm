<?php
//Aqui evito la utilizacion de cache con fines de refrescar tablas
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

require_once "clases/conexion.php";
$obj=new conectar();
$conexion=$obj->conexion();
$sql="SELECT id_parametro, nombre_parametro, codigo_parametro, descripcion, titulo, estado FROM parametros_generales ORDER BY nombre_parametro";
$result=mysqli_query($conexion,$sql)
?>

<div>
	<table class="table table-hover table-sm table-bordered font13" id="tablaparametros">
		<thead style="background-color: #2574a9;color: white; font-weight: bold;">
			<tr>				
				<td>Código</td>
				<td>Descripción</td>
				<td>Título</td>
				<td>Estado</td>
				<td>Editar</td>
			</tr>
		</thead>

		<tbody style="background-color: white">
			<?php
			while($row=mysqli_fetch_array($result)){                                
				?>
				<tr>					
					<td><?php echo $row['codigo_parametro'];?></td>
					<td><?php echo $row['descripcion'];?></td>
					<td><?php echo $row['titulo'];?></td>
					<td style="text-align: center;">
									
                    <div class="form-check form-switch">						
                    <input class="form-check-input" type="checkbox" 
                        <?php echo ($row['estado'] == 'AC') ? 'checked' : ''; ?>
                        onclick="cambiarEstado('<?php echo $row['id_parametro']?>', '<?php echo $row['estado']?>')"
                        id="estado_<?php echo $row['id_parametro']?>">
                    </div>
						
					</td>
					<td style="text-align: center;">
						<span class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modaleditarparametro" title="Editar Parámetro" onclick="FrmActualizar('<?php echo $row[0]?>')">
							<span class="far fa-edit"></span>
						</span>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
		
	</table>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tablaparametros').DataTable({
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
			}
		});
	} );
</script>