document.addEventListener('DOMContentLoaded', function() {
    const opcionesPerfil = document.querySelector('.opciones-perfil');
    const modalOpciones = document.getElementById('modalOpcionesPerfil');
    const inputFoto = document.getElementById('inputFotoPerfil');
    const fotoPerfil = document.querySelector('.foto-perfil');
    const eliminarFotoBtn = document.getElementById('eliminarFoto');
    const actualizarFotoBtn = document.getElementById('actualizarFoto');
    
    // Mostrar/ocultar modal
    opcionesPerfil.addEventListener('click', function(e) {
        e.stopPropagation();
        modalOpciones.style.display = 'flex';
    });
    
    // Cerrar modal al hacer clic fuera o en cancelar
    modalOpciones.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-opciones-perfil') || 
            e.target.classList.contains('cerrar-modal')) {
            modalOpciones.style.display = 'none';
        }
    });
});