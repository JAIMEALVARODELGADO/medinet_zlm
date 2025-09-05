<?php
//Aqui evito la utilizacion de cache con fines de refrescar tablas
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

require_once "clases/conexion.php";
$obj=new conectar();
$conexion=$obj->conexion();
$sql="SELECT codi_det,descripcion_grupo,descripcion_det,valor_det,estado FROM vw_conceptos ORDER BY descripcion_grupo,descripcion_det";
//echo $sql;
$result=mysqli_query($conexion,$sql)
?>

<div>
	<table class="table table-hover table-sm table-bordered font13" id="tablaconcep">
		<thead style="background-color: #2574a9;color: white; font-weight: bold;">
			<tr>
				<td>Concepto</td>
				<td>Detalle</td>
				<td>Valor</td>
				<td>Estado</td>
				<td>Editar</td>				
			</tr>
		</thead>

		<tbody style="background-color: white">
			<?php
			while($row=mysqli_fetch_array($result)){				
				?>
				<tr>
					<td><?php echo $row['descripcion_grupo'];?></td>
					<td><?php echo $row['descripcion_det'];?></td>
					<td><?php echo $row['valor_det'];?></td>
					
					<td style="text-align: center;">
									
                    <div class="form-check form-switch">						
                    <input class="form-check-input" type="checkbox" 
                        <?php echo ($row['estado'] == 'AC') ? 'checked' : ''; ?>
                        onclick="cambiarEstado('<?php echo $row['codi_det']?>', '<?php echo $row['estado']?>')"
                        id="estado_<?php echo $row['codi_det']?>">
                    </div>
						
					</td>

					<td style="text-align: center;">
						<span class="btn btn-warning btn.sm" data-toggle="modal" data-target="#modaleditarconcep" title="Editar El Registro" onclick="FrmActualizar('<?php echo $row[0]?>')">
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
		$('#tablaconcep').DataTable();
	} );
</script>