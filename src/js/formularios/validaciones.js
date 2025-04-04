export function validarEmail() {
    const email = document.getElementById('email').value.trim();
    if (!email.includes('@nusoft.com.co')) {
        Swal.fire({
            icon: "error",
            title: "Fallaste...",
            text: "Correo no permitido",
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
            }
        });
        return false;
    }
    return true;
}

export function validarClave() {
    const clave = document.getElementById('password').value.trim();
    if (clave.length < 8) {
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: "Ingresa una contraseña válida, debe tener como mínimo 8 caracteres",
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
            }
        });
        return false;
    }
    return true;
}

export function validarNombre() {
    const nombre = document.getElementById('nombre').value.trim();
    if (nombre === '') {
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: "Llena el campo Nombre",
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
            }
        });
        return false;
    }
    return true;
}

export function validarApellido() {
    const apellido = document.getElementById('apellido').value.trim();
    if (apellido === '') {
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: "Llena el campo Apellido",
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
            }
        });
        return false;
    }
    return true;
}

export function validarTelefono() {
    const telefono = document.getElementById('telefono').value.trim();
    if (telefono === '' || isNaN(telefono)) {
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: "Teléfono no válido",
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
            }
        });a
        return false;
    }
    return true;
}
