
document.addEventListener('DOMContentLoaded', () => {

    const modoOscuro = window.matchMedia('(prefers-color-scheme: dark)');

    if (modoOscuro.matches) {
        document.body.classList.toggle('active');
    } else {
        console.log('El usuario prefiere el modo claro');
    }

});

