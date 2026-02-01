
function mostrarNotasPaciente(data, nombrePaciente, id_agc) {
    // Limpiar la tabla antes de llenarla
    $('#tablaNotas tbody').empty();
    
    // Llenar el nombre del paciente
    $('#nombrePacienteL').val(nombrePaciente);
    $('#id_agc').val(id_agc);
    
    // Llenar la tabla con los datos
    if (data && data.length > 0) {
        data.forEach(function(nota) {
            let fila = `
                <tr>
                    <td>${nota.fecha_ne}</td>
                    <td>${nota.descripcion}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="editarNota(${nota.id_ne}, '${nota.descripcion}')" title="Editar">
                            <span class="fas fa-edit"></span>
                        </button>
                    </td>
                </tr>
            `;
            $('#tablaNotas tbody').append(fila);
        });
    } else {
        $('#tablaNotas tbody').append(`
            <tr>
                <td colspan="3" class="text-center">No hay notas registradas</td>
            </tr>
        `);
    }
    
    $('#modalNotasPaciente').modal('show');
}


function editarNota(id_ne, descripcion) {
    $('#modalEditarNota').modal('show');
    document.getElementById('descripcion_edit').value = descripcion;
    document.getElementById('id_ne_editar').value = id_ne;
}

function guardarEdicionNota(){
    descripcion=document.getElementById("descripcion_edit");
    if(descripcion==""){
        alertify.alert("Debe agregar una descripción de la nota");
        return false;
    }
    guardarEditarNota();
}

function guardarEditarNota(){
    let id_agc = document.getElementById('id_agc').value;
    let nombrePaciente = document.getElementById('nombrePacienteL').value;
    let id_ne = document.getElementById('id_ne_editar').value;
    let descripcion = document.getElementById('descripcion_edit').value;
    let opcion = "editar";
    
    const formData = new FormData();
    formData.append('id_ne', id_ne);
    formData.append('descripcion', descripcion);
    formData.append('opcion', opcion);

    fetch('procesos/crudNotas.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        
        respuesta(data);

        $('#modalEditarNota').modal('hide');
        
        mostrarNotas(id_agc,nombrePaciente);


    })
    .catch(error => {
        console.error('Error:', error);
    });
    
}

function respuesta(data){
    if(data.success){
        alertify.success(data.mensaje);
        $('#modalNuevaNota').modal('hide');
        $("#tablaDatatable").load("tablaagendanotas.php");
        document.getElementById('descripcion').value='';
        document.getElementById('id_agc').value='';
        document.getElementById('opcion').value='';
    }else{ 
        alertify.error(data.mensaje);
    }
}