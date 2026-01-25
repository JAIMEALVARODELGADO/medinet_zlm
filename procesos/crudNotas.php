<?php
require_once "../clases/conexion.php";

$obj=new conectar();
$conexion=$obj->conexion();

$id_agc = $_POST['id_agc'];
$descripcion = $_POST['descripcion'];
$opcion = $_POST['opcion'];

// Tu código aquí
//echo json_encode(['success' => true, 'mensaje' => 'Datos recibidos']);
//echo $descripcion;
switch ($opcion) {
    case 'nuevo':
        $query = "INSERT INTO notasenfermeria (id_agc, descripcion) VALUES ('$id_agc', '$descripcion')";
        $res=mysqli_query($conexion, $query);

        echo json_encode(['success' => true, 'mensaje' => 'Nota agregada exitosamente']);
        break;

    case 'actualizar':
        // Lógica para actualizar una nota
        // Aquí deberías actualizar la nota en la base de datos
        // Por ejemplo:
        // $query = "UPDATE notas SET descripcion='$descripcion' WHERE id_agc='$id_agc'";
        // Ejecutar la consulta...

        // Simulación de éxito
        echo json_encode(['success' => true, 'mensaje' => 'Nota actualizada exitosamente']);
        break;

    default:
        echo json_encode(['success' => false, 'mensaje' => 'Operación no válida']);
        break;
}
?>