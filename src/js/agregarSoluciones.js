document.addEventListener('DOMContentLoaded', (e) => {
    let quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': '1' }, { 'header': '2' }, { 'font': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['bold', 'italic', 'underline'],
                [{ 'align': [] }],
                ['link', 'image']
            ],
            clipboard: true
        }
    });

        // Definir la función enviarFormulario en el ámbito correcto
    function enviarFormulario(formData) {
        fetch('/dashboard/soluciones/crear', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'La solución se guardó correctamente',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect; // Redirigir desde el cliente
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al guardar',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión con el servidor',
                confirmButtonText: 'Aceptar'
            });
                console.error('Error:', error);
            });
    }//Cierra la función enviar formulario

    // Evento de pegado de imágenes
    quill.root.addEventListener("paste", function(event) {
        let items = (event.clipboardData || event.originalEvent.clipboardData).items;
        for (let item of items) {
            if (item.type.indexOf("image") === 0) {
                let file = item.getAsFile();
                let reader = new FileReader();
                reader.onload = function(event) {
                    let base64Image = event.target.result;
                    let range = quill.getSelection();
                    quill.insertEmbed(range.index, "image", base64Image);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    document.getElementById('formulario').addEventListener('submit', function(event) {
        event.preventDefault();
        let contenidoHTML = quill.root.innerHTML;
        let formData = new FormData(this);
    
        // Extraer imágenes en Base64
        let images = contenidoHTML.match(/<img[^>]+src="(data:image\/[^;]+;base64,[^"]+)"/g);
    
        if (images) {
            let promises = images.map(imgTag => {
                let base64 = imgTag.match(/src="(data:image\/[^;]+;base64,[^"]+)"/)[1];
                
                return fetch('/dashboard/soluciones/upload', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ file: base64 }), // Enviar como JSON
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Reemplazar todas las ocurrencias de la imagen Base64
                        contenidoHTML = contenidoHTML.split(base64).join(result.url);
                    }
                    return result;
                });
            });
    
            Promise.all(promises)
                .then(() => {
                    formData.set('descripcion', contenidoHTML);
                    enviarFormulario(formData);
                })
                .catch(error => {
                    console.error('Detalles del error:', {
                        error: error.message,
                        stack: error.stack,
                        base64: base64 // Verificar qué Base64 se está enviando
                    });
                    alert('Error al subir imágenes');
                });
        } else {
            formData.set('descripcion', contenidoHTML);
            enviarFormulario(formData);
        }
    });
});