<?php
session_start();
//Aqui evito la utilizacion de cache con fines de refrescar tablas
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

require_once "clases/conexion.php";
require_once "procesos/mn_funciones.php";
$obj=new conectar();
$conexion=$obj->conexion();

//$fechaini=cambiafecha(hoy()).' 00:00';
//$fechafin=cambiafecha(hoy()).' 23:59';
$fechaini=$_SESSION['gfecha_cita'].' 00:00';
$fechafin=$_SESSION['gfecha_cita'].' 23:59';
$sql="SELECT vw_agenda_medico.id_agc,fecha_agh,numero_iden_per,nombre,estado_agc,nombre_profesional,id_aten,observacion_agc,nombre_profesional FROM vw_agenda_medico LEFT JOIN atencion ON atencion.id_agc=vw_agenda_medico.id_agc 
WHERE fecha_agh BETWEEN '$fechaini' AND '$fechafin' ORDER BY fecha_agh";
echo "<br>".$sql;
$result=mysqli_query($conexion,$sql)
?>

<div>
	<table class="table table-hover table-sm table-bordered font13" id="tablaagenda">
		<thead style="background-color: #2574a9;color: white; font-weight: bold;">
			<tr>
				<td>Fecha</td>
				<td>Identificación</td>
				<td>Nombre</td>
				<td>Observación</td>
				<td>Estado</td>
                <td>Profesional</td>
				<td>Opciones</td>
			</tr>
		</thead>

		<tbody style="background-color: white">
			<?php
			while($row=mysqli_fetch_array($result)){
				?>
				<tr>
					<!--<td style="text-align: center;">
						<?php
							/*if($row['estado_agc']=='Inasistencia' or $row['estado_agc']=='Cumplida' or $row['estado_agc']=='Cancelada'){
								?>
								<span class="btn btn-secondary btn.sm" title="Registrar Atención">
									<span class="fas fa-briefcase-medical"></span>
								</span>
								<?php
							}
							else{
								?>
								<span class="btn btn-success btn.sm" title="Registrar Atención" onclick="atender('<?php echo $row[0]?>')">
									<span class="fas fa-briefcase-medical"></span>
								</span>
								<?php
							}*/
						?>
						
					</td>-->
					<td style="text-align: center;"><?php echo $row['fecha_agh'];?></td>
					<td><?php echo $row['numero_iden_per'];?></td>
                    <td><?php echo $row['nombre'];?></td>
                    <td><?php echo $row['observacion_agc'];?></td>
                    <td><?php echo $row['estado_agc'];?></td>
					<td><?php echo $row['nombre_profesional'];?></td>
					
					
					
					<td style="text-align: center;">
						<?php						
							/*if(is_null($row['id_aten'])){
								if($row['estado_agc']=='Cancelada' or $row['estado_agc']=='Inasistencia'){
									?>
									<span class="btn btn-secondary btn.sm" title="Registrar Inasistencia">
										<span class="fas fa-calendar-times"></span>
									</span>
									<?php
								}
								else{
									?>
									<span class="btn btn-danger btn.sm" title="Registrar Inasistencia" onclick="inasistencia('<?php echo $row[0];?>','<?php echo $row[3];?>')">
										<span class="fas fa-calendar-times"></span>
									</span>
									<?php
								}
								?>
								

								<span class="btn btn-secondary btn.sm" title="Imprimir Historia">
									<span class="fas fa-paste"></span>
								</span>
								<span class="btn btn-secondary btn.sm" title="Imprimir Formula">		
									<span class="fas fa-pills"></span>
								</span>
								<span class="btn btn-secondary btn.sm" title="Imprimir Ordenes">		
									<span class="fas fa-pills"></span>
								</span>
								<span class="btn btn-secondary btn.sm" title="Imprimir Procedimientos">		
									<i class="fas fa-th-list"></i>
								</span>
								<?php
							}
							else{
								?>
								<span class="btn btn-secondary btn.sm" title="Registrar Inasistencia"">
									<span class="fas fa-calendar-times"></span>
								</span>

								<span class="btn btn-success btn.sm" title="Imprimir Historia" onclick="imprimir('<?php echo $row[6]?>')">
									<span class="fas fa-paste"></span>
								</span>
								<span class="btn btn-success btn.sm" title="Imprimir Formula" onclick="imprimirformula('<?php echo $row[6]?>')">									
									<span class="fas fa-pills"></span>
								</span>
								<span class="btn btn-success btn.sm" title="Imprimir Ordenes" onclick="imprimirorden('<?php echo $row[6]?>')">									
									<i class="fas fa-file-invoice"></i>
								</span>
								<span class="btn btn-success btn.sm" title="Imprimir Procedimientos" onclick="imprimirproced('<?php echo $row[6]?>')">
									<i class="fas fa-th-list"></i>
								</span>
								<?php
							}*/
						?>
                        <span class="btn btn-success btn.sm" title="Registrar Nueva Nota" 
                        onclick="registrarNota('<?php echo $row['id_agc']; ?>','<?php echo $row['nombre']; ?>')"
>
									<span class="fas fa-briefcase-medical"></span>
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
		//$('#tablaagenda').DataTable();
	} );
</script>
