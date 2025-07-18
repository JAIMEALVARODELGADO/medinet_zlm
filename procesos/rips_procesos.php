<?php
require_once "../clases/conexion.php";

$opcion = isset($_GET['opcion']) ? $_GET['opcion'] : $_POST['opcion'];

if(!isset($opcion)) {
    echo "No se ha especificado una opción.";
    exit;
}

switch($opcion) {
    case 'crearRips':
        crearRips($_GET['id_factura']);
        break;
    case 'traerUs':        
        echo traerUs($_GET['id_factura']);
        break;
    case 'traerUsPorId':        
        echo traerUsPorId($_GET['id_usuario']);
        break;
    case 'traerDetalleGrupo':
        echo traerDetalleGrupo($_GET['id_grupo']);
        break;        
    case 'traerMunicipios':
        echo traerMunicipios();
        break;
    case 'guardarUsuario':
        guardarUsuario($_POST);
        break;
    case 'traerConsultas':
        echo traerConsultas($_GET['id_factura']);
        break;
    case 'traerConsultaPorId':
        echo traerConsultaPorId($_GET['id_consulta']);
        break;
    case 'guardarConsulta':
        guardarConsulta($_POST);
        break;
    case 'traerProcedimientos':
        echo traerProcedimientos($_GET['id_factura']);
        break;
    case 'traerProcedimientoPorId':
        echo traerProcedimientoPorId($_GET['id_procedimiento']);
        break;
    case 'guardarProcedimiento':
        guardarProcedimiento($_POST);
        break;
    case 'traerOtServicios':        
        echo traerOtServicios($_GET['id_factura']);
        break;
    case 'traerOtServicioPorId':
        echo traerOtServicioPorId($_GET['id_otroservicio']);
        break;
    case 'guardarOtServicio':
        guardarOtServicio($_POST);
        break;
    case 'traerRipsJs':        
        traerRipsJs($_GET['id_factura']);
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

function guardarUsuario($datos) {
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


function traerUsPorId($id_usuario) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_usuario, tipo_documento, numdocumento, tipousuario, fechanacimiento, codsexo, 
        codpaisresidencia, codmunicipioresidencia, codzonaresidencia, incapacidad, codpaisorigen,nru.id_factura,
        CONCAT (p.pnom_per, ' ', p.snom_per,' ',p.pape_per,' ',p.sape_per) as nombre_completo
        FROM nrusuario nru
        inner join factura_encabezado fe on fe.id_factura = nru.id_factura 
        inner join persona p on p.id_persona = fe.id_persona 
        WHERE nru.id_usuario = '$id_usuario'";
    
    //echo $sql;

    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    $row = mysqli_fetch_assoc($result);
    $data = $row;
    
    return(json_encode($data));
}

function traerDetalleGrupo($id_grupo) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT valor_det,descripcion_det 
    FROM detalle_grupo WHERE estado='AC' AND id_grupo='$id_grupo' ORDER BY descripcion_det";
    
    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return(json_encode($data));
}

function traerMunicipios() {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT codigo_mun, nombre_mun FROM municipio ORDER BY nombre_mun";
    
    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return(json_encode($data));
}

function traerConsultas($id_factura) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_consulta, fechainicioatencion, numautorizacion, codconsulta, 
        modalidadgruposervicio, gruposervicio, codservicio, finalidadtecnologiasalud, 
        causamotivoatencion, coddiagnosticoprincipal,
        coddiagnosticorelacionado1, coddiagnosticorelacionado2, coddiagnosticorelacionado3,
        tipodiagnosticoprincipal, vrservicio, conceptorecaudo, valorpagomoderador,
        numfevpagomoderador, consecutivo,id_detalle
        FROM nrconsulta WHERE id_factura = '$id_factura'";
            
    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }    
    
    return(json_encode($data));
}

