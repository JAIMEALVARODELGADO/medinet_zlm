<?php
require_once "../clases/conexion.php";


switch($_GET['opcion']) {
    case 'crearRips':
        crearRips($_GET['id_factura']);
        break;
    case 'traerUs':        
        echo traerUs($_GET['id_factura']);
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
        // Insertar datos en la tabla nrusuario
        $sql = "INSERT INTO nrusuario (
        tipo_documento,numdocumento,tipousuario,fechanacimiento,
        codsexo,codpaisresidencia,codmunicipioresidencia,codzonaresidencia,
        incapacidad,codpaisorigen,id_factura)
        SELECT dg.valor_det as tipo_documento,numero_iden_per,
        tp.valor_det as tipo_usuario,fnac_per,sx.valor_det as sexo_per,170 as codpaisresidencia,codigo_mun,
        CASE 
            WHEN zona = 'R' THEN '01'
            WHEN zona = 'U' THEN '02'
            ELSE NULL
        END AS zona,
        'NO' as incapacidad,'170' as codpaisorigen,fe.id_factura
        FROM factura_encabezado fe
        inner join persona p on p.id_persona = fe.id_persona 
        left join detalle_grupo dg on dg.codi_det = p.tipo_iden_per
        left join paciente pa on pa.id_persona = p.id_persona
        left join detalle_grupo tp on tp.codi_det = pa.tipo_usuario
        left join detalle_grupo sx on sx.codi_det = p.sexo_per
        WHERE fe.id_factura ='$id_factura'";
        //echo $sql;
        mysqli_query($conexion,$sql);

        // Insertar datos en la tabla nrconsulta
        $sql= "SELECT fe.fechaini_fac,'' as numautorizacion,
        cd.codigo_cdet,'' as modalidadgruposervicio,'' as gruposervicio,        
        '' as codservicio,'' as finalidad,'' as causamotivoatencion,'' as coddiagnosticoprincipal,
        '' as cddiagnosticorelacionado1,'' as coddiagnosticorelacionado2,
        '' as coddiagnosticorelacionado3,'' as tipodiagnosticoprincipal,
        fd.valor_unit_detfac,'' as conceptorecaudo,'0' as valorpagomoderador,        
        '' as numfevpagomoderador,fe.id_factura ,fd.id_detfac,fd.cantidad_detfac
        FROM factura_detalle fd 
        INNER JOIN factura_encabezado fe on fe.id_factura = fd.id_factura 
        INNER JOIN convenio_detalle cd on cd.id_cdet = fd.id_cdet 
        WHERE cd.tipo_cdet='AC' AND fd.id_factura ='$id_factura'";
        $row = mysqli_query($conexion,$sql);
        if(mysqli_num_rows($row) <> 0) {
            $consecutivo = 1;
            while($fila = mysqli_fetch_array($row)) {
                // Preparar los datos para la inserción
    
                $cantidad = $fila['cantidad_detfac'];
                for($i=0; $i < $cantidad; $i++) {
                    $sql = "INSERT INTO nrconsulta (
                        fechainicioatencion,
                        numautorizacion,
                        codconsulta,
                        modalidadgruposervicio,
                        gruposervicio,
                        codservicio,
                        finalidadtecnologiasalud,
                        causamotivoatencion,
                        coddiagnosticoprincipal,
                        coddiagnosticorelacionado1,
                        coddiagnosticorelacionado2,
                        coddiagnosticorelacionado3,
                        tipodiagnosticoprincipal,
                        vrservicio,
                        conceptorecaudo,
                        valorpagomoderador,
                        numfevpagomoderador,
                        consecutivo,
                        id_factura,
                        id_detalle)
                        VALUES (
                        '{$fila['fechaini_fac']}',
                        '{$fila['numautorizacion']}',
                        '{$fila['codigo_cdet']}',
                        '{$fila['modalidadgruposervicio']}',
                        '{$fila['gruposervicio']}',
                        '{$fila['codservicio']}',
                        '{$fila['finalidad']}',
                        '{$fila['causamotivoatencion']}',
                        '{$fila['coddiagnosticoprincipal']}',
                        '{$fila['cddiagnosticorelacionado1']}',
                        '{$fila['coddiagnosticorelacionado2']}',
                        '{$fila['coddiagnosticorelacionado3']}',
                        '{$fila['tipodiagnosticoprincipal']}',
                        '{$fila['valor_unit_detfac']}',
                        '{$fila['conceptorecaudo']}',
                        '{$fila['valorpagomoderador']}',
                        '{$fila['numfevpagomoderador']}',
                        '$consecutivo',
                        '{$fila['id_factura']}',
                        '{$fila['id_detfac']}'                    
                        )";
                    //echo $sql;
                    $consecutivo++;
                    mysqli_query($conexion,$sql);                    
                }    
            }            
        }
        // Insertar datos en la tabla nrprocedimientos
        $sql = "SELECT fe.fechaini_fac,'' as idmipres,'' as numautorizacion,
        cd.codigo_cdet,'' as codprocedimiento,'' as viaingresoserviciosalud,
        '' as modalidadgruposerviciotecsal,'' as gruposervicios,
        '' as codservicio,'' as finalidadtecnologiasalud,
        '' as coddiagnosticoprincipal,'' as coddiagnosticorelacionado,
        '' as codcomplicacion,fd.valor_unit_detfac,'' as conceptorecaudo,
        '0' as valorpagomoderador,'' as numfevpagomoderador,
        fe.id_factura ,fd.id_detfac,fd.cantidad_detfac
        FROM factura_detalle fd 
        INNER JOIN factura_encabezado fe on fe.id_factura = fd.id_factura 
        INNER JOIN convenio_detalle cd on cd.id_cdet = fd.id_cdet 
        WHERE cd.tipo_cdet='AP' AND fd.id_factura ='$id_factura'";
        //echo $sql;
        $row = mysqli_query($conexion,$sql);
        if(mysqli_num_rows($row) <> 0) {
            $consecutivo = 1;
            while($fila = mysqli_fetch_array($row)) {
                // Preparar los datos para la inserción
    
                $cantidad = $fila['cantidad_detfac'];
                for($i=0; $i < $cantidad; $i++) {
                    $sql = "INSERT INTO nrprocedimientos(
                        fechainicioatencion,
                        idmipres,
                        numautorizacion,
                        codprocedimiento,
                        viaingresoserviciosalud,
                        modalidadgruposerviciotecsal,
                        gruposervicios,
                        codservicio,
                        finalidadtecnologiasalud,                        
                        coddiagnosticoprincipal,
                        coddiagnosticorelacionado,
                        codcomplicacion,
                        vrservicio,
                        conceptorecaudo,
                        valorpagomoderador,
                        numfevpagomoderador,
                        consecutivo,
                        id_factura,
                        id_detfac)
                        VALUES (
                        '{$fila['fechaini_fac']}',
                        '{$fila['idmipres']}',
                        '{$fila['numautorizacion']}',                        
                        '{$fila['codprocedimiento']}',
                        '{$fila['viaingresoserviciosalud']}',
                        '{$fila['modalidadgruposerviciotecsal']}',
                        '{$fila['gruposervicios']}',
                        '{$fila['codservicio']}',
                        '{$fila['finalidadtecnologiasalud']}',
                        '{$fila['coddiagnosticoprincipal']}',
                        '{$fila['coddiagnosticorelacionado']}',
                        '{$fila['codcomplicacion']}',
                        '{$fila['valor_unit_detfac']}',
                        '{$fila['conceptorecaudo']}',
                        '{$fila['valorpagomoderador']}',
                        '{$fila['numfevpagomoderador']}',
                        '$consecutivo',
                        '{$fila['id_factura']}',
                        '{$fila['id_detfac']}'                        
                        )";
                    //echo "<br>".$sql;
                    $consecutivo++;
                    mysqli_query($conexion,$sql);                    
                }    
            }
        }

        // Insertar datos en la tabla nrotroservicios
        $sql = "SELECT '' as numautorizacion,0 as idmipres,fe.fechaini_fac,
        '' as tipoos,cd.codigo_cdet,cd.descripcion_cdet as nomtecnologia,
        fd.cantidad_detfac as cantidados, fd.valor_unit_detfac as vrunitos,
        fd.cantidad_detfac * fd.valor_unit_detfac as vrservicio,
        '' as conceptorecaudo,'0' as valorpagomoderador,
        '' as numfevpagomoderador,fe.id_factura ,fd.id_detfac
        FROM factura_detalle fd 
        INNER JOIN factura_encabezado fe on fe.id_factura = fd.id_factura 
        INNER JOIN convenio_detalle cd on cd.id_cdet = fd.id_cdet 
        WHERE cd.tipo_cdet='AT' AND fd.id_factura ='$id_factura'";
        //echo $sql;
        $row = mysqli_query($conexion,$sql);
        if(mysqli_num_rows($row) <> 0) {
            $consecutivo = 1;
            while($fila = mysqli_fetch_array($row)) {
                // Preparar los datos para la inserción
                $sql = "INSERT INTO nrotroservicios(
                        numautorizacion,idmipres,fechasuministrotecnologia,
                        tipoos,codtecnologia,nomtecnologia,cantidados,vrunitos,
                        vrservicio,conceptorecaudo,valorpagomoderador,
                        numfevpagomoderador,consecutivo,id_factura,id_detalle)
                        VALUES (
                        '{$fila['numautorizacion']}',
                        '{$fila['idmipres']}',
                        '{$fila['fechaini_fac']}',
                        '{$fila['tipoos']}',
                        '{$fila['codigo_cdet']}',
                        '{$fila['nomtecnologia']}',
                        '{$fila['cantidados']}',
                        '{$fila['vrunitos']}',
                        '{$fila['vrservicio']}',
                        '{$fila['conceptorecaudo']}',
                        '{$fila['valorpagomoderador']}',
                        '{$fila['numfevpagomoderador']}',
                        '$consecutivo',
                        '{$fila['id_factura']}',
                        '{$fila['id_detfac']}'
                        )";                        
                //echo "<br>".$sql;
                $consecutivo++;
                mysqli_query($conexion,$sql);
            }
        }
    }
}

