
document.addEventListener('DOMContentLoaded', () => {

    const modoOscuro = window.matchMedia('(prefers-color-scheme: dark)');

    if (modoOscuro.matches) {
        document.body.classList.toggle('active');
    } else {
        console.log('La preferencia de color en el navegador es clara');
    }
});

