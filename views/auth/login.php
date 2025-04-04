<h1 class="nombre-pagina">Iniciar Sesión</h1>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form method="POST" action="/" class="formulario" id="formularioEnviar">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu Email" name="email">
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tu Contraseña" name="password">
    </div>
    <input type="submit" class="boton" id="enviar" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script type='module' src='build/js/login.js'></script>
"; ?>