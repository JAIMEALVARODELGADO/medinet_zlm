function cerrar(){
    alert("Cerrar ventana de Rips Js");
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
function ripsAt(){    
    $("#tablaDataRips").load("tablaNRAT.php?id_factura="+id_factura);
}

function traerRipsJs(id_factura){
    alert("Traer Rips Js"+id_factura);
}

$(document).ready(function() {
    generarRipsJson(id_factura);
});

function generarRipsJson() {    
    var url = "procesos/rips_procesos.php?id_factura=" + id_factura + "&opcion=traerRipsJs";
    alert(url);
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

function mostrarRipsJson(ripsJs){
    console.log(ripsJs);
    // Limpiar el tbody de la tabla
    /*$('#tablaRips tbody').empty();
    
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
    });*/
}
