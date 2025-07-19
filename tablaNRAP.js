function cerrar(){		
    $("#tablaDataRips").empty();
}

function ripsUs(){    
    $("#tablaDataRips").load("tablaRipsUs.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}
function ripsAc(){    
    $("#tablaDataRips").load("tablaNRAC.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}
function ripsAt(){    
    $("#tablaDataRips").load("tablaNRAT.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}
function ripsJs(){    
    $("#tablaDataRips").load("tablaNRJs.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}

$(document).ready(function() {		
    //alert("Aqui estoy");    
    cargarProcedimientos();
    cargarVia();
    cargarModalidadGrupoProced();
    cargarGrupoServicioProced();
    cargarServiciosProced();
    cargarFinalidadesProced();    
    cargarConceptoRecaudoProced();
});

function cargarProcedimientos() {    
    var url = "procesos/rips_procesos.php?id_factura=" + id_factura + "&opcion=traerProcedimientos";
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
            mostrarProcedimientos(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function mostrarProcedimientos(procedimientos){
    // Limpiar el tbody de la tabla
    $('#tablaRips tbody').empty();
    
    // Verificar si hay datos
    if (!procedimientos || procedimientos.length === 0) {
        $('#tablaRips tbody').append('<tr><td colspan="9" class="text-center">No hay consultas para mostrar</td></tr>');
        return;
    }
    
    // Recorrer las consultas y crear las filas
    procedimientos.forEach(function(procedimiento) { 
        var fila = '<tr>' +
            '<td>' + (procedimiento.fechainicioatencion || '') + '</td>' +
            '<td>' + (procedimiento.numautorizacion || '') + '</td>' +
            '<td>' + (procedimiento.idmipres || '') + '</td>' +
            '<td>' + (procedimiento.codprocedimiento || '') + '</td>' +
            '<td>' + (procedimiento.finalidadtecnologiasalud || '') + '</td>' +
            '<td>' + (procedimiento.coddiagnosticoprincipal || '') + '</td>' +
            '<td>' + (procedimiento.vrservicio || '') + '</td>' +
            '<td>' +
            '<span class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar" title="Editar" onclick="editarProcedimiento(' + procedimiento.id_procedimiento + ')">' +
                '<span class="far fa-edit"></span>'+
            '</span>' +
            '</td>' +							                
            '</tr>';
        
        $('#tablaRips tbody').append(fila);
    });
}

function editarProcedimiento(id_procedimiento) {    
    // Validar el parámetro
    if (!id_procedimiento) {
        alert('ID del procedimiento no válido');
        return;
    }
    // Cargar los datos de la consulta específica        
    var url = "procesos/rips_procesos.php?id_procedimiento=" + id_procedimiento + "&opcion=traerProcedimientoPorId";
    
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
        if (!data.id_procedimiento) {
            throw new Error('Procedimiento no encontrado');
        }
        
        // Rellenar el formulario de edición                
        llenarFormularioProcedimiento(data);
        
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

function inicializarAutocomplete() {    
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

// Función auxiliar para rellenar el formulario
function llenarFormularioProcedimiento(procedimiento) {
    //console.log(procedimiento)
    // Rellenar los campos del formulario con los datos del JSON
    $('#fechainicioatencion').val(procedimiento.fechainicioatencion || '');
    $('#idmipres').val(procedimiento.idmipres || '');
    $('#numautorizacion').val(procedimiento.numautorizacion || '');
    $('#codprocedimiento').val(procedimiento.codprocedimiento || '');
    $('#codproced').val(procedimiento.codprocedimiento || '');
    $('#viaingresoserviciosalud').val(procedimiento.viaingresoserviciosalud || '');
    $('#modalidadgruposerviciotecsal').val(procedimiento.modalidadgruposerviciotecsal || '');
    $('#gruposervicios').val(procedimiento.gruposervicios || '');
    $('#codservicio').val(procedimiento.codservicio || '');
    $('#finalidadtecnologiasalud').val(procedimiento.finalidadtecnologiasalud || '');    
    $('#coddiagnosticoprincipal').val(procedimiento.coddiagnosticoprincipal || '');
    $('#dxprinc').val(procedimiento.coddiagnosticoprincipal || '');    
    $('#coddiagnosticorelacionado').val(procedimiento.coddiagnosticorelacionado || '');
    $('#dxrel1').val(procedimiento.coddiagnosticorelacionado || '');    
    $('#codcomplicacion').val(procedimiento.codcomplicacion || '');
    $('#compli').val(procedimiento.codcomplicacion || '');    
    $('#vrservicio').val(procedimiento.vrservicio || '');
    $('#conceptorecaudo').val(procedimiento.conceptorecaudo || '');
    $('#valorpagomoderador').val(procedimiento.valorpagomoderador || '');
    $('#numfevpagomoderador').val(procedimiento.numfevpagomoderador || '');
    $('#id_detfac').val(procedimiento.id_detfac || '');    
    // Guardar el ID para la actualización
    $('#id_procedimiento').val(procedimiento.id_procedimiento || '');
    
    //console.logs('Formulario de consulta rellenado correctamente');

    setTimeout(function() {        
        inicializarAutocomplete();
    }, 100);
}

function cargarVia(){
    // Cargar los datos de las vias de ingresos
    var url = "procesos/rips_procesos.php?"+
        "id_grupo=29"+ 
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
        llenarVias(data);
        
    })
    .catch(error => {
        console.error('Error al cargar modalidad grupo:', error);
        alert('Error al cargar los datos de modalidad grupo: ' + error.message);
    });
}

function llenarVias(data){
    // Verificar si el elemento existe
    var select = document.getElementById('viaingresoserviciosalud');
    if (select) {
        // Limpiar las opciones existentes
        select.innerHTML = '';
        
        // Agregar una opción por defecto
        var option = document.createElement('option');
        option.value = '';
        option.textContent = 'Seleccione Vía de ingreso';
        select.appendChild(option);
        
        // Recorrer los datos y crear las opciones
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.valor_det;
            option.textContent = item.descripcion_det;
            select.appendChild(option);
        });
    } else {
        console.error('El elemento select via de ingreso no existe en el DOM');
    }
}

function cargarModalidadGrupoProced(){
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
}

function guardarProcedimiento() {    
    // Recopilar los datos del formulario    
    if(document.getElementById("dxprinc").value == ""){
        document.getElementById("coddiagnosticoprincipal").value = "";
    }
    if(document.getElementById("dxrel").value == ""){
        document.getElementById("coddiagnosticorelacionado").value = "";
    }
    if(document.getElementById("compli").value == ""){
        document.getElementById("codcomplicacion").value = "";
    }
    var formData = new FormData();
    formData.append('opcion', 'guardarProcedimiento');
    formData.append('id_procedimiento', $('#id_procedimiento').val());
    formData.append('id_detfac', $('#id_detfac').val());
    formData.append('fechainicioatencion', $('#fechainicioatencion').val());
    formData.append('idmipres', $('#idmipres').val());
    formData.append('numautorizacion', $('#numautorizacion').val());    
    formData.append('codprocedimiento', $('#codprocedimiento').val());    
    formData.append('viaingresoserviciosalud', $('#viaingresoserviciosalud').val());
    formData.append('modalidadgruposerviciotecsal', $('#modalidadgruposerviciotecsal').val());
    formData.append('gruposervicios', $('#gruposervicios').val());
    formData.append('codservicio', $('#codservicio').val());
    formData.append('finalidadtecnologiasalud', $('#finalidadtecnologiasalud').val());
    formData.append('coddiagnosticoprincipal', $('#coddiagnosticoprincipal').val());
    formData.append('coddiagnosticorelacionado', $('#coddiagnosticorelacionado').val());
    formData.append('codcomplicacion', $('#codcomplicacion').val());
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
        cargarProcedimientos(); // Recargar la tabla
    })
    .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al actualizar la consulta');
    });
}