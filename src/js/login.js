import {validarEmail, validarClave} from '/build/js/formularios/validaciones.js';

document.addEventListener('DOMContentLoaded', function(){
    const formulario = document.getElementById('formularioEnviar');

    formulario.addEventListener('submit', (e)=>{
        e.preventDefault();

        const claveValida = validarClave();
        const emailValido = validarEmail();

        if(claveValida && emailValido){
            formulario.submit();
        }
    })
});


/*
function iniciarApp(){
    validarLogin(); //Valida que los datos ingresados en el login cumplan con los requisitos establecidos
}

function validarLogin(){
    const btnIniciarSesion = document.querySelector('#enviar');
    
    btnIniciarSesion.addEventListener('click', (e) => {
        // Prevenir que el formulario se envíe al servidor
        e.preventDefault();

        const inputEmail = document.getElementById('email').value; // Obtener el valor del input
        const dominio = inputEmail.split('@'); // Dividir el correo por '@'
        const password = document.getElementById('password').value; 

        // Hacer las validaciones correspondientes
        if (dominio[1] !== 'nusoft.com.co') {
            alert("El email no es correcto");
            return; // Si la validación falla, no se sigue con el envío
        }
        if(password === ''){
            alert('Por favor, ingresa una contraseña');
            return;
        }

        // Si todo está bien, el formulario puede ser enviado
        document.getElementById('formularioEnviar').submit();
    });
}
*/
