document.addEventListener('DOMContentLoaded', () => {
    // Lógica para eliminar y recargar la página
    document.querySelectorAll('.tabla-acciones.eliminar').forEach(boton => {
        boton.addEventListener('click', (event) => {
            event.preventDefault(); // Evita la redirección predeterminada del enlace
            // Confirmación antes de eliminar
            if (!confirm('¿Estás seguro de que deseas eliminar esta solución?')) {
                return;
            }

            // Obtener el ID de la solución
            const id = boton.closest('.fila-solucion').getAttribute('data-id');

            // Enviar petición DELETE al servidor
            fetch(`/dashboard/soluciones`, {
                method: 'POST', // O DELETE si el backend lo permite
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json()) // Convertir la respuesta a JSON
            .then(data => {
                if (data.success) {
                    alert('Solución eliminada correctamente');
                    window.location.reload(); // Recargar la página después de eliminar
                } else {
                    alert('Error al eliminar la solución');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
})