<div class="config-principal">
    <h1>Configuración</h1>
    <p>Configura tu interfaz de trabajo aquí:</p>
</div>

<div class="conig-general">
    <form action="">
        <!-- <p>Modo oscuro?</p>
        <div id="opciones-usuario">
            
            <div class="toggle-config">
            </div>
                
        </div>
        <div class="resultado">
            <p>Cantidad de resultados a mostrar</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Mostrar solución en una pestaña nueva</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Mostrar solución en una pestaña nueva</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Elige el idioma de la aplicación</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Activar el Inglés como preferencia los días viernes</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Cambiar contraseña cada:</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <h3>Información personal</h3>
        <div class="resultado">
            <p>Qué datos deseas que los demás usuarios vean de tí</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Permitir que otros usuarios puedan cambiar tu contraseña</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Mostrar modulo de tareas</p>
            <input type="number" placeholder="Número menor a 50">
        </div>
        <div class="resultado">
            <p>Mostrar modulo gestor de contraseña</p>
            <input type="number" placeholder="Número menor a 50">
        </div> -->
        <!-- Toggle para Modo Oscuro -->
         <div class="contenedor-toggle">
            <p>Modo oscuro</p>
             <div id="toggle-modo-oscuro" class="toggle-switch" data-action="modo-oscuro"></div>
         </div>
         <div class="contenedor-toggle">
            <p>Cambiar a Inglés los sábados</p>
             <div id="toggle-idioma" class="toggle-switch" data-action="idioma"></div>
         </div>
        <div class="campo">
            <input type="number" name="cantidad-registros" id="cantidad-registros" placeholder="Cantidad de registros a mostrar en las búsquedas">
        </div>
        <div class="contenedor-toggle">
            <p>¿Abrir videos en una pestaña nueva?</p>
             <div id="toggle-video" class="toggle-switch" data-action="video"></div>
         </div>
<!-- Otro Toggle para otra funcionalidad -->

<!-- Otro Toggle para cambiar idioma (Ejemplo) -->

    </form>

</div>



<?php
$script = "
    <script src='/build/js/toggle.js'></script>
"; ?>