function traerConsultaPorId($id_consulta) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_consulta, fechainicioatencion, numautorizacion, codconsulta, 
        modalidadgruposervicio, gruposervicio, codservicio, finalidadtecnologiasalud, 
        causamotivoatencion, coddiagnosticoprincipal,
        coddiagnosticorelacionado1, coddiagnosticorelacionado2, coddiagnosticorelacionado3,
        tipodiagnosticoprincipal, vrservicio, conceptorecaudo, valorpagomoderador,
        numfevpagomoderador, consecutivo,id_detalle
        FROM nrconsulta WHERE id_consulta = '$id_consulta'";

    //echo $sql;
            
    $result = mysqli_query($conexion,$sql);    
    
    $data = array();
    $row = mysqli_fetch_assoc($result);
    $data = $row;
        
    return(json_encode($data));
}

function guardarConsulta($datos) {
    $obj = new conectar();
    $conexion = $obj->conexion();

    $id_consulta = mysqli_real_escape_string($conexion, $datos['id_consulta']);
    $fechainicioatencion = mysqli_real_escape_string($conexion, $datos['fechainicioatencion']);
    $numautorizacion = mysqli_real_escape_string($conexion, $datos['numautorizacion']);
    $codconsulta = mysqli_real_escape_string($conexion, $datos['codconsulta']);
    $modalidadgruposervicio = mysqli_real_escape_string($conexion, $datos['modalidadgruposervicio']);
    $gruposervicio = mysqli_real_escape_string($conexion, $datos['gruposervicio']);
    $codservicio = mysqli_real_escape_string($conexion, $datos['codservicio']);
    $finalidadtecnologiasalud = mysqli_real_escape_string($conexion, $datos['finalidadtecnologiasalud']);
    $causamotivoatencion = mysqli_real_escape_string($conexion, $datos['causamotivoatencion']);
    $coddiagnosticoprincipal = mysqli_real_escape_string($conexion, $datos['coddiagnosticoprincipal']);
    $coddiagnosticorelacionado1 = mysqli_real_escape_string($conexion, $datos['coddiagnosticorelacionado1']);
    $coddiagnosticorelacionado2 = mysqli_real_escape_string($conexion, $datos['coddiagnosticorelacionado2']);
    $coddiagnosticorelacionado3 = mysqli_real_escape_string($conexion, $datos['coddiagnosticorelacionado3']);
    $tipodiagnosticoprincipal = mysqli_real_escape_string($conexion, $datos['tipodiagnosticoprincipal']);
    $vrservicio = mysqli_real_escape_string($conexion, $datos['vrservicio']);
    $conceptorecaudo = mysqli_real_escape_string($conexion, $datos['conceptorecaudo']);
    $valorpagomoderador = mysqli_real_escape_string($conexion, $datos['valorpagomoderador']);
    $numfevpagomoderador = mysqli_real_escape_string($conexion, $datos['numfevpagomoderador']);    
    $id_detalle = mysqli_real_escape_string($conexion, $datos['id_detalle']);

    $sql= "UPDATE nrconsulta SET 
            fechainicioatencion = '$fechainicioatencion',
            numautorizacion = '$numautorizacion',
            codconsulta = '$codconsulta',
            modalidadgruposervicio = '$modalidadgruposervicio',
            gruposervicio = '$gruposervicio',
            codservicio = '$codservicio',
            finalidadtecnologiasalud = '$finalidadtecnologiasalud',
            causamotivoatencion = '$causamotivoatencion',
            coddiagnosticoprincipal = '$coddiagnosticoprincipal',
            coddiagnosticorelacionado1 = '$coddiagnosticorelacionado1',
            coddiagnosticorelacionado2 = '$coddiagnosticorelacionado2',
            coddiagnosticorelacionado3 = '$coddiagnosticorelacionado3',
            tipodiagnosticoprincipal = '$tipodiagnosticoprincipal',
            vrservicio = '$vrservicio',
            conceptorecaudo = '$conceptorecaudo',
            valorpagomoderador = '$valorpagomoderador',
            numfevpagomoderador = '$numfevpagomoderador'            
        WHERE id_detalle='$id_detalle'";
    //echo $sql;
    if(mysqli_query($conexion, $sql)) {
        echo "Consulta actualizada correctamente";
    } else {
        echo "Error al actualizar la consulta: " . mysqli_error($conexion);
    }
}

