<?php
require(__DIR__ . '/../valida_sesion.php');
require_once "../clases/conexion.php";

$opcion = isset($_GET['opcion']) ? $_GET['opcion'] : $_POST['opcion'];

if(!isset($opcion)) {
    echo "No se ha especificado una opción.";
    exit;
}

switch($opcion) {
    case 'traerConvenios':
        echo traerConvenios();        
        break;
    case 'crearFacturaExterna':
        echo crearFacturaExterna($_GET['factura']);
        break;
    
}

function traerConvenios(){
    $obj = new conectar();
    $conexion = $obj->conexion();

    $sql="SELECT id_convenio,convenio_eps FROM vw_convenio WHERE estado_conv='A' ORDER BY nombre_eps";
    $result=mysqli_query($conexion,$sql);
    $data = array();
    while($row=mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    if(!empty($data)) {
        return json_encode($data);
    } else {
        return json_encode(array());
    }
}

function crearFacturaExterna($datos) {

    $msj="";
    $factura = json_decode($datos, true); // true para array asociativo

    //print_r($factura);
    $obj = new conectar();
    $conexion = $obj->conexion();

    //Aqui se consulta el tipo de servicio
    $TipoOs=0;
    $sqlTpOs="SELECT codi_det
    FROM detalle_grupo dg 
    WHERE id_grupo ='30' AND valor_det ='01'";
    //echo $sqlTpOs;

    $sqlTpOs=mysqli_query($conexion,$sqlTpOs);
    if(mysqli_num_rows($sqlTpOs)<>0){
        $row=mysqli_fetch_array($sqlTpOs);
        $TipoOs=$row['codi_det'];
    }

    
    $numero_factura = mysqli_real_escape_string($conexion, $factura['numero_factura']);
    $id_persona = mysqli_real_escape_string($conexion, $factura['id_persona']);
    $id_convenio = mysqli_real_escape_string($conexion, $factura['id_convenio']);
    $fecha_fac = mysqli_real_escape_string($conexion, $factura['fecha_fac']);
    $valortot_fac = mysqli_real_escape_string($conexion, $factura['valortot_fac']);    

    //Aqui se separ letras y números
    preg_match('/^([A-Za-z]+)(\d+)$/', $numero_factura, $matches);
    $prefijo_fac = $matches[1];
    $numero_fac = $matches[2];
    
    //Aqui se valida si la factura ya fue cargada
    $sqlFac="SELECT id_factura
    FROM factura_encabezado
    WHERE numero_fac='$numero_fac' AND prefijo_fac ='$prefijo_fac'";
    $sqlFac=mysqli_query($conexion,$sqlFac);
    if(mysqli_num_rows($sqlFac) > 0) {
        $msj= "La factura ya fue cargada anteriormente.";
        return($msj);
    }

    $gusuario_log = $_SESSION['gusuario_log'];
    $id_persona=10;
    // Insertar la factura en la base de datos
    $sql = "INSERT INTO factura_encabezado(numero_fac,id_persona,id_convenio,fecha_fac,
    fechaini_fac,fechafin_fac,fechacierre_fac,operador_fac,valortot_fac,
    copago_fac,descuento_fac,valorneto_fac,esta_fac,prefijo_fac,formapago_fac)
    VALUES ('$numero_fac', '$id_persona', '$id_convenio', '$fecha_fac',
    '$fecha_fac', '$fecha_fac', '$fecha_fac', '$gusuario_log', '$valortot_fac', 
    '0', '0', '$valortot_fac', 'C', '$prefijo_fac', '135')";

    //echo"<br>".$sql;

    mysqli_query($conexion, $sql);
    $id_factura=mysqli_insert_id($conexion);
    if(!$id_factura) {
        $msj= "Error al crear la factura: " . mysqli_error($conexion);
        return($msj);
    }

    $detalle = $factura['detalle'];
    foreach ($detalle as $item) {
        $descripcion = mysqli_real_escape_string($conexion, $item['descripcion']);
        $cantidad = mysqli_real_escape_string($conexion, $item['cantidad']);
        $valor_unit = mysqli_real_escape_string($conexion, $item['valor_unit']);        

        //Aqui se consulta el insumo en la tabla medicamento
        $consultaMed= "SELECT id_medicamento FROM medicamento 
                WHERE nombre_mto='$descripcion'";
        //echo "<br>".$consultaMed;

        $resultMed = mysqli_query($conexion, $consultaMed);
        if(mysqli_num_rows($resultMed) > 0) {
            $rowMed = mysqli_fetch_assoc($resultMed);
            $id_servicio = $rowMed['id_medicamento'];                    
        } else {
            // Si no existe el medicamento, asignar 0
            $sqlMedicamento = "INSERT INTO medicamento(nombre_mto, estado_mto, tipo_mto, tipo_os_am)
                            VALUES ('$descripcion', 'A', 'D','$TipoOs')";
            //echo "<br>".$sqlMedicamento;
            $resultMedicamento = mysqli_query($conexion, $sqlMedicamento);
            $id_servicio = mysqli_insert_id($conexion); // Obtener el ID del nuevo medicamento insertado
        }

        //Aqui se consulta el insumo en el convenio
        $consultaDet = "SELECT id_cdet FROM convenio_detalle 
            WHERE id_convenio='$id_convenio' AND id_servicio='$id_servicio'";
        $resultDet = mysqli_query($conexion, $consultaDet);

        if(mysqli_num_rows($resultDet) > 0) {
            $rowDet = mysqli_fetch_assoc($resultDet);
            $id_cdet = $rowDet['id_cdet'];
        } else {
            // Si no existe, insertar el detalle                        
            $sql_detalle_conv = "INSERT INTO convenio_detalle(id_convenio,id_servicio,descripcion_cdet,tipo_cdet,codigo_cdet,valor_cdet,estado_cdet)
                                 VALUES ('$id_convenio','$id_servicio', '$descripcion', 'AT', '', '$valor_unit', 'A')";
            //echo "<br>".$sql_detalle_conv;
            $result_detalle_conv = mysqli_query($conexion, $sql_detalle_conv);

            $id_cdet = mysqli_insert_id($conexion); // Obtener el ID del nuevo detalle insertado
        }        

        // Insertar el detalle de la factura
        $sql_detalle = "INSERT INTO factura_detalle(id_factura,id_cdet,cantidad_detfac,valor_unit_detfac)
                        VALUES ('$id_factura', '$id_cdet', '$cantidad', '$valor_unit')";
        
        //echo "<br>".$sql_detalle;
        $result_detalle = mysqli_query($conexion, $sql_detalle);
        if(!$result_detalle) {
            $msj = "Error al crear la factura: ";
        }
        else{
            $msj = "Factura creada con éxito ";
        }
    }
    return $msj;
}
?>