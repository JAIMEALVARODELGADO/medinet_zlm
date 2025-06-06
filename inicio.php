<?php
session_start();
if(isset($_POST['id_persona'])){
	$_SESSION['gusuario_log']=$_POST['id_persona'];
	$_SESSION['gnombreusu_log']=$_POST['nombre_usu'];
    if(!isset($_SESSION['gcontador_log'])){
        $_SESSION['gcontador_log']=$_POST['contador_log'];
    }
    else{
        $_SESSION['gcontador_log']=$_SESSION['gcontador_log']+$_POST['contador_log'];    
    }
}

require_once "clases/conexion.php";
$obj=new conectar();
$conexion=$obj->conexion();

//Aqui consulto el nombre de la entidad para mostrarlo en el encabezado, de acuerdo a la parametrizacion
$consparametros="SELECT estado FROM parametros_generales WHERE nombre_parametro ='nombreentidadxprofesional'";
$consparametros=mysqli_query($conexion,$consparametros);
if(mysqli_num_rows($consparametros) > 0) {    
    $rowparametro=mysqli_fetch_array($consparametros);
    $estadoparametro=$rowparametro['estado'];
}
else {
    $estadoparametro='IN'; // Si no existe el parametro, lo dejo en estado inactivo
}

if($estadoparametro == 'AC'){
    $consenti="SELECT CONCAT(pnom_per,' ',snom_per,' ',pape_per,' ',sape_per) as nombre
    FROM persona p
    WHERE p.id_persona ='{$_POST['id_persona']}'";
    $consenti=mysqli_query($conexion,$consenti);
    $row=mysqli_fetch_array($consenti);
    $_SESSION['gnombre_ent']=$row['nombre'];
} else {
    $consenti="SELECT nombre_ent FROM entidad WHERE id_ent='1'";
    $consenti=mysqli_query($conexion,$consenti);
    $row=mysqli_fetch_row($consenti);
    $_SESSION['gnombre_ent']=$row[0];
}



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Medinet V3</title>
    <?php require_once "scripts.php";?>
</head>
<style>
body {
    background-image: url("https://www.sectorial.co/images/salud.jpg");
    background-repeat: no-repeat;
    height: 100%;
    background-size: cover;
}
</style>
<body>
<?php
	require("encabezado.php");
    require("menu.php");
?>        
</body>

</html>
