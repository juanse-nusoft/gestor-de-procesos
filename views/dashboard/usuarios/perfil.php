
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


<div class="modal-opciones-perfil" id="modalOpcionesPerfil">
    <div class="modal-contenido">
        <button class="opcion-modal" id="actualizarFoto">Actualizar foto</button>
        <button class="opcion-modal" id="eliminarFoto">Eliminar foto</button>
        <button class="opcion-modal cerrar-modal">Cancelar</button>
    </div>
</div>

<input type="file" id="inputFotoPerfil" accept="image/*" style="display: none;">

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/modalPerfil.js'></script>
"; ?>