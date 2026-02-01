<?php
require("valida_sesion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Medinet V3</title>
    <?php require_once "scripts.php";?>
    <script src="js/mn_notas1.js"></script>
</head>

<body>
    <?php
    require("encabezado.php");
    require("menu.php");
    $hoy=date("Y-m-d");
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card text-left">
                    <div class="card-header">
                        <h4>Notas de Enfermería</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="id_persona" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $hoy;?>">
                            </div>
                            <div class="col-sm-3">
                                <span class="btn btn-primary" title="Buscar" onclick="actualizar_citas()" id="btn_buscar">Buscar <span class="fas fa-search"></span></span>
                                </span>
                            </div>
                        </div>
                        <div id="tablaDatatable"></div>
                    </div>
                    <div class="card-footer text-muted">
                        By Soluciones Thin & Thin
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <form id="form1" name='form1' method="POST">
        <input type="hidden" id="fecha_cita" name="fecha_cita">
        <input type="hidden" id="id_aten" name="id_aten">
    </form>


    <!-- Modal Nuevo -->
    <div class="modal fade" id="modalNuevaNota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nueva Nota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_nuevo">
                        <label>Paciente: </label>
                        <br><input type="text" readonly="readonly" class="form-control input-sm" id="nombrePaciente" name="nombrePaciente">
                        <br><label>Descripción</label>
                        <br><textarea name="descripcion" id="descripcion" cols="65" rows="10"></textarea>

                        <input type="hidden" id="id_agc" name="id_agc">
                        <input type="hidden" id="opcion" name="opcion">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <span class="fas fa-angle-double-left"></span></button>
                    <button type="button" id="btnNuevo" class="btn btn-primary" onclick="validar()">Guardar <span class="fas fa-save"></span>
                    </button>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lista de Notas -->
    <div class="modal fade" id="modalListaNotas" tabindex="-1" role="dialog" aria-labelledby="modalListaNotas" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalListaNotas">Lista de Notas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_nuevo">
                        <label>Paciente: </label>
                        <br><input type="text" readonly="readonly" class="form-control input-sm" id="nombrePaciente" name="nombrePaciente">
                        <br><label>Descripción</label>
                        <br><textarea name="descripcion" id="descripcion" cols="65" rows="10"></textarea>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <span class="fas fa-angle-double-left"></span></button>
                    <button type="button" id="btnNuevo" class="btn btn-primary" onclick="validar()">Guardar <span class="fas fa-save"></span>
                    </button>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notas por Paciente -->
    <div class="modal fade" id="modalNotasPaciente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notas del Paciente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Paciente: </label>
                        <input type="text" readonly="readonly" class="form-control input-sm" id="nombrePacienteL" name="nombrePacienteL">
                    </div>
                    
                    <!-- Tabla de notas -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tablaNotas">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="20%">Fecha</th>
                                    <th width="60%">Descripción</th>
                                    <th width="20%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Las filas se llenarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar <span class="fas fa-times"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Editar Nota (FUERA del modal padre) -->
<div class="modal fade" id="modalEditarNota" tabindex="-1" role="dialog" aria-labelledby="ModalLabelEdit" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabelEdit">Editar Nota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_editar">
                    <div class="form-group">
                        <label for="descripcion_edit">Descripción</label>
                        <textarea class="form-control" name="descripcion_edit" id="descripcion_edit" rows="4" placeholder="Escriba la descripción de la nota..."></textarea>
                    </div>
                    <input type="hidden" id="id_ne_editar" name="id_ne_editar">
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEdicionNota()">Guardar</button>
            </div>
        </div>
    </div>
</div>


    <input type="hidden" id="id_agc" name="id_agc">
    <input type="hidden" id="opcion" name="opcion">
</body>

</html>

<script type="text/javascript">
    actualizar_citas();

    function registrarNota(id_agc,nombrePaciente){
        document.getElementById("id_agc").value=id_agc;
        document.getElementById("opcion").value="nuevo";

        $('#modalNuevaNota').modal('show');
        
        $('#nombrePaciente').val(nombrePaciente);
        
    }

    function inasistencia(id_agc,nombre_){
        alertify.confirm('Registrar Inasistencia', 'Desea registrar la inasistencia del paciente: '+nombre_,
            function(){ 
                $.ajax({
                    type:"POST",
                    data:"id_agc="+id_agc,
                    url:"procesos/inasistencia.php",
                    success:function(r){
                        if(r==1){
                            $("#tablaDatatable").load("tablaagendanotas.php");
                            alertify.success("Inasistencia Registrada!");
                        }else{
                            alertify.error("Inasistencia no Registrada!");
                        }
                    }
                })

            }
            ,function(){

            });
    }

    function imprimir(id_aten){        
        $('#id_aten').val(id_aten);
        document.form1.action="mn_impr_historia.php";
        document.form1.target="new";
        document.form1.submit();
    }

    function imprimirformula(id_aten){        
        $('#id_aten').val(id_aten);
        document.form1.action="mn_impr_formula.php";
        document.form1.target="new";
        document.form1.submit();
    }

    function imprimirorden(id_aten){        
        $('#id_aten').val(id_aten);
        document.form1.action="mn_impr_orden.php";
        document.form1.target="new";
        document.form1.submit();
    }

    function imprimirproced(id_aten){        
        $('#id_aten').val(id_aten);
        document.form1.action="mn_impr_proced.php";
        document.form1.target="new";
        document.form1.submit();
    }

    function actualizar_citas(){
        $('#fecha_cita').val($('#fecha').val());
        $(document).ready(function(){
            datos=$('#form1').serialize();
            $.ajax({
                type:"POST",
                data:datos,
                url:"procesos/actualizarcitas.php",                
            });
            $("#tablaDatatable").load("tablaagendanotas.php");            
        });
    }

    function validar(){
        descripcion=$('#descripcion').val();
        if(descripcion==""){
            alertify.alert("Debe agregar una descripción de la nota");
            return false;
        }
        agregarNota();
    }

    function agregarNota(){
        let id_agc = document.getElementById('id_agc').value;
        let descripcion = document.getElementById('descripcion').value;
        let opcion = document.getElementById('opcion').value;
        
        const formData = new FormData();
        formData.append('id_agc', id_agc);
        formData.append('descripcion', descripcion);
        formData.append('opcion', opcion);

        fetch('procesos/crudNotas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            
            respuesta(data);
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

    function mostrarNotas(id_agc, nombrePaciente) {
        let opcion = "listarNotasPaciente";
        const formData = new FormData();
        formData.append('id_agc', id_agc);
        formData.append('descripcion','');
        formData.append('opcion', opcion);
        
        fetch('procesos/crudNotas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            mostrarNotasPaciente(data, nombrePaciente, id_agc);
        });
        //.catch(error => {
        //    console.error('Error:', error);
        //});
    }
    
</script>

<!---Aqui desactivo la combinacion Ctrl-Click -->
<script type="text/javascript">
    $('a').click(function (e){  
    if (e.ctrlKey) {
        return false;
    }
    });
</script>