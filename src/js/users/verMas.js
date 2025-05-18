document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los botones "Ver más"
    const verMasBtns = document.querySelectorAll('.ver-mas');
    const modal = document.getElementById('usuarioModal');
    const closeModal = document.querySelector('.close-modal');
    const closeBtn = document.querySelector('.modal-btn.cerrar');
    
    // Función para abrir el modal con los datos del usuario
    function openModal(usuarioId) {
        // Aquí deberías obtener los datos del usuario con el ID proporcionado
        // Por ahora, simularemos que encontramos el usuario en el DOM
        
        const usuarioSection = document.querySelector(`.section-usuarios[data-id="${usuarioId}"]`);
        if (usuarioSection) {
            const nombre = usuarioSection.querySelector('.nombre-usuarios:nth-of-type(1)').textContent;
            const apellido = usuarioSection.querySelector('.nombre-usuarios:nth-of-type(2)').textContent;
            const email = usuarioSection.querySelector('.correo-usuarios').textContent;
            const division = usuarioSection.querySelector('.division-usuarios').textContent;
            const foto = usuarioSection.querySelector('.perfil-usuarios').src;
            
            // Llenar el modal con los datos
            document.getElementById('modalNombreCompleto').textContent = `${nombre} ${apellido}`;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalDivision').textContent = division;
            document.getElementById('modalProfilePhoto').src = foto;
            
            // Mostrar el modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Event listeners para los botones "Ver más"
    verMasBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const usuarioId = this.getAttribute('data-id');
            openModal(usuarioId);
        });
    });
    
    // Cerrar modal
    function closeModalFunc() {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    
    closeModal.addEventListener('click', closeModalFunc);
    closeBtn.addEventListener('click', closeModalFunc);
    
    // Cerrar al hacer clic fuera del modal
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalFunc();
        }
    });
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeModalFunc();
        }
    });
});