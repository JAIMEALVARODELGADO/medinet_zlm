window.onload = function() {
    cargarConvenios();
    let factura;
};


function procesarXML(){    
    const fileInput = document.getElementById('archivoXml');
    const file = fileInput.files[0];
    
    if (!file) {
        showStatus('Por favor selecciona un archivo XML', 'error');
        return;
    }
    
    if (!file.name.toLowerCase().endsWith('.xml')) {
        showStatus('Por favor selecciona un archivo XML válido', 'error');
        return;
    }
    
    showStatus('Procesando archivo XML...', 'info');
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const xmlContent = e.target.result;            
            const parser = new DOMParser();            
            const xmlDoc = parser.parseFromString(xmlContent, 'text/xml');            
            // Verificar si hay errores en el parsing
            const parseError = xmlDoc.querySelector('parsererror');            
            if (parseError) {
                throw new Error('Error al parsear el XML: ' + parseError.textContent);
            }
            
            // Extraer datos de la factura
            const invoiceData = extractInvoiceData(xmlDoc);
            //console.log(invoiceData);
            processedData = invoiceData;
            
            // Mostrar resultados
            displayResults(invoiceData);
            showStatus('Archivo procesado exitosamente', 'success');
            
        } catch (error) {
            showStatus('Error al procesar el archivo: ' + error.message, 'error');
            console.error('Error:', error);
        }
    };
    
    reader.readAsText(file);
}

function showStatus(message, type = 'info') {
    const statusDiv = document.getElementById('status');
    statusDiv.innerHTML = `<div class="status ${type}">${message}</div>`;
}

function extractInvoiceData(xmlDoc) {
    // Función auxiliar para obtener texto de un elemento
    function getElementText(parent, tagName, namespaceURI = null) {
        const elements = parent.getElementsByTagNameNS(namespaceURI || '*', tagName);
        return elements.length > 0 ? elements[0].textContent.trim() : '';
    }
    
    // Función auxiliar para obtener atributo
    function getElementAttribute(parent, tagName, attributeName, namespaceURI = null) {
        const elements = parent.getElementsByTagNameNS(namespaceURI || '*', tagName);
        return elements.length > 0 ? elements[0].getAttribute(attributeName) || '' : '';
    }
    
    const invoice = xmlDoc.documentElement;
    
    // Información básica de la factura
    const basicInfo = {
        id: getElementText(invoice, 'ID'),
        uuid: getElementText(invoice, 'UUID'),
        issueDate: getElementText(invoice, 'IssueDate'),
        issueTime: getElementText(invoice, 'IssueTime'),
        invoiceTypeCode: getElementText(invoice, 'InvoiceTypeCode'),
        currencyCode: getElementText(invoice, 'DocumentCurrencyCode'),
        lineCount: parseInt(getElementText(invoice, 'LineCountNumeric')) || 0
    };
    
    // Información del proveedor
    const supplierParty = invoice.getElementsByTagNameNS('*', 'AccountingSupplierParty')[0];
    const supplier = {
        name: getElementText(supplierParty, 'Name'),
        registrationName: getElementText(supplierParty, 'RegistrationName'),
        companyId: getElementText(supplierParty, 'CompanyID'),
        taxLevelCode: getElementText(supplierParty, 'TaxLevelCode'),
        address: {
            cityName: getElementText(supplierParty, 'CityName'),
            postalZone: getElementText(supplierParty, 'PostalZone'),
            countrySubentity: getElementText(supplierParty, 'CountrySubentity'),
            addressLine: getElementText(supplierParty, 'Line')
        },
        contact: {
            name: getElementText(supplierParty, 'Name'),
            telephone: getElementText(supplierParty, 'Telephone'),
            email: getElementText(supplierParty, 'ElectronicMail')
        }
    };
    
    // Información del cliente
    const customerParty = invoice.getElementsByTagNameNS('*', 'AccountingCustomerParty')[0];
    const customer = {
        name: getElementText(customerParty, 'Name'),
        registrationName: getElementText(customerParty, 'RegistrationName'),
        companyId: getElementText(customerParty, 'CompanyID'),
        taxLevelCode: getElementText(customerParty, 'TaxLevelCode'),
        address: {
            cityName: getElementText(customerParty, 'CityName'),
            postalZone: getElementText(customerParty, 'PostalZone'),
            countrySubentity: getElementText(customerParty, 'CountrySubentity'),
            addressLine: getElementText(customerParty, 'Line')
        },
        contact: {
            name: getElementText(customerParty, 'Name'),
            telephone: getElementText(customerParty, 'Telephone'),
            email: getElementText(customerParty, 'ElectronicMail')
        }
    };
    
    // Información de pago
    const paymentMeans = invoice.getElementsByTagNameNS('*', 'PaymentMeans')[0];
    const payment = {
        id: getElementText(paymentMeans, 'ID'),
        paymentMeansCode: getElementText(paymentMeans, 'PaymentMeansCode'),
        paymentDueDate: getElementText(paymentMeans, 'PaymentDueDate')
    };
    
    // Totales monetarios
    const legalMonetary = invoice.getElementsByTagNameNS('*', 'LegalMonetaryTotal')[0];
    const totals = {
        lineExtensionAmount: parseFloat(getElementText(legalMonetary, 'LineExtensionAmount')) || 0,
        taxExclusiveAmount: parseFloat(getElementText(legalMonetary, 'TaxExclusiveAmount')) || 0,
        taxInclusiveAmount: parseFloat(getElementText(legalMonetary, 'TaxInclusiveAmount')) || 0,
        payableAmount: parseFloat(getElementText(legalMonetary, 'PayableAmount')) || 0
    };
    
    // Líneas de la factura
    const invoiceLines = invoice.getElementsByTagNameNS('*', 'InvoiceLine');
    const lines = [];
    
    for (let i = 0; i < invoiceLines.length; i++) {
        const line = invoiceLines[i];
        const lineData = {
            id: getElementText(line, 'ID'),
            quantity: parseFloat(getElementText(line, 'InvoicedQuantity')) || 0,
            unitCode: getElementAttribute(line, 'InvoicedQuantity', 'unitCode'),
            lineExtensionAmount: parseFloat(getElementText(line, 'LineExtensionAmount')) || 0,
            item: {
                description: getElementText(line, 'Description'),
                brandName: getElementText(line, 'BrandName'),
                modelName: getElementText(line, 'ModelName'),
                standardItemId: getElementText(line, 'ID')
            },
            price: {
                priceAmount: parseFloat(getElementText(line, 'PriceAmount')) || 0,
                baseQuantity: parseFloat(getElementText(line, 'BaseQuantity')) || 0
            }
        };
        lines.push(lineData);
    }
    
    // Información de autorización DIAN
    const dianExtensions = invoice.getElementsByTagNameNS('*', 'DianExtensions')[0];
    const authorization = {
        invoiceAuthorization: getElementText(dianExtensions, 'InvoiceAuthorization'),
        prefix: getElementText(dianExtensions, 'Prefix'),
        from: getElementText(dianExtensions, 'From'),
        to: getElementText(dianExtensions, 'To'),
        authorizationPeriod: {
            startDate: getElementText(dianExtensions, 'StartDate'),
            endDate: getElementText(dianExtensions, 'EndDate')
        },
        qrCode: getElementText(dianExtensions, 'QRCode')
    };
    
    return {
        basicInfo,
        supplier,
        customer,
        payment,
        totals,
        lines,
        authorization,
        originalXml: new XMLSerializer().serializeToString(xmlDoc),
        processedAt: new Date().toISOString()
    };
}

