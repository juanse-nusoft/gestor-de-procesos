document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tabla-acciones.eliminar').forEach(boton => {
        boton.addEventListener('click', (event) => {
            event.preventDefault();

            // SweetAlert para confirmación
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿deseas eliminar este registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                customClass: {
                    confirmButton: 'alert-eliminar', // Clase personalizada para el botón de confirmar
                    cancelButton: 'alert-cancelar'   // Clase personalizada para el botón de cancelar
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const id = boton.closest('.fila-solucion').getAttribute('data-id');

                    fetch(`/dashboard/soluciones`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: 'Solución eliminada correctamente',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                window.location.reload(); // Recarga después de cerrar el alert
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo eliminar la solución',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error crítico',
                            text: 'Ocurrió un error inesperado',
                            icon: 'error'
                        });
                    });
                }
            });
        });
    });
});