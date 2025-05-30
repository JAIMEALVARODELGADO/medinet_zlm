<?php
require("valida_sesion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Medinet V3</title>
    <?php 
        require_once "scripts.php";        
    ?>    
</head>

<body>
    <?php
    require("encabezado.php");
    require("menu.php")
    ?>
    
    <div class="card text">
    <div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="mn_entidad1.php">Entidad</a>
				</li>				
				<li class="nav-item">
					<a class="nav-link active" href="#">Parámetros Generales</a>
				</li>
			</ul>
		</div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card text-left">
                    <div class="card-header">
                        <h4>PARAMETROS GENERALES</h4>
                    </div>
                    
                    <div class="card-body">
                        <hr>
                        <div id="tablaDataparametros"></div>
                    </div>
                    <div class="card-footer text-muted">
                        By Soluciones Thin & Thin
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar -->
    <div class="modal fade" id="modaleditarparametro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Parámetro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_editar">
                        <input type="hidden" id="id_parametro" name="id_parametro">
                        
                        <label>Código del Parámetro</label>
                        <input type="text" maxlength="10" class="form-control input-sm" id="codigo_parametroU" name="codigo_parametroU">
                        
                        <label>Título</label>
                        <input type="text" class="form-control input-sm" id="tituloU" name="tituloU">
                        
                        <!--<div class="form-group">
                            <label>Nombre del Parámetro (Solo lectura)</label>
                            <input type="text" class="form-control input-sm" id="nombre_parametroU" name="nombre_parametroU" readonly>
                        </div>-->
                        
                        <div class="form-group">
                            <label>Descripción (Solo lectura)</label>
                            <textarea class="form-control input-sm" id="descripcionU" name="descripcionU" rows="3" readonly></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <span class="fas fa-angle-double-left"></span></button>
                    <button type="button" id="btnActualizar" class="btn btn-primary">Guardar <span class="fas fa-save"></span></button>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>

<script type="text/javascript">
    $(document).ready(function(){
        $("#tablaDataparametros").load("tablaparametros.php");
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#btnActualizar').click(function(){
            datos=$('#frm_editar').serialize();
            $.ajax({
                type:"POST",
                data:datos,
                url:"procesos/actualizarparametro.php",
                success:function(r){
                    if(r==1){
                        $("#tablaDataparametros").load("tablaparametros.php");
                        alertify.success("Parámetro actualizado correctamente");
                        $('#modaleditarparametro').modal('hide');
                    }
                    else{
                        alertify.error("Error: El parámetro no se pudo actualizar");
                    }
                }
            });
        });
    });
</script>

<script type="text/javascript">
    function FrmActualizar(idparametro){
        $.ajax({
            type:"POST",
            data:"idparametro="+idparametro,
            url:"procesos/obtenDatosparametro.php",
            success:function(r){
                var datos = JSON.parse(r);                
                $('#id_parametro').val(datos['id_parametro']);
                $('#codigo_parametroU').val(datos['codigo_parametro']);
                $('#nombre_parametroU').val(datos['nombre_parametro']);
                $('#descripcionU').val(datos['descripcion']);
                $('#tituloU').val(datos['titulo']);
            }
        })
    }
</script>

<script type="text/javascript">
    function cambiarEstado(idparametro, estadoActual){        
        var nuevoEstado = estadoActual == "AC" ? "IN" : "AC";
        $.ajax({
            type:"POST",
            data:"idparametro="+idparametro+"&estado="+nuevoEstado,
            url:"procesos/cambiarestadoparametro.php",
            success:function(r){
                if(r==1){
                    $("#tablaDataparametros").load("tablaparametros.php");
                    //alertify.success("Estado actualizado correctamente");
                }
                else{
                    //alertify.error("Error al cambiar el estado");
                }
            }
        });
    }
</script>