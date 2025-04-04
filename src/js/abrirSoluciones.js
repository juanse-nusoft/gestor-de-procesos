
// Agregar evento de clic a las filas de la tabla
document.querySelectorAll('.fila-solucion').forEach(fila => {
    fila.addEventListener('click', (event) => {
        // Evitar la redirección si se hace clic en los botones de editar/eliminar
        if (event.target.tagName === 'A' || event.target.tagName === 'I') {
            return;
        }
        // Obtener el ID de la solución desde el atributo data-id
        const id = fila.getAttribute('data-id');
        // Redirigir a la página de detalles
        window.location.href = `/dashboard/soluciones/detalle?id=${id}`;
    });
});

document.addEventListener('click', function(event) {
    let elemento = event.target.closest('a.copiar'); // Busca el <a> más cercano con la clase "copiar"

    if (elemento) {
        event.preventDefault(); // Evita la navegación
        
        let enlace = elemento.href; // Obtiene el href del enlace clickeado

        // Copiar al portapapeles
        navigator.clipboard.writeText(enlace)
            .then(() => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Copiado",
                    showConfirmButton: false,
                    timer: 1200
                });
            })
            .catch(err => {
                console.error('Error al copiar:', err);
            });
    }
});

