<?php
require_once "../clases/conexion.php";
require_once "../clases/crudparametros.php";
$obj=new crudparametros();

$datos=array(
    $_POST['id_parametro'],
    $_POST['codigo_parametroU'],
    $_POST['tituloU']
);
echo $obj->actualizar($datos);
?>