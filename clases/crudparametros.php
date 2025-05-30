<?php
class crudparametros{
    
    public function actualizar($datos){
        $obj=new conectar();
        $conexion=$obj->conexion();
        
        $sql="UPDATE parametros_generales SET 
              codigo_parametro='$datos[1]',
              titulo='$datos[2]' 
              WHERE id_parametro='$datos[0]'";
        
        $result=mysqli_query($conexion,$sql);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function obtenDatos($idparametro){
        $obj=new conectar();
        $conexion=$obj->conexion();
        
        $sql="SELECT id_parametro, nombre_parametro, codigo_parametro, descripcion, titulo, estado 
              FROM parametros_generales 
              WHERE id_parametro='$idparametro'";
        
        $result=mysqli_query($conexion,$sql);
        $ver=mysqli_fetch_row($result);
        
        return array(
            'id_parametro'=>$ver[0],
            'nombre_parametro'=>$ver[1],
            'codigo_parametro'=>$ver[2],
            'descripcion'=>$ver[3],
            'titulo'=>$ver[4],
            'estado'=>$ver[5]
        );
    }
    
    public function cambiarEstado($datos){
        $obj=new conectar();
        $conexion=$obj->conexion();
        
        $sql="UPDATE parametros_generales SET 
              estado='$datos[1]' 
              WHERE id_parametro='$datos[0]'";
        
        $result=mysqli_query($conexion,$sql);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }
}
?>