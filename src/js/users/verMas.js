document.addEventListener('DOMContentLoaded', function() {
    const verMasBtns = document.querySelectorAll('.ver-mas');
    const modal = document.getElementById('usuarioModal');
    const closeModal = document.querySelector('.close-modal');
    const closeBtn = document.querySelector('.modal-btn.cerrar');
    
    function openModal(usuarioId) {
        // En una aplicación real, aquí harías una petición AJAX para obtener los datos completos
        // Pero como ya tienes los datos en el DOM, los obtenemos de allí
        
        const usuarioSection = document.querySelector(`.section-usuarios[data-id="${usuarioId}"]`);
        if (usuarioSection) {
            // Obtener datos básicos
            const nombre = usuarioSection.querySelector('.nombre-usuarios:nth-of-type(1)').textContent;
            const apellido = usuarioSection.querySelector('.nombre-usuarios:nth-of-type(2)').textContent;
            const email = usuarioSection.querySelector('.correo-usuarios').textContent;
            const foto = usuarioSection.querySelector('.perfil-usuarios').src;
            
            // Obtener datos adicionales del data attributes (deberías agregarlos en el HTML)
            // Asumiré que los datos están disponibles en atributos data-*
            const usuarioDiv = usuarioSection.closest('.section-usuarios');
            const telefono = usuarioDiv.dataset.telefono || 'No especificado';
            const estado = usuarioDiv.dataset.estado === '1' ? 'Activo' : 'Inactivo';
            const esAdmin = usuarioDiv.dataset.admin === '1' ? 'Administrador' : 'Usuario normal';
            const divisiones = JSON.parse(usuarioDiv.dataset.divisiones || '[]');
            const idUsuario = usuarioDiv.dataset.id;
            
            // Llenar el modal
            document.getElementById('modalNombreCompleto').textContent = `${nombre} ${apellido}`;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalTelefono').textContent = telefono;
            document.getElementById('modalEstado').textContent = estado;
            document.getElementById('modalRol').textContent = esAdmin;
            document.getElementById('modalId').textContent = idUsuario;
            document.getElementById('modalProfilePhoto').src = foto;
            
            // Actualizar badges
            const adminBadge = document.getElementById('modalAdminBadge');
            const estadoBadge = document.getElementById('modalEstadoBadge');
            
            adminBadge.textContent = esAdmin === 'Administrador' ? 'Admin' : 'Usuario';
            adminBadge.className = `badge ${esAdmin === 'Administrador' ? 'admin' : 'normal'}`;
            
            estadoBadge.textContent = estado;
            estadoBadge.className = `badge ${estado === 'Activo' ? 'activo' : 'inactivo'}`;
            
            // Llenar divisiones
            const divisionesList = document.getElementById('modalDivisiones');
            divisionesList.innerHTML = '';
            
            if (divisiones.length > 0) {
                divisiones.forEach(division => {
                    const li = document.createElement('li');
                    li.textContent = division.nombre;
                    divisionesList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'Sin divisiones asignadas';
                li.style.color = '#999';
                li.style.fontStyle = 'italic';
                divisionesList.appendChild(li);
            }
            
            // Mostrar el modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Necesitarás actualizar tu HTML para incluir los data attributes adicionales
    // Ejemplo de cómo debería quedar cada sección de usuario:
    /*
    <div class="section-usuarios" data-id="<?php echo $usuario->id; ?>" 
         data-telefono="<?php echo htmlspecialchars($usuario->telefono); ?>"
         data-estado="<?php echo $usuario->estado; ?>"
         data-admin="<?php echo $usuario->admin; ?>"
         data-divisiones='<?php echo json_encode($usuario->divisiones); ?>'>
    */
    
    verMasBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const usuarioId = this.getAttribute('data-id');
            openModal(usuarioId);
        });
    });
    
    function closeModalFunc() {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    
    closeModal.addEventListener('click', closeModalFunc);
    closeBtn.addEventListener('click', closeModalFunc);
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalFunc();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeModalFunc();
        }
    });
});