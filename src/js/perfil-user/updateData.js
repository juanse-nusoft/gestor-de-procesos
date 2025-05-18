document.addEventListener('DOMContentLoaded', function () {
    const campoContrasena = document.getElementById('campo-contrasena');
    const formulario = document.getElementById('formulario');
    const togglePassword = document.getElementById('toggle-password');

    // Enviar formulario con JavaScript
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formulario);

        //remover la contraseña si no está visible
        if (!togglePassword.classList.contains('active')) {
            formData.delete('nueva_contrasena');
        }

        fetch('/dashboard/usuarios/perfil', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Tu perfil ha sido actualizado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Hubo un problema al actualizar los datos'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor'
            });
        });
    });
});
