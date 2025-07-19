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
function ripsAt(){    
    $("#tablaDataRips").load("tablaNRAT.php?id_factura="+id_factura+"&numero_fac="+numero_fac);
}

/*function traerRipsJs(id_factura){
    alert("Traer Rips Js"+id_factura);
}*/

$(document).ready(function() {
    ripsJs='';
    //generarRipsJson(id_factura);
});

function generarRipsJson() {    
    var url = "procesos/rips_procesos.php?id_factura=" + id_factura + "&opcion=traerRipsJs";
    //alert(url);
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
            //mostrarRipsJson(data);
            alertify.success('RIPS Json generados correctamente');
            ripsJs = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

/*function mostrarRipsJson(data){
    ripsJs = data;    
}*/

function descargarRipsJson(){    
    if (ripsJs == '') {
        alertify.error('No hay datos para descargar, primero debe Generar RIPS');
        return;
    }
    
    //console.log(ripsJs);
    
    // Crear un blob con el contenido de la variable
    const blob = new Blob([ripsJs], { type: 'application/javascript' });
    
    // Crear un enlace temporal para la descarga
    const enlace = document.createElement('a');
    enlace.href = URL.createObjectURL(blob);
    enlace.download = 'FEV'+numero_fac+'.json';
    
    // Agregar el enlace al DOM, hacer clic y eliminarlo
    document.body.appendChild(enlace);
    enlace.click();
    document.body.removeChild(enlace);
    
    // Liberar la URL del objeto para optimizar memoria
    URL.revokeObjectURL(enlace.href);
}
