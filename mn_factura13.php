<?php
require(__DIR__ . '/../valida_sesion.php');
require_once "clases/conexion.php";
$obj=new conectar();
$conexion=$obj->conexion();
$hoy=date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Medinet V3</title>
	<?php 
		require_once "scripts.php";
	?>
    <script src="mn_factura13.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .upload-section {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            background-color: #fafafa;
        }
        
        .upload-section.dragover {
            border-color: #007bff;
            background-color: #f0f8ff;
        }
        
        input[type="file"] {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        
        .results {
            margin-top: 30px;
        }
        
        .json-preview {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            white-space: pre-wrap;
        }
        
        .status {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status.info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .invoice-summary {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
        }
        
        .invoice-summary h3 {
            margin-top: 0;
            color: #007bff;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .summary-label {
            font-weight: bold;
        }
    </style>
</head>

<body>
	<?php
	require("encabezado.php");
	require("menu.php")
	?>


	<div class="card text">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="nav-link" href="mn_factura1.php">Crear Nueva Factura</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_factura11.php">Facturas Abiertas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mn_factura12.php">Facturas Cerradas/Anuladas</a>
				</li>
                <li class="nav-item">
					<a class="nav-link active" href="#">Subir XML</a>
				</li>					
			</ul>

		</div>       
		<br>
		<div class="container">
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="card text-left">
	                    <div class="card-header">
	                        <h4>Subir Factura XML</h4>
	                    </div>
                    </div>                    
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-6">                                
                                <label for="archivo" class="col-form-label"><p>Arrastra y suelta un archivo XML aquí o haz clic para seleccionar</p></label>
                                <input type="file" id="archivoXml" name="archivoXml" accept=".xml" class="form-control-file">
                            </div>
                            
                            <div class="col-sm-6">
                                <label for="id_eps" class="col-form-label">Convenio con la EPS</label>
                                <select class="form-control form-control-sm" id="id_convenio" name="id_convenio">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-primary" onclick="procesarXML()">Procesar XML</button>
                            </div>
                        </div>

                        <div id="status"></div>
                            
                        <div class="results" id="results" style="display: none;">
                            <h3>Resumen de la Factura</h3>
                            <div id="invoiceSummary"></div>
                            
                            <h3>Vista previa JSON</h3>
                            <div class="json-preview" id="jsonPreview"></div>
                            
                            <button onclick="crearFactura()" id="sendBtn" disabled>Crear Factura</button>
                            <!--<button onclick="downloadJSON()" id="downloadBtn" disabled>Descargar JSON</button>-->
                        </div>
                    </div>
                </div>

                </div>
	                    <div class="card-footer text-muted">
	                        By Soluciones Thin & Thin
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>		
	</div>

	
    <form id="frm_factura" name="frm_factura" method="POST">
    	<input type="hidden" id="condicion" name="condicion">
        <input type="hidden" id="id_factura" name="id_factura">
    </form>
</body>

</html>
