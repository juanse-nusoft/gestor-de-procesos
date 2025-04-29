<div class="config-principal">
    <h1>Configuración</h1>
    <p>Configura tu interfaz de trabajo aquí:</p>
</div>

<div class="conig-general">
    <form action="">
         <div class="contenedor-toggle">
            <p>Modo oscuro</p>
             <div id="toggle-modo-oscuro" class="toggle-switch" data-action="modo-oscuro"></div>
         </div>
         <div class="contenedor-toggle">
            <p>Cambiar a Inglés los viernes</p>
             <div id="toggle-idioma" class="toggle-switch" data-action="idioma"></div>
         </div>
        <div class="campo-corto">
            <input type="number" name="cantidad-registros" id="cantidad-registros" placeholder="Cantidad de registros a mostrar en las búsquedas">
        </div>
        <div class="contenedor-toggle">
            <p>¿Abrir videos en una pestaña nueva?</p>
             <div id="toggle-video" class="toggle-switch" data-action="video"></div>
         </div>


    </form>

</div>



<?php
$script = "
    <script src='/build/js/toggle.js'></script>
"; ?>