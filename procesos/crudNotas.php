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
if(isset(($_POST['id_ne']))){
    $id_ne = $_POST['id_ne'];
} else {
    $id_ne = '';
}
$descripcion = $_POST['descripcion'];
$opcion = $_POST['opcion'];

switch ($opcion) {
    case 'nuevo':
        $query = "INSERT INTO notasenfermeria (id_agc, descripcion, operador_ne) 
        VALUES ('$id_agc', '$descripcion', '$usuario_log')";
        $res=mysqli_query($conexion, $query);

        echo json_encode(['success' => true, 'mensaje' => 'Nota agregada exitosamente']);
        break;

    case 'listarNotasPaciente':
        echo json_encode(ListarNotasPaciente($id_agc));
        break;

    case 'editar':
        $query = "UPDATE notasenfermeria SET descripcion='$descripcion' 
        WHERE id_ne='$id_ne'";
        //printf($query);
        $res=mysqli_query($conexion, $query);
        if(!$res){
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar la nota']);
            exit;
        }
        echo json_encode(['success' => true, 'mensaje' => 'Nota actualizada exitosamente']);
        break;

    default:
        echo json_encode(['success' => false, 'mensaje' => 'Operación no válida']);
        break;
}

function ListarNotasPaciente($id_agc) {
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
}

?>