function traerProcedimientos($id_factura) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_procedimiento, fechainicioatencion, numautorizacion,idmipres,
            codprocedimiento, finalidadtecnologiasalud, coddiagnosticoprincipal,
            vrservicio 
            FROM nrprocedimientos 
            WHERE id_factura = '$id_factura'";
    //echo $sql;   
    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }    
    
    return(json_encode($data));
}

function traerProcedimientoPorId($id_procedimiento) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_procedimiento,fechainicioatencion,idmipres,numautorizacion,
        codprocedimiento,viaingresoserviciosalud,modalidadgruposerviciotecsal,
        gruposervicios,codservicio,finalidadtecnologiasalud,tipodocumentoidentificacion,
        numdocumentoidentificacion,coddiagnosticoprincipal,coddiagnosticorelacionado,
        codcomplicacion,vrservicio,conceptorecaudo,valorpagomoderador,numfevpagomoderador,
        consecutivo,id_factura,id_detfac
        FROM nrprocedimientos 
        WHERE id_procedimiento = '$id_procedimiento'";

    //echo $sql;            
    $result = mysqli_query($conexion,$sql);    
    
    $data = array();
    $row = mysqli_fetch_assoc($result);
    $data = $row;
        
    return(json_encode($data));
}

function guardarProcedimiento($datos){
    $obj = new conectar();
    $conexion = $obj->conexion();

    $id_procedimiento = mysqli_real_escape_string($conexion, $datos['id_procedimiento']);
    $fechainicioatencion = mysqli_real_escape_string($conexion, $datos['fechainicioatencion']);
    $idmipres = mysqli_real_escape_string($conexion, $datos['idmipres']);
    $numautorizacion = mysqli_real_escape_string($conexion, $datos['numautorizacion']);
    $codprocedimiento = mysqli_real_escape_string($conexion, $datos['codprocedimiento']);
    $viaingresoserviciosalud = mysqli_real_escape_string($conexion, $datos['viaingresoserviciosalud']);
    $modalidadgruposerviciotecsal = mysqli_real_escape_string($conexion, $datos['modalidadgruposerviciotecsal']);
    $gruposervicios = mysqli_real_escape_string($conexion, $datos['gruposervicios']);
    $codservicio = mysqli_real_escape_string($conexion, $datos['codservicio']);
    $finalidadtecnologiasalud = mysqli_real_escape_string($conexion, $datos['finalidadtecnologiasalud']);
    //$tipodocumentoidentificacion = mysqli_real_escape_string($conexion, $datos['tipodocumentoidentificacion']);
    //$numdocumentoidentificacion = mysqli_real_escape_string($conexion, $datos['numdocumentoidentificacion']);
    $coddiagnosticoprincipal = mysqli_real_escape_string($conexion, $datos['coddiagnosticoprincipal']);
    $coddiagnosticorelacionado = mysqli_real_escape_string($conexion, $datos['coddiagnosticorelacionado']);
    $codcomplicacion = mysqli_real_escape_string($conexion, $datos['codcomplicacion']);
    $vrservicio = mysqli_real_escape_string($conexion, $datos['vrservicio']);
    $conceptorecaudo = mysqli_real_escape_string($conexion, $datos['conceptorecaudo']);
    $valorpagomoderador = mysqli_real_escape_string($conexion, $datos['valorpagomoderador']);
    $numfevpagomoderador = mysqli_real_escape_string($conexion, $datos['numfevpagomoderador']);    
    $id_detfac = mysqli_real_escape_string($conexion, $datos['id_detfac']);
    
    $sql= "UPDATE nrprocedimientos SET 
            fechainicioatencion = '$fechainicioatencion',
            idmipres = '$idmipres',
            numautorizacion = '$numautorizacion',
            codprocedimiento = '$codprocedimiento',
            viaingresoserviciosalud = '$viaingresoserviciosalud',
            modalidadgruposerviciotecsal = '$modalidadgruposerviciotecsal',
            gruposervicios = '$gruposervicios',
            codservicio = '$codservicio',
            finalidadtecnologiasalud = '$finalidadtecnologiasalud',            
            coddiagnosticoprincipal = '$coddiagnosticoprincipal',
            coddiagnosticorelacionado = '$coddiagnosticorelacionado',
            codcomplicacion = '$codcomplicacion',
            vrservicio = '$vrservicio',
            conceptorecaudo = '$conceptorecaudo',
            valorpagomoderador = '$valorpagomoderador',
            numfevpagomoderador = '$numfevpagomoderador'
        WHERE id_detfac='$id_detfac'";
    //echo $sql;
    if(mysqli_query($conexion, $sql)) {
        echo "Procedimiento actualizado correctamente";
    } else {
        echo "Error al actualizar el procedimiento: " . mysqli_error($conexion);
    }
}

