/*document.addEventListener('DOMContentLoaded', () => {
    let toggle = document.getElementById('opciones-usuario'); // El toggle de Configuración
    let body = document.body;

    // Función para aplicar el modo oscuro basado en localStorage
    function aplicarModoOscuro() {
        let modoOscuro = localStorage.getItem('modoOscuro');

        if (modoOscuro === 'activado') {
            body.classList.add('active');
            if (toggle) toggle.classList.add('active'); // Reflejar el estado en el toggle
        } else {
            body.classList.remove('active');
            if (toggle) toggle.classList.remove('active');
        }
    }

    // Evento de clic para cambiar el modo oscuro
    if (toggle) {
        toggle.addEventListener('click', () => {
            let modoOscuroActivo = body.classList.toggle('active'); // Cambia el estado en el body
            
            // Actualiza el estado del toggle
            toggle.classList.toggle('active', modoOscuroActivo);

            // Guarda el estado en localStorage
            localStorage.setItem('modoOscuro', modoOscuroActivo ? 'activado' : 'desactivado');
        });
    }

    aplicarModoOscuro(); // Aplica la configuración guardada al cargar
});

*/

document.addEventListener('DOMContentLoaded', () => {
    let toggles = document.querySelectorAll('.toggle-switch');

    toggles.forEach(toggle => {
        let action = toggle.dataset.action;

        // Leer el estado guardado
        let estadoGuardado = localStorage.getItem(action);
        let activo = estadoGuardado === 'activado';

        // Aplicar clase si está activado
        if (activo) {
            toggle.classList.add('active');
        }

        // Ejecutar acción según el estado guardado
        switch (action) {
            case 'modo-oscuro':
                document.body.classList.toggle('active', activo);
                break;

            case 'notificaciones':
                console.log(`Notificaciones ${activo ? 'activadas' : 'desactivadas'}`);
                break;

            case 'idioma':
                console.log(`Cambiando idioma a ${activo ? 'Inglés' : 'Español'}`);
                break;
        }

        // Evento al hacer clic
        toggle.addEventListener('click', () => {
            toggle.classList.toggle('active');
            let activo = toggle.classList.contains('active');

            localStorage.setItem(action, activo ? 'activado' : 'desactivado');

            switch (action) {
                case 'modo-oscuro':
                    document.body.classList.toggle('active', activo);
                    break;

                case 'notificaciones':
                    console.log(`Notificaciones ${activo ? 'activadas' : 'desactivadas'}`);
                    break;

                case 'idioma':
                    console.log(`Cambiando idioma a ${activo ? 'Inglés' : 'Español'}`);
                    break;

                default:
                    console.log(`Acción ${action} no definida.`);
            }
        });
    });
});

