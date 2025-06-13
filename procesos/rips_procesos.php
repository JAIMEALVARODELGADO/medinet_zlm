<?php
require_once "../clases/conexion.php";


switch($_GET['opcion']) {
    case 'crearRips':
        crearRips($_GET['id_factura']);
        break;
    case 'rips':
        include 'procesos/rips_rips.php';
        break;
    case 'ripsAc':
        include 'procesos/rips_ripsAc.php';
        break;
    case 'ripsAp':
        include 'procesos/rips_ripsAp.php';
        break;
    case 'ripsOt':
        include 'procesos/rips_ripsOt.php';
        break;
    default:
        echo "Opción no válida.";
        break;
}

function crearRips($id_factura) {
    $obj=new conectar();
	$conexion=$obj->conexion();

    $sql = "SELECT COUNT(*) as cantidad
    FROM nrusuario n
    WHERE n.id_factura ='$id_factura'";

    $row=mysqli_query($conexion,$sql);
    $resultado=mysqli_fetch_array($row);
    if($resultado['cantidad'] == 0) {
        $sql = "INSERT INTO nrusuario (
tipo_documento,numdocumento,
tipousuario,
fechanacimiento,
codsexo,
codpaisresidencia,
codmunicipioresidencia,
codzonaresidencia,
incapacidad,
codpaisorigen,
id_factura
)
        VALUES ()";
        echo $sql;
        //mysqli_query($conexion,$sql);
    }





	/*	$sql="INSERT INTO eps (codigo_eps,nit_eps,nombre_eps,direccion_eps,telefono_eps,contacto_eps)
		VALUES ('$datos[0]','$datos[1]','$datos[2]','$datos[3]','$datos[4]','$datos[5]')";
		return mysqli_query($conexion,$sql);

        $obj=new conectar();
		$conexion=$obj->conexion();

		$sql="SELECT id_eps,codigo_eps, nit_eps, nombre_eps, direccion_eps, telefono_eps, contacto_eps FROM eps WHERE id_eps='$ideps'";
		$row=mysqli_query($conexion,$sql);
		$ver=mysqli_fetch_row($row);
		$datos=array(
			'id_eps' => $ver[0],
			'codigo_eps' => $ver[1], 
			'nit_eps' => $ver[2], 
			'nombre_eps' => $ver[3], 
			'direccion_eps' => $ver[4], 
			'telefono_eps' => $ver[5], 
			'contacto_eps' => $ver[6]
			);
		return $datos;*/

    
}
?>