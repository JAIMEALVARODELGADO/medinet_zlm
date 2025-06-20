    //id_factura = "<?php echo $id_factura; ?>";

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
		//alert(url);
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
				'<span class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar" title="Editar" onclick="editarUs(' + usuario.id_usuario + ')">' +
					'<span class="far fa-edit"></span>'+
				'</span>' +
                '</td>' +							                
                '</tr>';
            
            $('#tablaRipsUs tbody').append(fila);
        });
    }

	function editarUs(id_usuario) {
        // Validar el parámetro
        if (!id_usuario) {
            alert('ID de usuario no válido');
            return;
        }
            
        // Cargar los datos del usuario específico        
        var url = "procesos/rips_procesos.php?id_usuario=" + id_usuario + "&opcion=traerUsPorId";
        
        fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {            
            
            // Verificar si hay error en la respuesta del servidor
            if (data.error) {
                throw new Error(data.mensaje || 'Error desconocido del servidor');
            }
            
            // Validación correcta para tu estructura JSON
            if (!data.id_usuario) {
                throw new Error('Usuario no encontrado');
            }
            
            // Rellenar el formulario de edición
            rellenarFormularioEdicion(data);
            
        })
        .catch(error => {
            console.error('Error al cargar usuario:', error);
            alert('Error al cargar los datos del usuario: ' + error.message);
        });
    }
    
    // Función auxiliar para rellenar el formulario
    function rellenarFormularioEdicion(usuario) {
        console.log('Usuario a editar:', usuario);
        
        // Rellenar los campos del formulario con los datos del JSON
        $('#edit_tipo_documento').val(usuario.tipo_documento || '');
        $('#edit_numdocumento').val(usuario.numdocumento || '');
        $('#edit_nombre_completo').val(usuario.nombre_completo || '');
        $('#edit_tipousuario').val(usuario.tipousuario || '');
        $('#edit_fechanacimiento').val(usuario.fechanacimiento || '');
        $('#edit_codsexo').val(usuario.codsexo || '');
        $('#edit_codpaisresidencia').val(usuario.codpaisresidencia || '');
        $('#edit_codmunicipioresidencia').val(usuario.codmunicipioresidencia || '');
        $('#edit_codzonaresidencia').val(usuario.codzonaresidencia || '');
        $('#edit_incapacidad').val(usuario.incapacidad || '');
        $('#edit_codpaisorigen').val(usuario.codpaisorigen || '');
        
        // Guardar el ID para la actualización
        $('#edit_id_usuario').val(usuario.id_usuario || '');
        
        console.log('Formulario rellenado correctamente');
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