function traerUs($id_factura) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_usuario, tipo_documento, numdocumento, tipousuario, fechanacimiento, codsexo, codpaisresidencia, codmunicipioresidencia, codzonaresidencia, incapacidad, codpaisorigen,
        CONCAT (p.pnom_per, ' ', p.snom_per,' ',p.pape_per,' ',p.sape_per) as nombre_completo
        FROM nrusuario nru
        inner join factura_encabezado fe on fe.id_factura = nru.id_factura 
        inner join persona p on p.id_persona = fe.id_persona 
        WHERE nru.id_factura = '$id_factura'";
    //echo $sql;
    
    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    //echo json_encode($data);
    return(json_encode($data));
}

function actualizarUsuario($datos) {
    $obj = new conectar();
    $conexion = $obj->conexion();
    
    $id_usuario = mysqli_real_escape_string($conexion, $datos['id_usuario']);
    $tipo_documento = mysqli_real_escape_string($conexion, $datos['tipo_documento']);
    $numdocumento = mysqli_real_escape_string($conexion, $datos['numdocumento']);
    $tipousuario = mysqli_real_escape_string($conexion, $datos['tipousuario']);
    $fechanacimiento = mysqli_real_escape_string($conexion, $datos['fechanacimiento']);
    $codsexo = mysqli_real_escape_string($conexion, $datos['codsexo']);
    $codpaisresidencia = mysqli_real_escape_string($conexion, $datos['codpaisresidencia']);
    $codmunicipioresidencia = mysqli_real_escape_string($conexion, $datos['codmunicipioresidencia']);
    $codzonaresidencia = mysqli_real_escape_string($conexion, $datos['codzonaresidencia']);
    $incapacidad = mysqli_real_escape_string($conexion, $datos['incapacidad']);
    $codpaisorigen = mysqli_real_escape_string($conexion, $datos['codpaisorigen']);
    
    $sql = "UPDATE nrusuario SET 
            tipo_documento = '$tipo_documento',
            numdocumento = '$numdocumento',
            tipousuario = '$tipousuario',
            fechanacimiento = '$fechanacimiento',
            codsexo = '$codsexo',
            codpaisresidencia = '$codpaisresidencia',
            codmunicipioresidencia = '$codmunicipioresidencia',
            codzonaresidencia = '$codzonaresidencia',
            incapacidad = '$incapacidad',
            codpaisorigen = '$codpaisorigen'
            WHERE id_usuario = '$id_usuario'";
    
    if(mysqli_query($conexion, $sql)) {
        echo "Usuario actualizado correctamente";
    } else {
        echo "Error al actualizar usuario: " . mysqli_error($conexion);
    }
}
?>