function traerOtServicios($id_factura) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_otroservicio,numautorizacion,idmipres,fechasuministrotecnologia,
            tipoos,codtecnologia,nomtecnologia,cantidados,vrunitos,vrservicio,conceptorecaudo,
            valorpagomoderador,numfevpagomoderador,consecutivo,id_factura,id_detalle
            FROM nrotroservicios
            WHERE id_factura = '$id_factura'";
    //echo $sql;   
    $result = mysqli_query($conexion,$sql);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }    
    
    return(json_encode($data));
}

function traerOtServicioPorId($id_otroservicio) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT id_otroservicio,numautorizacion,idmipres,fechasuministrotecnologia,
        tipoos,codtecnologia,nomtecnologia,cantidados,vrunitos,vrservicio,conceptorecaudo,
        valorpagomoderador,numfevpagomoderador,consecutivo,id_factura,id_detalle
        FROM nrotroservicios    
        WHERE id_otroservicio = '$id_otroservicio'";

    //echo $sql;            
    $result = mysqli_query($conexion,$sql);    
    
    $data = array();
    $row = mysqli_fetch_assoc($result);
    $data = $row;
        
    return(json_encode($data));
}

function guardarOtServicio($datos){
    $obj = new conectar();
    $conexion = $obj->conexion();

    $id_otroservicio = mysqli_real_escape_string($conexion, $datos['id_otroservicio']);
    $numautorizacion = mysqli_real_escape_string($conexion, $datos['numautorizacion']);
    $idmipres = mysqli_real_escape_string($conexion, $datos['idmipres']);
    $fechasuministrotecnologia = mysqli_real_escape_string($conexion, $datos['fechasuministrotecnologia']);
    $tipoos = mysqli_real_escape_string($conexion, $datos['tipoos']);
    $codtecnologia = mysqli_real_escape_string($conexion, $datos['codtecnologia']);
    $nomtecnologia = mysqli_real_escape_string($conexion, $datos['nomtecnologia']);
    $cantidados = mysqli_real_escape_string($conexion, $datos['cantidados']);
    $vrunitos = mysqli_real_escape_string($conexion, $datos['vrunitos']);
    $vrservicio = $cantidados* $vrunitos; // Calcular el valor del servicio
    $conceptorecaudo = mysqli_real_escape_string($conexion, $datos['conceptorecaudo']);
    $valorpagomoderador = mysqli_real_escape_string($conexion, $datos['valorpagomoderador']);
    if($valorpagomoderador=='' || $valorpagomoderador==null) {
        $valorpagomoderador = 0; // Si no se proporciona, establecer en 0
    }
    $numfevpagomoderador = mysqli_real_escape_string($conexion, $datos['numfevpagomoderador']); 

    $sql= "UPDATE nrotroservicios SET 
            numautorizacion = '$numautorizacion',
            idmipres = '$idmipres',
            fechasuministrotecnologia = '$fechasuministrotecnologia',
            tipoos = '$tipoos',
            codtecnologia = '$codtecnologia',
            nomtecnologia = '$nomtecnologia',
            cantidados = '$cantidados',
            vrunitos = '$vrunitos',
            vrservicio = '$vrservicio',
            conceptorecaudo = '$conceptorecaudo',
            valorpagomoderador = '$valorpagomoderador',
            numfevpagomoderador = '$numfevpagomoderador'
        WHERE id_otroservicio='$id_otroservicio'";
    
    //echo $sql;
    if(mysqli_query($conexion, $sql)) {
        echo "Procedimiento actualizado correctamente";
    } else {
        echo "Error al actualizar el procedimiento: " . mysqli_error($conexion);
    }
}

