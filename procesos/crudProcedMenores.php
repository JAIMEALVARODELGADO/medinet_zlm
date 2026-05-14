<?php
session_start();
require_once "../clases/conexion.php";

$obj=new conectar();
$conexion=$obj->conexion();
if($_SESSION['gusuario_log']){
    $usuario_log = $_SESSION['gusuario_log'];
} else {
    $usuario_log = '';
}
if(isset($_POST['id_agc'])){
    $id_agc = $_POST['id_agc'];
} else {
    $opcion = 'Salir';
}
/*if(isset(($_POST['id_ne']))){
    $id_ne = $_POST['id_ne'];
} else {
    $id_ne = '';
}*/

$opcion = $_POST['opcion'];
if(isset($_POST['plantilla'])){
    $plantilla = $_POST['plantilla'];
}
if(isset(($_POST['id_aten']))){
    $id_aten= $_POST['id_aten'];
}
if(isset(($_POST['descripcion']))){
    $descripcion = $_POST['descripcion'];
}
if(isset(($_POST['id_procedimiento_ed']))){
    $id_procedimiento_ed = $_POST['id_procedimiento_ed'];
}
//echo "<br>Descripcion ed: ".$_POST['$descripcion_ed'];
if(isset(($_POST['descripcion_ed']))){
    $descripcion = $_POST['descripcion_ed'];
}

if(isset(($_POST['id_procmenor']))){
    $id_procmenor = $_POST['id_procmenor'];
}

switch ($opcion) {
    case 'traerPlantilla':
        $query = "SELECT pg.descripcion,pg.titulo 
        FROM parametros_generales pg 
        WHERE pg.nombre_parametro ='$plantilla'";
        
        $result = mysqli_query($conexion, $query);
        $data = mysqli_fetch_assoc($result);
        
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Plantilla no encontrada']);
            break;
        }
    
        echo json_encode([
            'success'     => true,
            'descripcion' => $data['descripcion'],
            'titulo'      => $data['titulo']
        ]);
    
        
        break;
    case 'nuevo':
        $query = "INSERT INTO consulta_proced_menores (id_aten, descripcion, operador_proc)
        VALUES ('$id_aten', '$descripcion', '$usuario_log')";
        //echo $query;
        $res=mysqli_query($conexion, $query);

        echo json_encode(['success' => true, 'mensaje' => 'Nota agregada exitosamente']);
        break;

    /*case 'listarNotasPaciente':
        echo json_encode(ListarNotasPaciente($id_agc));
        break;*/

    case 'editar':
        $query = "UPDATE consulta_proced_menores SET descripcion='$descripcion' 
        WHERE id_procmenor='$id_procedimiento_ed'";
        //echo $query;
        $res=mysqli_query($conexion, $query);
        if(!$res){
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar el procedimiento']);
            exit;
        }
        echo json_encode(['success' => true, 'mensaje' => 'Procedimiento actualizada exitosamente']);
        break;
    
    case 'consultar_Id':
        $query = "SELECT id_procmenor, id_aten,fecha_proc, descripcion,operador_proc 
        FROM consulta_proced_menores 
        WHERE id_procmenor='$id_procmenor'";
        
        $result = mysqli_query($conexion, $query);
        $data = mysqli_fetch_assoc($result);
        if (!$data) {
            echo json_encode(['success' => false, 'mensaje' => 'Nota no encontrada']);
            break;
        }
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    default:
        echo json_encode(['success' => false, 'mensaje' => 'Operación no válida']);
        break;
}

/*function ListarNotasPaciente($id_agc) {
    global $conexion;
    $query = "SELECT id_ne, id_agc, fecha_ne, descripcion 
    FROM notasenfermeria 
    WHERE id_agc='$id_agc'";
    $result = mysqli_query($conexion, $query);
    $notas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notas[] = $row;
    }
    return $notas;
}*/

?>