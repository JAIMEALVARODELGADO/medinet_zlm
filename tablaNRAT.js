function cerrar(){    
    $("#tablaDataRips").empty();
}

function ripsUs(){    
    $("#tablaDataRips").load("tablaRipsUs.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}
function ripsAc(){    
    $("#tablaDataRips").load("tablaNRAC.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}
function ripsAp(){    
    $("#tablaDataRips").load("tablaNRAP.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}
function ripsJs(){    
    $("#tablaDataRips").load("tablaNRJs.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}

$(document).ready(function() {    
    cargarServicios();
    cargarTipoOs();        
    cargarConceptoRecaudoOtServicio();
});

function cargarServicios() {    
    var url = "procesos/rips_procesos.php?id_factura=" + id_factura + "&opcion=traerOtServicios";
    const fetchOptions = {
        method: 'GET'        
    }
    
    fetch(url, fetchOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {            
            mostrarServicios(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function mostrarServicios(otServicios){    
    console.log(otServicios);
    // Limpiar el tbody de la tabla
    $('#tablaRips tbody').empty();
    
    // Verificar si hay datos
    if (!otServicios || otServicios.length === 0) {
        $('#tablaRips tbody').append('<tr><td colspan="9" class="text-center">No hay consultas para mostrar</td></tr>');
        return;
    }    
    
    // Recorrer las consultas y crear las filas
    otServicios.forEach(function(otServicios) { 
        var fila = '<tr>' +
            '<td>' + (otServicios.fechasuministrotecnologia || '') + '</td>' +
            '<td>' + (otServicios.numautorizacion || '') + '</td>' +
            '<td>' + (otServicios.idmipres || '') + '</td>' +
            '<td>' + (otServicios.codtecnologia || '') + '</td>' +
            '<td>' + (otServicios.nomtecnologia || '') + '</td>' +
            '<td>' + (otServicios.cantidados || '') + '</td>' +
            '<td>' + (otServicios.vrunitos || '') + '</td>' +            
            '<td>' + (otServicios.vrservicio || '') + '</td>' +
            '<td>' +
            '<span class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar" title="Editar" onclick="editarServicio(' + otServicios.id_otroservicio + ')">' +
                '<span class="far fa-edit"></span>'+
            '</span>' +
            '</td>' +	
            '</tr>';
        
        $('#tablaRips tbody').append(fila);
    });
}

function editarServicio(id_otroservicio) {
    // Validar el parámetro
    if (!id_otroservicio) {
        alert('ID del servicio no válido');
        return;
    }
    // Cargar los datos del servicio a editar
    var url = "procesos/rips_procesos.php?id_otroservicio=" + id_otroservicio + "&opcion=traerOtServicioPorId";
    
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
        if (!data.id_otroservicio) {
            throw new Error('Procedimiento no encontrado');
        }
        
        // Rellenar el formulario de edición                
        llenarFormularioServicio(data);
        
    })
    .catch(error => {
        console.error('Error al cargar el procedimiento:', error);
        alert('Error al cargar los datos del procedimiento: ' + error.message);
    });
}

function cargarConceptoRecaudoOtServicio(){
    // Cargar los datos de concepto recaudo
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=28"+ 
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
        
        //Llenar select de concepto recaudo
        llenarConceptoRecaudo(data);
        
    })
    .catch(error => {
        console.error('Error al cargar concepto recaudo:', error);
        alert('Error al cargar los datos de concepto recaudo: ' + error.message);
    });
}

function llenarConceptoRecaudo(data){
    // Verificar si el elemento existe
    var select = document.getElementById('conceptorecaudo');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione concepto';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select conceptorecaudo no existe en el DOM');
    }
}

// Función auxiliar para rellenar el formulario
function llenarFormularioServicio(servicio) {
    console.log(servicio)
    // Rellenar los campos del formulario con los datos del JSON
    $('#id_otroservicio').val(servicio.id_otroservicio || '');
    $('#fechasuministrotecnologia').val(servicio.fechasuministrotecnologia || '');
    $('#numautorizacion').val(servicio.numautorizacion || '');
    $('#idmipres').val(servicio.idmipres || '');
    $('#tipoos').val(servicio.tipoos || '');
    $('#codtecnologia').val(servicio.codtecnologia || '');
    $('#nomtecnologia').val(servicio.nomtecnologia || '');
    $('#cantidados').val(servicio.cantidados || '');
    $('#vrunitos').val(servicio.vrunitos || '');
    $('#vrservicio').val(servicio.vrservicio || '');
    $('#conceptorecaudo').val(servicio.conceptorecaudo || '');
    $('#valorpagomoderador').val(servicio.valorpagomoderador || '');
    $('#numfevpagomoderador').val(servicio.numfevpagomoderador || '');

    setTimeout(function() {        
        inicializarAutocomplete();
    }, 100);
}

function cargarTipoOs(){    
    // Cargar los datos de los tipos de otro servicio
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=30"+ 
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
        
        //Llenar select de modalidad grupo servicio
        llenarTiposOs(data);
        
    })
    .catch(error => {
        console.error('Error al cargar modalidad grupo:', error);
        alert('Error al cargar los datos de modalidad grupo: ' + error.message);
    });
}

function llenarTiposOs(data){
    // Verificar si el elemento existe
    var select = document.getElementById('tipoos');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione el tipo';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select tipo de otro servicio no existe en el DOM');
    }
}

function guardarServicio() {    
    // Recopilar los datos del formulario    
    var formData = new FormData();
    formData.append('opcion', 'guardarOtServicio');
    formData.append('id_otroservicio', $('#id_otroservicio').val());
    formData.append('fechasuministrotecnologia', $('#fechasuministrotecnologia').val());
    formData.append('numautorizacion', $('#numautorizacion').val());
    formData.append('idmipres', $('#idmipres').val());
    formData.append('tipoos', $('#tipoos').val());
    formData.append('codtecnologia', $('#codtecnologia').val());
    formData.append('nomtecnologia', $('#nomtecnologia').val());
    formData.append('cantidados', $('#cantidados').val());
    formData.append('vrunitos', $('#vrunitos').val());
    formData.append('vrservicio', $('#vrservicio').val());
    formData.append('conceptorecaudo', $('#conceptorecaudo').val());
    formData.append('valorpagomoderador', $('#valorpagomoderador').val());
    formData.append('numfevpagomoderador', $('#numfevpagomoderador').val());
    
    fetch('procesos/rips_procesos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {        
        alertify.success(data);
        cerrarModal();
        cargarServicios(); // Recargar la tabla
    })
    .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al actualizar el servicio');
    });
}