function traerRipsJs($id_factura) {
    //echo $id_factura;
    $obj=new conectar();
    $conexion=$obj->conexion();

    // Traer datos de la entidad
    $sql = "select numeroiden_ent as numDocumentoldObligado 
        from entidad e";
    $result = mysqli_query($conexion,$sql);
    
    $data = array();
    $row = mysqli_fetch_assoc($result);
    $numDocumentoldObligado=$row['numDocumentoldObligado'];

    // trae datos de la factura
    $sql = "SELECT $numDocumentoldObligado as numDocumentoIdObligado, numero_fac as numFactura, null as tipoNota,null as numNota
        FROM factura_encabezado fe
        WHERE fe.id_factura = '$id_factura'";

    //echo $sql;
    $result = mysqli_query($conexion,$sql);
    
    $rips = array();
    $row = mysqli_fetch_assoc($result);
    $rips = $row;

    // Traer datos del usuario
    $ripsUs=traerRipsUs($id_factura);
    $rips['usuarios'] = $ripsUs;

    echo "<br><br><pre>".json_encode($rips);
    return(json_encode($rips));
}

function traerRipsUs($id_factura) {
    $obj=new conectar();
    $conexion=$obj->conexion();

    $sql = "SELECT tipo_documento as tipoDocumentoIdentificacion, numdocumento as numDocumentoIdentificacion, 
    tipousuario as tipoUsuario, fechanacimiento as fechaNacimiento, codsexo as codSexo, 
    codpaisresidencia as codPaisResidencia, codmunicipioresidencia as codMunicipioResidencia,
    codzonaresidencia as codZonaTerritorialResidencia, incapacidad, 
    ROW_NUMBER() OVER (ORDER BY nru.id_usuario) AS consecutivo,
    codpaisorigen as codPaisOrigen
        
        FROM nrusuario nru
        inner join factura_encabezado fe on fe.id_factura = nru.id_factura 
        
        WHERE nru.id_factura = '$id_factura'";
    
    //echo $sql;
    
    $result = mysqli_query($conexion,$sql);
    $ripsUs = array();    
    while($row = mysqli_fetch_assoc($result)) {
        $row['consecutivo'] = (int)$row['consecutivo'];        
        if($row['codMunicipioResidencia'] == '') {$row['codMunicipioResidencia'] = null;}
        $servicios=traerServicios($id_factura,$row['tipoDocumentoIdentificacion'],$row['numDocumentoIdentificacion']);

        if(!empty($servicios)) {
            $row['servicios'] = $servicios;
        }
        $ripsUs[] = $row;
    }    

    //echo "Rips Us:... ".json_encode($ripsUs);
    return($ripsUs);
}

