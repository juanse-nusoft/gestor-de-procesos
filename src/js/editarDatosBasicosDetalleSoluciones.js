
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('formulario-solucion');
    
    formulario.addEventListener('submit', async (e) => {
        e.preventDefault();

            // Validar datos antes de enviar
        const estado = formulario.elements.estado.value;
        const categoria = formulario.elements.categoria.value;
        
        if (!estado || !categoria) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor complete todos los campos requeridos',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
        
        // Mostrar loader (opcional)
        /*
        const boton = e.target.querySelector('button[type="submit"]');
        const textoOriginal = boton.textContent;
        boton.disabled = true;
        boton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Actualizando...';
        */
        try {
            // Crear FormData directamente del formulario
            const formData = new FormData(formulario);
            
            // Convertir a objeto
            const datos = Object.fromEntries(formData.entries());
            
            const respuesta = await fetch(formulario.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Para enviar JSON
                    'Accept': 'application/json' // Para recibir JSON
                },
                body: JSON.stringify(datos) // Convertir a JSON
            });
            
            const data = await respuesta.json();
            
            if (!respuesta.ok) {
                throw new Error(data.message || 'Error en la respuesta del servidor');
            }
            
            // Mostrar notificación de éxito
            await Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: data.message || 'Los cambios se guardaron correctamente',
                confirmButtonText: 'Aceptar'
            });
            
            // Redirigir si es necesario
           /* if (data.redirect) {
                window.location.href = data.redirect;
            }
            */
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Ocurrió un error al guardar los cambios',
                confirmButtonText: 'Aceptar'
            });
            
        } /*finally {
            // Restaurar botón
            boton.disabled = false;
            boton.textContent = textoOriginal;
        }
            */
    });
});