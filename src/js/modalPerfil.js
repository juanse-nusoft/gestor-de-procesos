document.addEventListener('DOMContentLoaded', function() {
    const opcionesPerfil = document.querySelector('.opciones-perfil');
    const modalOpciones = document.getElementById('modalOpcionesPerfil');
    const inputFoto = document.getElementById('inputFotoPerfil');
    const fotoPerfil = document.querySelector('.foto-perfil');
    const eliminarFotoBtn = document.getElementById('eliminarFoto');
    const subirFotoBtn = document.getElementById('subirFoto');
    const toggle = document.getElementById('toggle-password');
    const campoContrasena = document.getElementById('campo-contrasena');
    
    // Mostrar/ocultar modal
    opcionesPerfil.addEventListener('click', function(e) {
        e.stopPropagation();
        modalOpciones.style.display = 'flex';
    });
    
    subirFotoBtn.addEventListener('click', function() {
        modalOpciones.style.display = 'none';
        inputFoto.click();
    });

    // Cerrar modal al hacer clic fuera o en cancelar
    modalOpciones.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-opciones-perfil') || 
            e.target.classList.contains('cerrar-modal')) {
            modalOpciones.style.display = 'none';
        }
    });

    // Manejar la selección de imagen
    inputFoto.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            // Mostrar confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas cambiar tu foto de perfil?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    
                    // Enviar al servidor
                    subirImagenPerfil(this.files[0]);
                } else {
                    // Resetear el input si cancela
                    this.value = '';
                }
            });
        }
    });

    // Función para subir la imagen al servidor
    function subirImagenPerfil(file) {
        const formData = new FormData();
        formData.append('imagen', file);
        
        fetch('/dashboard/usuarios/upload-perfil', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Foto de perfil actualizada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Actualizar la imagen en la interfaz
                fotoPerfil.src = data.imagen;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Error al subir la imagen'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión con el servidor'
            });
            console.error('Error:', error);
        });
    }
    eliminarFotoBtn.addEventListener('click', function() {
    Swal.fire({
        title: '¿Eliminar foto de perfil?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarFotoPerfil();
        }
    });
});

function eliminarFotoPerfil() {
    fetch('/dashboard/usuarios/delete-perfil', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Para FormData
        },
        body: 'eliminar=true' // Envía como formulario básico
    })
    .then(response => {
        if (!response.ok) throw new Error("HTTP error " + response.status);
        return response.json().catch(() => {
            throw new Error("Respuesta no es JSON válido");
        });
    })
    .then(data => {
        if (data.success) {
            document.querySelector('.foto-perfil').src = data.imagen;
            Swal.fire('¡Éxito!', 'Foto eliminada', 'success');
            modalOpciones.style.display = 'none';
        } else {
            throw new Error(data.error || "Error desconocido");
        }
    })
    .catch(error => {
        console.error("Error completo:", error);
        Swal.fire('Error', error.message, 'error');
    });
}
    toggle.addEventListener('click', function() {
        this.classList.toggle('active');
        campoContrasena.style.display = this.classList.contains('active') ? 'flex' : 'none';
    });
});