document.addEventListener('DOMContentLoaded', function() {
    const editarBtns = document.querySelectorAll('.accion.editar');
    const editarModal = document.getElementById('editarUsuarioModal');
    const formEditar = document.getElementById('formEditarUsuario');
    
    // Abrir modal con datos existentes
    editarBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const usuarioCard = this.closest('.section-usuarios');
            
            // Obtener datos de los atributos data-*
            const usuarioData = {
                id: usuarioCard.dataset.id,
                nombre: usuarioCard.querySelector('.nombre-usuarios:nth-of-type(1)').textContent,
                apellido: usuarioCard.querySelector('.nombre-usuarios:nth-of-type(2)').textContent,
                email: usuarioCard.querySelector('.correo-usuarios').textContent,
                telefono: usuarioCard.dataset.telefono || '',
                estado: usuarioCard.dataset.estado,
                admin: usuarioCard.dataset.admin
            };
            
            // Llenar formulario
            document.getElementById('editUsuarioId').value = usuarioData.id;
            document.getElementById('editNombre').value = usuarioData.nombre;
            document.getElementById('editApellido').value = usuarioData.apellido;
            document.getElementById('editEmail').value = usuarioData.email;
            document.getElementById('editTelefono').value = usuarioData.telefono;
            document.getElementById('editEstado').value = usuarioData.estado;
            document.getElementById('editAdmin').value = usuarioData.admin;
            
            // Mostrar modal
            editarModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Enviar formulario
    formEditar.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.textContent = 'Guardando...';
            submitBtn.disabled = true;
            
            const response = await fetch('/dashboard/usuarios/update-user', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) throw new Error('Error en la respuesta');
            
            const result = await response.json();
            
            if (result.success) {
                alert('Usuario actualizado correctamente');
                window.location.reload(); // Recargar para ver cambios
            } else {
                throw new Error(result.message || 'Error al actualizar');
            }
        } catch (error) {
            console.error('Error:', error);
            alert(error.message);
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });
    
    // Cerrar modal
    document.querySelectorAll('#editarUsuarioModal .close-modal, #editarUsuarioModal .modal-btn.cerrar').forEach(btn => {
        btn.addEventListener('click', () => {
            editarModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        });
    });
    
    // Cerrar al hacer clic fuera del modal
    editarModal.addEventListener('click', (e) => {
        if (e.target === editarModal) {
            editarModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    });
});