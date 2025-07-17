//id_factura = "<?php echo $id_factura; ?>";

function cerrar(){		
    $("#tablaDataRips").empty();
}
function ripsAc(){				
    $("#tablaDataRips").load("tablaNRAC.php?id_factura="+id_factura);
}
function ripsAp(){
    $("#tablaDataRips").load("tablaNRAP.php?id_factura="+id_factura);
}
function ripsAt(){    
    $("#tablaDataRips").load("tablaNRAT.php?id_factura="+id_factura);
}
function ripsJs(){      
    $("#tablaDataRips").load("tablaNRJs.php?id_factura="+id_factura);
}

$(document).ready(function() {		
    crearRips();
    cargarTpDocumento();
    cargarTpUsuario();
    cargarSexo();
    cargarMunicipios();
    cargarZona();
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
    $('#tipo_documento').val(usuario.tipo_documento || '');
    $('#numdocumento').val(usuario.numdocumento || '');    
    $('#tipousuario').val(usuario.tipousuario || '');
    $('#fechanacimiento').val(usuario.fechanacimiento || '');
    $('#codsexo').val(usuario.codsexo || '');
    $('#codpaisresidencia').val(usuario.codpaisresidencia || '');
    $('#codmunicipioresidencia').val(usuario.codmunicipioresidencia || '');
    $('#codzonaresidencia').val(usuario.codzonaresidencia || '');
    $('#incapacidad').val(usuario.incapacidad || '');
    $('#codpaisorigen').val(usuario.codpaisorigen || '');
    
    // Guardar el ID para la actualización
    $('#id_usuario').val(usuario.id_usuario || '');
    
    console.log('Formulario rellenado correctamente');
}

function guardarUsuario() {
    // Recopilar los datos del formulario
    var formData = new FormData();
    formData.append('opcion', 'guardarUsuario');
    formData.append('id_usuario', $('#id_usuario').val());
    formData.append('tipo_documento', $('#tipo_documento').val());
    formData.append('numdocumento', $('#numdocumento').val());
    formData.append('tipousuario', $('#tipousuario').val());
    formData.append('fechanacimiento', $('#fechanacimiento').val());
    formData.append('codsexo', $('#codsexo').val());
    formData.append('codpaisresidencia', $('#codpaisresidencia').val());
    formData.append('codmunicipioresidencia', $('#codmunicipioresidencia').val());
    formData.append('codzonaresidencia', $('#codzonaresidencia').val());
    formData.append('incapacidad', $('#incapacidad').val());
    formData.append('codpaisorigen', $('#codpaisorigen').val());

    fetch('procesos/rips_procesos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {        
        alertify.success(data);
        cerrarModal();
        cargarUs(); // Recargar la tabla
    })
    .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al actualizar el usuario');
    });
}

function cerrarModal() {
    $('#modalEditar').modal('hide');
    
    // Limpiar backdrop y restaurar body
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
    }, 300);
}

function cargarTpDocumento(){
    // Cargar los datos del tipo de documento
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=1"+ 
        "&opcion=traerDetalleGrupo";        

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
        
        //Llenar select de tipo de identificacion
        llenarTipoIdentificacion(data);
        
    })
    .catch(error => {
        console.error('Error al cargar usuario:', error);
        alert('Error al cargar los datos del usuario: ' + error.message);
    });
}

function llenarTipoIdentificacion(data){
    // Verificar si el elemento existe
    var select = document.getElementById('tipo_documento');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione un tipo de documento';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select no existe en el DOM');
    }
}

function cargarTpUsuario(){
    // Cargar los datos del tipo de usuario
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=3"+ 
        "&opcion=traerDetalleGrupo";        

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
        
        //Llenar select de tipo de identificacion
        llenarTipoUsuario(data);
        
    })
    .catch(error => {
        console.error('Error al cargar usuario:', error);
        alert('Error al cargar los datos del usuario: ' + error.message);
    });
}

function llenarTipoUsuario(data){
    // Verificar si el elemento existe
    var select = document.getElementById('tipousuario');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione el tipo de usuario';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select no existe en el DOM');
    }
}

function cargarSexo(){
    // Cargar los datos del sexo
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=2"+ 
        "&opcion=traerDetalleGrupo";        

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
        
        //Llenar select de tipo de identificacion
        llenarSexo(data);
        
    })
    .catch(error => {
        console.error('Error al cargar usuario:', error);
        alert('Error al cargar los datos del usuario: ' + error.message);
    });
}

function llenarSexo(data){
    // Verificar si el elemento existe
    var select = document.getElementById('codsexo');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione el sexo';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select no existe en el DOM');
    }
}

function cargarMunicipios(){
    // Cargar los datos de los municipios
    var url = "procesos/rips_procesos.php?"+        
        "&opcion=traerMunicipios";        

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
        
        //Llenar select de tipo de identificacion
        llenarMunicipios(data);
        
    })
    .catch(error => {
        console.error('Error al cargar usuario:', error);
        alert('Error al cargar los datos del usuario: ' + error.message);
    });
}

function llenarMunicipios(data){
    // Verificar si el elemento existe
    var select = document.getElementById('codmunicipioresidencia');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione el municipio';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.codigo_mun;
            option.textContent = item.nombre_mun;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select no existe en el DOM');
    }
}

function cargarZona(){
    // Cargar los datos de la zona de residencia
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=10"+ 
        "&opcion=traerDetalleGrupo";        

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
        
        //Llenar select de tipo de identificacion
        llenarZona(data);
        
    })
    .catch(error => {
        console.error('Error al cargar usuario:', error);
        alert('Error al cargar los datos del usuario: ' + error.message);
    });
}

function llenarZona(data){
    // Verificar si el elemento existe
    var select = document.getElementById('codzonaresidencia');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione la zona';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select no existe en el DOM');
    }
}