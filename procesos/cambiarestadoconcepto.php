<?php
require_once "../clases/conexion.php";
require_once "../clases/crudconcepto.php";

$obj=new crudconcepto();
$datos=array(
    $_POST['codi_det'],
    $_POST['estado']
);
echo $obj->cambiarEstado($datos);
?>