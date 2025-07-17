function cerrar(){    
    $("#tablaDataRips").empty();
}

function ripsUs(){    
    $("#tablaDataRips").load("tablaRipsUs.php?id_factura="+id_factura);
}
function ripsAc(){    
    $("#tablaDataRips").load("tablaNRAC.php?id_factura="+id_factura);
}
function ripsAp(){    
    $("#tablaDataRips").load("tablaNRAP.php?id_factura="+id_factura);
}
function ripsJs(){    
    $("#tablaDataRips").load("tablaNRJs.php?id_factura="+id_factura);
}

$(document).ready(function() {		
    //alert("Aqui estoy");    
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

// Función auxiliar para rellenar el formulario
/*function llenarFormularioConsulta(consulta) {    
    // Rellenar los campos del formulario con los datos del JSON
    $('#fechainicioatencion').val(consulta.fechainicioatencion || '');
    $('#numautorizacion').val(consulta.numautorizacion || '');
    $('#codconsulta').val(consulta.codconsulta || '');
    $('#codconsu').val(consulta.codconsulta || '');
    $('#modalidadgruposervicio').val(consulta.modalidadgruposervicio || '');
    $('#gruposervicio').val(consulta.gruposervicio || '');
    $('#codservicio').val(consulta.codservicio || '');
    $('#finalidadtecnologiasalud').val(consulta.finalidadtecnologiasalud || '');
    $('#causamotivoatencion').val(consulta.causamotivoatencion || '');
    $('#coddiagnosticoprincipal').val(consulta.coddiagnosticoprincipal || '');
    $('#dxprinc').val(consulta.coddiagnosticoprincipal || '');    
    $('#coddiagnosticorelacionado1').val(consulta.coddiagnosticorelacionado1 || '');
    $('#dxrel1').val(consulta.coddiagnosticorelacionado1 || '');    
    $('#coddiagnosticorelacionado2').val(consulta.coddiagnosticorelacionado2 || '');
    $('#dxrel2').val(consulta.coddiagnosticorelacionado2 || '');    
    $('#coddiagnosticorelacionado3').val(consulta.coddiagnosticorelacionado3 || '');
    $('#dxrel3').val(consulta.coddiagnosticorelacionado3 || '');    
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
        inicializarAutocomplete();
    }, 100);
}*/

/*function inicializarAutocomplete() {    
    $("#dxprinc").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#dxprinc").result(function(event, data, formatted) {
        $("#coddiagnosticoprincipal").val(data[1]);
    });

    $("#dxrel1").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#dxrel1").result(function(event, data, formatted) {
        $("#coddiagnosticorelacionado1").val(data[1]);
    });

    $("#dxrel2").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#dxrel2").result(function(event, data, formatted) {
        $("#coddiagnosticorelacionado2").val(data[1]);
    });

    $("#dxrel3").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#dxrel3").result(function(event, data, formatted) {
        $("#coddiagnosticorelacionado3").val(data[1]);
    });

    $("#codconsu").autocomplete("procesos/autocomp_cups2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#codconsu").result(function(event, data, formatted) {
        $("#codconsulta").val(data[1]);
    });

    //Procedimientos
    $("#codproced").autocomplete("procesos/autocomp_cups2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#codproced").result(function(event, data, formatted) {
        $("#codprocedimiento").val(data[1]);
    });

    $("#dxrel").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#dxrel").result(function(event, data, formatted) {
        $("#coddiagnosticorelacionado").val(data[1]);
    });

    $("#compli").autocomplete("procesos/autocomp_cie2.php", {
        width: 500,
        matchContains: false,
        mustMatch: false,
        selectFirst: false
    });
    
    $("#compli").result(function(event, data, formatted) {
        $("#codcomplicacion").val(data[1]);
    });
}

function guardarConsulta() {
    // Recopilar los datos del formulario
    if(document.getElementById("dxprinc").value == ""){
        document.getElementById("coddiagnosticoprincipal").value = "";
    }
    if(document.getElementById("dxrel1").value == ""){
        document.getElementById("coddiagnosticorelacionado1").value = "";
    }
    if(document.getElementById("dxrel2").value == ""){
        document.getElementById("coddiagnosticorelacionado2").value = "";
    }
    if(document.getElementById("dxrel3").value == ""){
        document.getElementById("coddiagnosticorelacionado3").value = "";
    }
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
        cerrarModal();
        cargarConsultas(); // Recargar la tabla
    })
    .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al actualizar la consulta');
    });
}

function cerrarModal() {
    document.querySelector('[data-dismiss="modal"]').click();    
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
}*/

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

/*function cargarModalidadGrupoProced(){
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
        llenarModalidadGrupoProced(data);
        
    })
    .catch(error => {
        console.error('Error al cargar modalidad grupo:', error);
        alert('Error al cargar los datos de modalidad grupo: ' + error.message);
    });
}

function llenarModalidadGrupoProced(data){
    // Verificar si el elemento existe
    var select = document.getElementById('modalidadgruposerviciotecsal');
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

function cargarGrupoServicioProced(){
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
        llenarGrupoServicioProced(data);
        
    })
    .catch(error => {
        console.error('Error al cargar grupo servicio:', error);
        alert('Error al cargar los datos de grupo servicio: ' + error.message);
    });
}

function llenarGrupoServicioProced(data){
    // Verificar si el elemento existe
    var select = document.getElementById('gruposervicios');
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

function cargarServiciosProced(){
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
        llenarServiciosProced(data);
        
    })
    .catch(error => {
        console.error('Error al cargar grupo servicio:', error);
        alert('Error al cargar los datos de grupo servicio: ' + error.message);
    });
}

function llenarServiciosProced(data){    
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

function cargarFinalidadesProced(){
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
        llenarFinalidadesProced(data);
        
    })
    .catch(error => {
        console.error('Error al cargar finalidad:', error);
        alert('Error al cargar los datos de finalidad: ' + error.message);
    });
}

function llenarFinalidadesProced(data){
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

function cargarConceptoRecaudoProced(){
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
        llenarConceptoRecaudoProced(data);
        
    })
    .catch(error => {
        console.error('Error al cargar concepto recaudo:', error);
        alert('Error al cargar los datos de concepto recaudo: ' + error.message);
    });
}

function llenarConceptoRecaudoProced(data){
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
}*/

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