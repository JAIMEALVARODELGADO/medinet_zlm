function cerrar(){		
    $("#tablaDataRips").empty();
}

function ripsUs(){    
    $("#tablaDataRips").load("tablaRipsUs.php?id_factura="+id_factura);
}

function ripsAp(){    
    $("#tablaDataRips").load("tablaNRAP.php?id_factura="+id_factura);
}

$(document).ready(function() {		
    //alert("Aqui estoy");    
    cargarConsultas();
    cargarModalidadGrupo();
    cargarGrupoServicio();
    cargarServicios();
    cargarFinalidades();
    cargarCausaExterna();
    cargarTipoDiagnostico();
    cargarConceptoRecaudo();
});

function cargarConsultas() {    
    
    var url = "procesos/rips_procesos.php?id_factura=" + id_factura + "&opcion=traerConsultas";
    
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
            mostrarConsultas(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function mostrarConsultas(consultas){
    // Limpiar el tbody de la tabla     
    //console.log('Datos recibidos:', consultas);
    $('#tablaRips tbody').empty();
    
    // Verificar si hay datos
    if (!consultas || consultas.length === 0) {
        $('#tablaRips tbody').append('<tr><td colspan="9" class="text-center">No hay consultas para mostrar</td></tr>');
        return;
    }
    
    // Recorrer las consultas y crear las filas
    consultas.forEach(function(consulta) {            
        var fila = '<tr>' +
            '<td>' + (consulta.fechainicioatencion || '') + '</td>' +
            '<td>' + (consulta.numautorizacion || '') + '</td>' +
            '<td>' + (consulta.codconsulta || '') + '</td>' +
            '<td>' + (consulta.finalidadtecnologiasalud || '') + '</td>' +
            '<td>' + (consulta.coddiagnosticoprincipal || '') + '</td>' +
            '<td>' + (consulta.vrservicio || '') + '</td>' +
            '<td>' +
            '<span class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar" title="Editar" onclick="editarConsulta(' + consulta.id_consulta + ')">' +
                '<span class="far fa-edit"></span>'+
            '</span>' +
            '</td>' +							                
            '</tr>';
        
        $('#tablaRips tbody').append(fila);
    });
}

function editarConsulta(id_consulta) {
    // Validar el parámetro
    if (!id_consulta) {
        alert('ID de consulta no válido');
        return;
    }
        
    // Cargar los datos de la consulta específica        
    var url = "procesos/rips_procesos.php?id_consulta=" + id_consulta + "&opcion=traerConsultaPorId";
    
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
        if (!data.id_consulta) {
            throw new Error('Consulta no encontrada');
        }
        
        // Rellenar el formulario de edición                
        llenarFormularioConsulta(data);
        
    })
    .catch(error => {
        console.error('Error al cargar consulta:', error);
        alert('Error al cargar los datos de la consulta: ' + error.message);
    });
}

// Función auxiliar para rellenar el formulario
function llenarFormularioConsulta(consulta) {
    // Rellenar los campos del formulario con los datos del JSON
    $('#fechainicioatencion').val(consulta.fechainicioatencion || '');
    $('#numautorizacion').val(consulta.numautorizacion || '');
    $('#codconsulta').val(consulta.codconsulta || '');
    $('#modalidadgruposervicio').val(consulta.modalidadgruposervicio || '');
    $('#gruposervicio').val(consulta.gruposervicio || '');
    $('#codservicio').val(consulta.codservicio || '');
    $('#finalidadtecnologiasalud').val(consulta.finalidadtecnologiasalud || '');
    $('#causamotivoatencion').val(consulta.causamotivoatencion || '');
    $('#coddiagnosticoprincipal').val(consulta.coddiagnosticoprincipal || '');
    $('#dxprinc').val(consulta.coddiagnosticoprincipal || '');    
    $('#coddiagnosticorelacionado1').val(consulta.coddiagnosticorelacionado1 || '');
    $('#coddiagnosticorelacionado2').val(consulta.coddiagnosticorelacionado2 || '');
    $('#coddiagnosticorelacionado3').val(consulta.coddiagnosticorelacionado3 || '');
    $('#tipodiagnosticoprincipal').val(consulta.tipodiagnosticoprincipal || '');
    $('#vrservicio').val(consulta.vrservicio || '');
    $('#conceptorecaudo').val(consulta.conceptorecaudo || '');
    $('#valorpagomoderador').val(consulta.valorpagomoderador || '');
    $('#numfevpagomoderador').val(consulta.numfevpagomoderador || '');
    $('#id_detalle').val(consulta.id_detalle || '');
    
    // Guardar el ID para la actualización
    $('#id_consulta').val(consulta.id_consulta || '');
    
    //console.log('Formulario de consulta rellenado correctamente');

    setTimeout(function() {        
        inicializarAutocompleteDx();
    }, 100);
}

function inicializarAutocompleteDx() {    
    $("#dxprinc").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#dxprinc").result(function(event, data, formatted) {
        $("#coddiagnosticoprincipal").val(data[1]);
    });
}

