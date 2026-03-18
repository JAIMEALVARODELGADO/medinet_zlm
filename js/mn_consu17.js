async function traePlantilla() {
    const plantilla = document.getElementById("tipo_proc").value;

    // Validación del input
    if (!plantilla) {
        console.warn("No se seleccionó ninguna plantilla.");
        return;
    }

    const formData = new FormData();
    formData.append('plantilla', plantilla);
    formData.append('opcion', 'traerPlantilla');

    try {
        const response = await fetch('procesos/crudProcedMenores.php', {
            method: 'POST',
            body: formData
        });

        // Verificar que la respuesta HTTP sea exitosa
        if (!response.ok) {
            throw new Error(`Error del servidor: ${response.status}`);
        }

        const data = await response.json();
        console.log("Respuesta completa del servidor:", data);
        // Verificar que el array no esté vacío
        if (!data || !data.success) {
            console.warn("No se encontró plantilla:", data?.message);
            return;
        }

        mostrarPlantilla(data);

    } 
    catch (error) {
        
        console.error("Error al traer la plantilla:", error);
    }
}

function mostrarPlantilla(data) {
    const campo = document.getElementById("descripcion");

    if (!campo) {
        console.error("El elemento 'descripcion' no existe en el DOM.");
        return;
    }

    if (!data?.success) {
        console.warn("Plantilla no encontrada:", data?.message);
        return;
    }

    campo.value = data.descripcion;
}