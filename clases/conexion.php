
<?php
	/**
	 * Conexion a la BD
	 */
	class conectar{
		public function conexion(){			
			$conexion=mysqli_connect('localhost','root','','medinet_zlm');
			//$conexion=mysqli_connect('https://www.bbcsolutions.co','bbcsolut_root','9@XtCu!XGN-b?_I}','bbcsolut_medinetbbc');			
			mysqli_set_charset($conexion,"utf8");
			return $conexion;
		}
	}

?>