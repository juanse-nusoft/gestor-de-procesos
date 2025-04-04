<h1 class="nombre-pagina"> Recuperar Contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu email a continuación</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="emnail">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu Email" required>
    </div>
    <input type="submit" class="boton" value="Recuperar Contraseña">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? <br>Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta?<br> Crear una</a>
</div>