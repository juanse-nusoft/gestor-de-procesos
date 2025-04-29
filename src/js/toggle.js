
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

