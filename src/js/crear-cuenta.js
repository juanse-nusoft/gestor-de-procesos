import { validarEmail, validarClave, validarNombre, validarApellido, validarTelefono } from '/build/js/formularios/validaciones.js';

document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formularioEnviar');

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        // Ejecutar las validaciones
        const esClaveValida = validarClave();
        const esEmailValido = validarEmail();
        const esTelefonoValido = validarTelefono();
        const esApellidoValido = validarApellido();
        const esNombreValido = validarNombre();

        // Si todas las validaciones son correctas, enviar el formulario
        if (esNombreValido && esApellidoValido && esTelefonoValido && esEmailValido && esClaveValida) {
            formulario.submit();
        }
    });
});


