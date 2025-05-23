<?php //debuguear(s($usuario)); ?>
<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form action="/crear-cuenta" method="POST" class="formulario" id="formularioEnviar">
    <div class="campo campo-desplegable">
        <label for="division">División</label>
        <select name="division" id="division" required>
        <option value="">Selecciona la división a la que perteneces</option>
        <?php foreach($divisiones as $division): ?>
            <option value="<?php echo s($division->division_id); ?>">
                <?php echo s($division->nombre); ?>
            </option>
        <?php endforeach; ?>
    </select>
    </div>
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre" value="<?php echo ($usuario->nombre) ?? ''; ?>" required>
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="Tu Apellido" value="<?php echo $usuario->apellido ?? ''; ?>" required>
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Tu Teléfono" value="<?php echo $usuario->telefono ?? ''; ?>" required>
    </div>
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Tu E-mail" value="<?php echo $usuario->email ?? ''; ?>" required>
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu Contraseña" required>
    </div>
    
        <input type="submit" value="Crear Cuenta" class="boton" id="enviar">
</form>
 
<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script type='module' src='build/js/crearr-cuenta.js'></script>
"; ?>
