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
		//console.log(url);
		fetchOptions={
			method: 'GET',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}
		fetch(url, fetchOptions)
		.then(response => response.text())
		.then(data => {			
			cargarUs();
		})
		.catch(error => {
			console.error('Error:', error);
		});
	}

	function cargarUs() {
		var url = "procesos/rips_procesos.php?id_factura=" + id_factura
		+"&opcion=traerUs";
		console.log(url);
		fetchOptions={
			method: 'GET',
			headers: {				
				'Content-Type': 'application/json' 
			}
		}
		fetch(url, fetchOptions)		
		.then(response => response.json())
		.then(data => {			
            mostrarUs(data);
		})
		.catch(error => {
			console.error('Error:', error);
		});
	}

	function mostrarUs(usuarios){
        // Limpiar el tbody de la tabla
        $('#tablaRipsUs tbody').empty();
        
        // Verificar si hay datos
        if (!usuarios || usuarios.length === 0) {
            $('#tablaRipsUs tbody').append('<tr><td colspan="9" class="text-center">No hay usuarios para mostrar</td></tr>');
            return;
        }
        
        // Recorrer los usuarios y crear las filas
        usuarios.forEach(function(usuario) {
            var fila = '<tr>' +
                '<td>' + (usuario.tipo_documento || '') + '</td>' +
                '<td>' + (usuario.numdocumento || '') + '</td>' +
                '<td>' + (usuario.nombre_completo || 'N/A') + '</td>' + // Nota: este campo no viene en tu JSON actual
                '<td>' + (usuario.tipousuario || '') + '</td>' +
                '<td>' + (usuario.fechanacimiento || '') + '</td>' +
                '<td>' + (usuario.codsexo || '') + '</td>' +
                '<td>' +
				'<span class="btn btn-warning btn.sm" data-toggle="modal" data-target="#modalEditar" title="Editar" onclick="editarUs()">'+
					'<span class="far fa-edit"></span>'+
				'</span>' +
				'</td>' +
				'<td>' +				                
                '</tr>';
            
            $('#tablaRipsUs tbody').append(fila);
        });
    }

	function editarUs(id_usuario) {
		// Cargar los datos del usuario específico
		var url = "procesos/rips_procesos.php?id_usuario=" + id_usuario + "&opcion=traerUsuario";
		
		fetch(url)
		.then(response => response.json())
		.then(data => {
			if(data && data.length > 0) {
				var usuario = data[0];
				// Llenar el formulario del modal con los datos
				$('#edit_id_usuario').val(usuario.id_usuario);
				$('#edit_tipo_documento').val(usuario.tipo_documento);
				$('#edit_numdocumento').val(usuario.numdocumento);
				$('#edit_tipousuario').val(usuario.tipousuario);
				$('#edit_fechanacimiento').val(usuario.fechanacimiento);
				$('#edit_codsexo').val(usuario.codsexo);
				$('#edit_codpaisresidencia').val(usuario.codpaisresidencia);
				$('#edit_codmunicipioresidencia').val(usuario.codmunicipioresidencia);
				$('#edit_codzonaresidencia').val(usuario.codzonaresidencia);
				$('#edit_incapacidad').val(usuario.incapacidad);
				$('#edit_codpaisorigen').val(usuario.codpaisorigen);
			}
		})
		.catch(error => {
			console.error('Error al cargar usuario:', error);
			alert('Error al cargar los datos del usuario');
		});
	}

	function guardarUsuario() {
		// Recopilar los datos del formulario
		var formData = new FormData();
		formData.append('opcion', 'actualizarUsuario');
		formData.append('id_usuario', $('#edit_id_usuario').val());
		formData.append('tipo_documento', $('#edit_tipo_documento').val());
		formData.append('numdocumento', $('#edit_numdocumento').val());
		formData.append('tipousuario', $('#edit_tipousuario').val());
		formData.append('fechanacimiento', $('#edit_fechanacimiento').val());
		formData.append('codsexo', $('#edit_codsexo').val());
		formData.append('codpaisresidencia', $('#edit_codpaisresidencia').val());
		formData.append('codmunicipioresidencia', $('#edit_codmunicipioresidencia').val());
		formData.append('codzonaresidencia', $('#edit_codzonaresidencia').val());
		formData.append('incapacidad', $('#edit_incapacidad').val());
		formData.append('codpaisorigen', $('#edit_codpaisorigen').val());

		fetch('procesos/rips_procesos.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.text())
		.then(data => {
			alert('Usuario actualizado correctamente');
			$('#modalEditar').modal('hide');
			cargarUs(); // Recargar la tabla
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Error al actualizar el usuario');
		});
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

	                        <!--<div id="tablaDatafactura"></div>-->
	                    </div>
	                    
	                </div>
	            </div>
	        </div>
	    </div>		
	</div>

<!-- Modal para Editar Usuario -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Usuario RIPS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <input type="hidden" id="edit_id_usuario" name="id_usuario">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tipo_documento">Tipo Documento:</label>
                                <select class="form-control" id="edit_tipo_documento" name="tipo_documento" required>
                                    <option value="">Seleccione...</option>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                    <option value="RC">Registro Civil</option>
                                    <option value="PA">Pasaporte</option>
                                    <option value="CE">Cédula de Extranjería</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_numdocumento">Número Documento:</label>
                                <input type="text" class="form-control" id="edit_numdocumento" name="numdocumento" maxlength="20" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tipousuario">Tipo Usuario:</label>
                                <select class="form-control" id="edit_tipousuario" name="tipousuario" required>
                                    <option value="">Seleccione...</option>
                                    <option value="01">Contributivo</option>
                                    <option value="02">Subsidiado</option>
                                    <option value="03">Vinculado</option>
                                    <option value="04">Particular</option>
                                    <option value="05">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_fechanacimiento">Fecha Nacimiento:</label>
                                <input type="date" class="form-control" id="edit_fechanacimiento" name="fechanacimiento" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codsexo">Sexo:</label>
                                <select class="form-control" id="edit_codsexo" name="codsexo" required>
                                    <option value="">Seleccione...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codpaisresidencia">Código País Residencia:</label>
                                <input type="text" class="form-control" id="edit_codpaisresidencia" name="codpaisresidencia" maxlength="3" value="170" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codmunicipioresidencia">Código Municipio:</label>
                                <input type="text" class="form-control" id="edit_codmunicipioresidencia" name="codmunicipioresidencia" maxlength="5" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codzonaresidencia">Zona Residencia:</label>
                                <select class="form-control" id="edit_codzonaresidencia" name="codzonaresidencia" required>
                                    <option value="">Seleccione...</option>
                                    <option value="01">Rural</option>
                                    <option value="02">Urbana</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_incapacidad">Incapacidad:</label>
                                <select class="form-control" id="edit_incapacidad" name="incapacidad" required>
                                    <option value="">Seleccione...</option>
                                    <option value="NO">No</option>
                                    <option value="SI">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_codpaisorigen">Código País Origen:</label>
                                <input type="text" class="form-control" id="edit_codpaisorigen" name="codpaisorigen" maxlength="3" value="170" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>