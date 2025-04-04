document.addEventListener('DOMContentLoaded', () => {
    let quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': '1' }, { 'header': '2' }, { 'font': [] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['bold', 'italic', 'underline'],
                [{ 'align': [] }],
                ['link', 'image']
            ],
            clipboard: true
        }
    });//Fin del objeto Quill

    function enviarFormulario(formData) {
        fetch('/dashboard/soluciones/editar', {
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
    }

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
    // Obtener la descripción almacenada
    let descripcionGuardada = document.getElementById('descripcionServidor').dataset.content;

    // Identificar imágenes guardadas previamente
    let imagenesGuardadas = new Set();
    if (descripcionGuardada) {
        quill.root.innerHTML = descripcionGuardada;
        quill.focus();

        // Extraer imágenes y almacenarlas en el Set
        let imgTags = quill.root.querySelectorAll('img');
        imgTags.forEach(img => {
            let src = img.getAttribute('src');
            if (!src.startsWith('data:image')) { // Solo URLs (no Base64)
                imagenesGuardadas.add(src);
            }
        });
    }

    document.getElementById('formulario').addEventListener('submit', function(event) {
        event.preventDefault();
        let contenidoHTML = quill.root.innerHTML;
        let formData = new FormData(this);

        // Extraer todas las imágenes
        let imgTags = quill.root.querySelectorAll('img');
        let nuevasImagenes = [];

        imgTags.forEach(img => {
            let src = img.getAttribute('src');

            if (src.startsWith('data:image')) {
                // Imagen en Base64 (nueva imagen)
                nuevasImagenes.push(src);
            } else if (!imagenesGuardadas.has(src)) {
                // Imagen con URL desconocida (posible error)
                console.warn('Imagen desconocida detectada:', src);
            }
        });

        if (nuevasImagenes.length > 0) {
            let promises = nuevasImagenes.map(base64 => {
                return fetch('/dashboard/soluciones/upload', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ file: base64 })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
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
                    console.error('Error al subir imágenes:', error);
                    alert('Hubo un error al subir imágenes.');
                });
        } else {
            // No hay nuevas imágenes, guardar directamente
            formData.set('descripcion', contenidoHTML);
            enviarFormulario(formData);
        }
    });
});