function traerServicios($id_factura,$tipoDocumentoIdentificacion,$numDocumentoIdentificacion) {
    
    $servicios = array();

    $obj=new conectar();
    $conexion=$obj->conexion();    
    $servicios = array();
    // Traer datos de la entidad
    $sql = "SELECT codigopres_ent
        FROM entidad";
    $result = mysqli_query($conexion,$sql);
    $row = mysqli_fetch_assoc($result);
    $codigopres_ent = $row['codigopres_ent'];
    //tipoDocumentoIdentificacion    

    // Traer consultas
    $sql = "SELECT '$codigopres_ent' as codPrestador, fechainicioatencion as fechaInicioAtencion, 
    numautorizacion as numAutorizacion, codconsulta as codConsulta, modalidadgruposervicio as modalidadGrupoServicioTecSal, 
    gruposervicio as grupoServicios, codservicio as codServicio, finalidadtecnologiasalud as finalidadTecnologiaSalud, 
    causamotivoatencion as causaMotivoAtencion, coddiagnosticoprincipal as codDiagnosticoPrincipal,
    coddiagnosticorelacionado1 as codDiagnosticoRelacionado1, coddiagnosticorelacionado2 as codDiagnosticoRelacionado2,
    coddiagnosticorelacionado3 as codDiagnosticoRelacionado3,tipodiagnosticoprincipal as tipoDiagnosticoPrincipal,
    '$tipoDocumentoIdentificacion' as tipoDocumentoIdentificacion,
    '$numDocumentoIdentificacion' as numDocumentoIdentificacion, vrservicio as vrServicio,
    conceptorecaudo as conceptoRecaudo, valorpagomoderador as valorPagoModerador,
    numfevpagomoderador as numFEVPagoModerador,consecutivo
    FROM nrconsulta WHERE id_factura = '$id_factura'";
    //echo "<pre>".$sql;
    
    $result = mysqli_query($conexion,$sql);
    $ripsAc = array();
    while($row = mysqli_fetch_assoc($result)) {
        $row['codServicio'] = (int)$row['codServicio'];
        $row['fechaInicioAtencion'] = str_replace("T", " ", $row['fechaInicioAtencion']);
        if($row['numAutorizacion'] == '') {$row['numAutorizacion'] = null;} // Si no hay autorización, establecer como null
        if($row['codDiagnosticoRelacionado1'] == '') {$row['codDiagnosticoRelacionado1'] = null;} // Si no hay diagnóstico relacionado, establecer como null 
        if($row['codDiagnosticoRelacionado2'] == '') {$row['codDiagnosticoRelacionado2'] = null;} // Si no hay diagnóstico relacionado, establecer como null
        if($row['codDiagnosticoRelacionado3'] == '') {$row['codDiagnosticoRelacionado3'] = null;} // Si no hay diagnóstico relacionado, establecer como null
        if($row['numFEVPagoModerador'] == '') {$row['numFEVPagoModerador'] = null;} // Si no hay diagnóstico relacionado, establecer como null
        $row['vrServicio'] = (int)$row['vrServicio'];
        $row['valorPagoModerador'] = (int)$row['valorPagoModerador'];
        $row['consecutivo'] = (int)$row['consecutivo'];
        $ripsAc[] = $row;
    }

    if($ripsAc<>''){
        $servicios['consultas'] = $ripsAc;
        //echo "<br>RIPS Servicios: ".json_encode($servicios);
    }    
    //echo "<br><br>RIPS AC: ".json_encode($ripsAc);

    // Traer procedimientos
    $sql = "SELECT '$codigopres_ent' as codPrestador, fechainicioatencion as fechaInicioAtencion,
    idmipres as idMIPRES, numautorizacion as numAutorizacion, codprocedimiento as codProcedimiento,
    viaingresoserviciosalud as viaIngresoServicioSalud, modalidadgruposerviciotecsal as modalidadGrupoServicioTecSal,
    gruposervicios as grupoServicios, codservicio as codServicio, finalidadtecnologiasalud as finalidadTecnologiaSalud,
    '$tipoDocumentoIdentificacion' as tipoDocumentoIdentificacion,
    '$numDocumentoIdentificacion' as numDocumentoIdentificacion,
    coddiagnosticoprincipal as codDiagnosticoPrincipal, coddiagnosticorelacionado as codDiagnosticoRelacionado,
    codcomplicacion as codComplicacion, vrservicio as vrServicio, conceptorecaudo as conceptoRecaudo,
    valorpagomoderador as valorPagoModerador, numfevpagomoderador as numFEVPagoModerador, consecutivo
    FROM nrprocedimientos 
    WHERE id_factura = '$id_factura'";
    //echo "<br>".$sql;

    $result = mysqli_query($conexion,$sql);
    $ripsAp = array();

    while($row = mysqli_fetch_assoc($result)) {
        $row['codServicio'] = (int)$row['codServicio'];
        $row['fechaInicioAtencion'] = str_replace("T", " ", $row['fechaInicioAtencion']);
        $row['idMIPRES'] = (int)$row['idMIPRES'];
        if($row['idMIPRES'] == 0) {$row['idMIPRES'] = null;} // Si no hay MIPRES, establecer como null
        if($row['numAutorizacion'] == '') {$row['numAutorizacion'] = null;} // Si no hay autorización, establecer como null
        if($row['codDiagnosticoRelacionado'] == '') {$row['codDiagnosticoRelacionado'] = null;} // Si no hay diagnóstico relacionado, establecer como null 
        if($row['codComplicacion'] == '') {$row['codComplicacion'] = null;} // Si no hay complicación, establecer como null
        if($row['numFEVPagoModerador'] == '') {$row['numFEVPagoModerador'] = null;} // Si no hay diagnóstico relacionado, establecer como null
        $row['vrServicio'] = (int)$row['vrServicio'];
        $row['valorPagoModerador'] = (int)$row['valorPagoModerador'];
        $row['consecutivo'] = (int)$row['consecutivo'];
        $ripsAp[] = $row;
    }
    if($ripsAp<>''){
        $servicios['procedimientos'] = $ripsAp;
        //echo "<br>RIPS Servicios: ".json_encode($servicios);
    }
    //echo "<br><br>RIPS AP: ".json_encode($ripsAp);

    // Traer otros servicios
    $sql = "SELECT '$codigopres_ent' as codPrestador, numautorizacion as numAutorizacion, idmipres as idMIPRES,
    fechasuministrotecnologia as fechaSuministroTecnologia, tipoos as tipoOS, codtecnologia as codTecnologiaSalud,
    nomtecnologia as nomTecnologiaSalud, cantidados as cantidadOS, 
    '$tipoDocumentoIdentificacion' as tipoDocumentoIdentificacion,
    '$numDocumentoIdentificacion' as numDocumentoIdentificacion,
    vrunitos as vrUnitOS,vrservicio as vrServicio, conceptorecaudo as conceptoRecaudo,
    valorpagomoderador as valorPagoModerador, numfevpagomoderador as numFEVPagoModerador, consecutivo
    FROM nrotroservicios
    WHERE id_factura = '$id_factura'";
    //echo "<br>".$sql;

    $result = mysqli_query($conexion,$sql);
    $ripsAt = array();
    while($row = mysqli_fetch_assoc($result)) {
        $row['vrUnitOS'] = (int)$row['vrUnitOS'];
        $row['vrServicio'] = (int)$row['vrServicio'];
        $row['idMIPRES'] = (int)$row['idMIPRES'];
        $row['fechaSuministroTecnologia'] = str_replace("T", " ", $row['fechaSuministroTecnologia']);
        if($row['numAutorizacion'] == '') {$row['numAutorizacion'] = null;} // Si no hay autorización, establecer como null 
        if($row['numFEVPagoModerador'] == 0) {$row['numFEVPagoModerador'] = null;} // Si no hay diagnóstico relacionado, establecer como null
        if($row['idMIPRES'] == 0) {$row['idMIPRES'] = null;} // Si no hay MIPRES, establecer como null
        if($row['numFEVPagoModerador']== '') {$row['numFEVPagoModerador'] = null;} // Si no hay diagnóstico relacionado, establecer como null
        $row['cantidadOS'] = (int)$row['cantidadOS'];
        $row['valorPagoModerador'] = (int)$row['valorPagoModerador'];        
        $row['consecutivo'] = (int)$row['consecutivo'];
        $ripsAt[] = $row;
    }
    if($ripsAt<>''){
        $servicios['otrosServicios'] = $ripsAt;    
    }
    //echo "<br><br>RIPS AT: ".json_encode($ripsAt);
    return($servicios);
}
?>