function guardarConsulta() {
    // Recopilar los datos del formulario
    var formData = new FormData();
    formData.append('opcion', 'guardarConsulta');
    formData.append('id_consulta', $('#id_consulta').val());
    formData.append('id_detalle', $('#id_detalle').val());
    formData.append('fechainicioatencion', $('#fechainicioatencion').val());
    formData.append('numautorizacion', $('#numautorizacion').val());
    formData.append('codconsulta', $('#codconsulta').val());
    formData.append('modalidadgruposervicio', $('#modalidadgruposervicio').val());
    formData.append('gruposervicio', $('#gruposervicio').val());
    formData.append('codservicio', $('#codservicio').val());
    formData.append('finalidadtecnologiasalud', $('#finalidadtecnologiasalud').val());
    formData.append('causamotivoatencion', $('#causamotivoatencion').val());
    formData.append('coddiagnosticoprincipal', $('#coddiagnosticoprincipal').val());    
    formData.append('coddiagnosticorelacionado1', $('#coddiagnosticorelacionado1').val());
    formData.append('coddiagnosticorelacionado2', $('#coddiagnosticorelacionado2').val());
    formData.append('coddiagnosticorelacionado3', $('#coddiagnosticorelacionado3').val());
    formData.append('tipodiagnosticoprincipal', $('#tipodiagnosticoprincipal').val());
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
        //$('#modalEditar').modal('hide');
        cargarConsultas(); // Recargar la tabla
    })
    .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al actualizar la consulta');
    });
}

function cargarModalidadGrupo(){
    // Cargar los datos de modalidad grupo servicio
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=25"+ 
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
        llenarModalidadGrupo(data);
        
    })
    .catch(error => {
        console.error('Error al cargar modalidad grupo:', error);
        alert('Error al cargar los datos de modalidad grupo: ' + error.message);
    });
}

function llenarModalidadGrupo(data){
    // Verificar si el elemento existe
    var select = document.getElementById('modalidadgruposervicio');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione modalidad';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select modalidadgruposervicio no existe en el DOM');
    }
}

function cargarGrupoServicio(){
    // Cargar los datos de grupo servicio
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=26"+ 
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
        
        //Llenar select de grupo servicio
        llenarGrupoServicio(data);
        
    })
    .catch(error => {
        console.error('Error al cargar grupo servicio:', error);
        alert('Error al cargar los datos de grupo servicio: ' + error.message);
    });
}

function llenarGrupoServicio(data){
    // Verificar si el elemento existe
    var select = document.getElementById('gruposervicio');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione grupo servicio';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select gruposervicio no existe en el DOM');
    }
}

function cargarServicios(){
    // Cargar los datos de servicios    
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=27"+ 
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
        
        //Llenar select de grupo servicio        
        llenarServicios(data);
        
    })
    .catch(error => {
        console.error('Error al cargar grupo servicio:', error);
        alert('Error al cargar los datos de grupo servicio: ' + error.message);
    });
}

function llenarServicios(data){    
    // Verificar si el elemento existe
    var select = document.getElementById('codservicio');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione el servicio';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select servicio no existe en el DOM');
    }
}

function cargarFinalidades(){
    // Cargar los datos de finalidad
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=11"+ 
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
        
        //Llenar select de finalidad
        llenarFinalidades(data);
        
    })
    .catch(error => {
        console.error('Error al cargar finalidad:', error);
        alert('Error al cargar los datos de finalidad: ' + error.message);
    });
}

function llenarFinalidades(data){
    // Verificar si el elemento existe
    var select = document.getElementById('finalidadtecnologiasalud');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione finalidad';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select finalidadtecnologiasalud no existe en el DOM');
    }
}

function cargarCausaExterna(){
    // Cargar los datos de causa motivo
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=12"+ 
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
        
        //Llenar select de causa motivo        
        llenarCausaExterna(data);
        
    })
    .catch(error => {
        console.error('Error al cargar causa motivo:', error);
        alert('Error al cargar los datos de causa motivo: ' + error.message);
    });
}

function llenarCausaExterna(data){
    // Verificar si el elemento existe
    var select = document.getElementById('causamotivoatencion');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione causa externa';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select causamotivoatencion no existe en el DOM');
    }
}

function cargarTipoDiagnostico(){
    // Cargar los datos de tipo diagnostico
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=13"+ 
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
        
        //Llenar select de tipo diagnostico
        llenarTipoDiagnostico(data);
        
    })
    .catch(error => {
        console.error('Error al cargar tipo diagnostico:', error);
        alert('Error al cargar los datos de tipo diagnostico: ' + error.message);
    });
}

function llenarTipoDiagnostico(data){
    // Verificar si el elemento existe
    var select = document.getElementById('tipodiagnosticoprincipal');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione tipo diagnóstico';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select tipodiagnosticoprincipal no existe en el DOM');
    }
}

function cargarConceptoRecaudo(){
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

