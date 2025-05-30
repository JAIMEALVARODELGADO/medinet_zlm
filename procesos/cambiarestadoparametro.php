<?php
require_once "../clases/conexion.php";
require_once "../clases/crudparametros.php";

$obj=new crudparametros();
$datos=array(
    $_POST['idparametro'],
    $_POST['estado']
);
echo $obj->cambiarEstado($datos);
?>