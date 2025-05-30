<?php
require_once "../clases/conexion.php";
require_once "../clases/crudparametros.php";

$obj=new crudparametros();
echo json_encode($obj->obtenDatos($_POST['idparametro']));
?>