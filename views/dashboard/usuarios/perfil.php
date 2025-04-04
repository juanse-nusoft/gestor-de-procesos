
<?php //debuguear($usuario) ?>

<div class="perfil">
    <div class="contenedor-imagen-perfil">
        <img src="/perfil/juanse1.jpg" width="500px" height="800" alt="" class="foto-perfil">
        <i class='bx bx-dots-horizontal-rounded opciones-perfil'></i>
    </div>

    <form action="">
        <div class="campo-corto-centrado">
            <input type="text" value="<?php echo $usuario->nombre;?>">
        </div>
        <div class="campo-corto-centrado">
            <input type="text" value="<?php echo $usuario->apellido;?>">
        </div>
        <div class="campo-corto-centrado">
            <input type="text" value="<?php echo $usuario->email;?>" disabled>
        </div>
        <div class="campo-referencia">
            <p disabled>Soporte</p>
        </div>
        <div class="campo-corto-centrado">
            <p>Cambiar contraseña</p>
            <div id="toggle-idioma" class="toggle-switch" data-action="idioma"></div>
        </div>
        <button type="submit" class="boton">Actualizar</button>
    </form>
</div>

