
<?php //debuguear($_SESSION)   ?>

<div class="perfil">
    <div class="contenedor-imagen-perfil">
        <img src="<?php echo $usuarioPerfil->profile_photo ?>" width="500px" height="800" alt="" class="foto-perfil">
        <i class='bx bx-dots-horizontal-rounded opciones-perfil'></i>
    </div>

    <form class="formulario" id="formulario" enctype="multipart/form-data">
        <div class="campo-corto-centrado">
            <input type="text" value="<?php echo $usuario->nombre;?>" name="nombre-perfil">
        </div>
        <div class="campo-corto-centrado">
            <input type="text" value="<?php echo $usuario->apellido;?>" name="apellido-perfil">
        </div>
        <div class="campo-corto-centrado">
            <input type="text" value="<?php echo $usuario->email;?>" disabled>
        </div>
        <div class="campo-referencia">
            <p disabled><?php echo $_SESSION['usuario']['division'][0]->nombre; ?></p>
        </div>
        
        <div class="campo-corto-centrado">
            <p>Cambiar contraseña</p>
            <div id="toggle-password" class="toggle-switch"></div>
        </div>

        <div class="campo-corto-centrado" id="campo-contrasena" style="display: none;">
            <input type="password" placeholder="Nueva contraseña" id="nueva-contrasena" name="nueva_contrasena">
        </div>
        <button type="submit" class="boton">Actualizar</button>
    </form>
</div>


<input type="file" id="inputFotoPerfil" accept="image/*" class="input-foto-perfil" style="display: none;">
<div class="modal-opciones-perfil" id="modalOpcionesPerfil">
    <div class="modal-contenido">
        <button class="opcion-modal" id="subirFoto">Subir foto</button>
        <button class="opcion-modal" id="eliminarFoto">Eliminar foto</button>
        <button class="opcion-modal cerrar-modal">Cancelar</button>
    </div>
</div>


<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/modalPerfil.js'></script>
    <script src='/build/js/perfil-user/updateData.js'></script>
"; ?>