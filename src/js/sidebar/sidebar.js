const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggleBtn");

// Alternar expansión del sidebar
toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("expanded");
});

document.addEventListener('DOMContentLoaded', () => {
    let body = document.body;

    // Función para aplicar el modo oscuro desde localStorage
    function aplicarModoOscuro() {
        let modoOscuro = localStorage.getItem('modo-oscuro');

        if (modoOscuro === 'activado') {
            body.classList.add('active');
        } else {
            body.classList.remove('active');
        }
    }

    aplicarModoOscuro(); // Se ejecuta en todas las páginas
});

