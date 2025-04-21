document.addEventListener('DOMContentLoaded', function() {
    const selectDivision = document.getElementById('division');
    const selectCategoria = document.getElementById('categoria');
    const tieneMultiplesDivisiones = selectDivision !== null;

    // Función para cargar categorías
    const cargarCategorias = (divisionId) => {
        selectCategoria.innerHTML = '<option value="">Todas las categorías</option>';
        
        // Mostrar loading solo si hay múltiples divisiones
        if (tieneMultiplesDivisiones) {
            selectCategoria.disabled = true;
            const loadingOption = document.createElement('option');
            loadingOption.value = '';
            loadingOption.textContent = 'Cargando...';
            selectCategoria.appendChild(loadingOption);
        }

        // Si no hay división seleccionada, mostrar todas las categorías
        if (!divisionId) {
            if (tieneMultiplesDivisiones) {
                selectCategoria.disabled = false;
            }
            return;
        }

        fetch(`/dashboard/soluciones/get-categorias?division_id=${divisionId}`)
            .then(response => response.json())
            .then(categorias => {
                selectCategoria.innerHTML = '<option value="">Todas las categorías</option>';
                
                categorias.forEach(cat => {
                    const option = new Option(cat.nombre, cat.id);
                    selectCategoria.add(option);
                });
                
                // Mantener selección previa si existe
                if (new URLSearchParams(window.location.search).has('categoria')) {
                    selectCategoria.value = new URLSearchParams(window.location.search).get('categoria');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                selectCategoria.innerHTML = '<option value="">Error al cargar</option>';
            })
            .finally(() => {
                if (tieneMultiplesDivisiones) {
                    selectCategoria.disabled = false;
                }
            });
    };

    // Eventos
    if (tieneMultiplesDivisiones) {
        selectDivision.addEventListener('change', function() {
            cargarCategorias(this.value);
        });
        
        // Cargar inicialmente si hay división seleccionada
        if (selectDivision.value) {
            cargarCategorias(selectDivision.value);
        }
    } else {
        // Usuario con una sola división - cargar categorías automáticamente
        const divisionId = document.querySelector('input[name="division"]')?.value;
        if (divisionId) {
            cargarCategorias(divisionId);
        }
    }
});