function displayResults(data) {        
    const resultsDiv = document.getElementById('results');
    const summaryDiv = document.getElementById('invoiceSummary');
    const jsonPreview = document.getElementById('jsonPreview');
    const id_convenio = document.getElementById('id_convenio').value;    

    // Crear el array de detalles basado en las líneas de la factura
    const detalle = data.lines.map(line => ({
        descripcion: line.item.description,
        cantidad: line.quantity,
        valor_unit: line.price.priceAmount
    }));

    //Aqui creo el objeto con los datos para la factura de medinet    
    factura = {
        numero_factura: data.basicInfo.id,
        id_persona: 0, // Este campo se debe completar con el ID de la persona
        id_convenio: id_convenio,
        fecha_fac: data.basicInfo.issueDate,
        valortot_fac: data.totals.payableAmount,
        detalle: detalle
    };
    //console.log(factura);

    // Mostrar resumen
    summaryDiv.innerHTML = `
        <div class="invoice-summary">
            <h3>Factura ${data.basicInfo.id}</h3>
            <div class="summary-row">
                <span class="summary-label">Fecha:</span>
                <span>${data.basicInfo.issueDate} ${data.basicInfo.issueTime}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Proveedor:</span>
                <span>${data.supplier.name}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Cliente:</span>
                <span>${data.customer.name}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total:</span>
                <span>$${data.totals.payableAmount.toLocaleString('es-CO')} ${data.basicInfo.currencyCode}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Líneas:</span>
                <span>${data.lines.length} productos</span>
            </div>
        </div>
    `;
    
    // Mostrar JSON
    //jsonPreview.textContent = JSON.stringify(data, null, 2);
    
    // Habilitar botones
    document.getElementById('sendBtn').disabled = false;
    //document.getElementById('downloadBtn').disabled = false;
    
    resultsDiv.style.display = 'block';
}

function crearFactura() {
    //console.log(factura);
    //alert();
    if (!processedData) {
        showStatus('No hay datos para enviar', 'error');
        return;
    }
    
    showStatus('Guardando factura...', 'info');

    var url = "procesos/factura_procesos.php?opcion=crearFacturaExterna"+
    "&factura="+encodeURIComponent(JSON.stringify(factura));
    //alert(url);
    fetchOptions={
        method: 'GET',
        headers: {				
            'Content-Type': 'application/json' 
        }
    }
    fetch(url, fetchOptions)		
    .then(response => response.text())
    .then(data => {			
        facturar(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });


    
    /*fetch('process_invoice.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(processedData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showStatus('Datos enviados y guardados exitosamente', 'success');
        } else {
            showStatus('Error al guardar datos: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showStatus('Error al enviar datos: ' + error.message, 'error');
    });*/
}

function cargarConvenios() {
    var url = "procesos/factura_procesos.php?opcion=traerConvenios";
    //alert(url);
    fetchOptions={
        method: 'GET',
        headers: {				
            'Content-Type': 'application/json' 
        }
    }
    fetch(url, fetchOptions)		
    .then(response => response.json())
    .then(data => {			
        cargarSeclectConvenios(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function cargarSeclectConvenios(data) {    
    var select = document.getElementById('id_convenio');
    select.innerHTML = ''; // Limpiar opciones existentes

    data.forEach(function(convenio) {
        var option = document.createElement('option');
        option.value = convenio.id_convenio;
        option.textContent = convenio.convenio_eps;
        select.appendChild(option);
    });    
}

function facturar(data) {

    if (data.success) {
        showStatus('Factura creada exitosamente', 'success');
        // Aquí puedes redirigir a otra página o mostrar un mensaje adicional
        // window.location.href = 'factura_success.html';
    } else {
        showStatus('Error al crear la factura: ' + data.message, 'error');
    }
}