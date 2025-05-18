<?php //debuguear($usuarios) ?>
<div class="contenedor">
    <div class="buscador">
        <form method="GET" action="/dashboard/usuarios">
            <input class="input-buscador text-buscador" name="query" type="text" placeholder="nombre, correo o identificación" value="<?php echo $_GET['query'] ?? ''; ?>">
            <select name="estado" id="categoria" class="input-buscador">
                <option value="" disabled selected>Busca por estado</option>
                    <option value="2" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 2) ? 'selected' : ''; ?>>
                        Cancelado
                    </option>
                    <option value="1" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 1) ? 'selected' : ''; ?>>
                        Activo
                    </option>
            </select>
            <button type="submit" class="input-buscador boton-tabla">Buscar</button>
        </form>
    </div>
</div>
<div class="contenedor-usuarios">
    <?php foreach ($usuarios as $usuario) : ?>
    <div class="section-usuarios" data-id="<?php echo $usuario->id; ?>">
        <div class="img-usuarios">
            <img class="perfil-usuarios" src="<?php echo $usuario->profile_photo ?>" alt="">
            <p class="nombre-usuarios"><?php echo htmlspecialchars($usuario->nombre); ?></p>
            <p class="nombre-usuarios"><?php echo htmlspecialchars($usuario->apellido); ?></p>
            <p class="correo-usuarios"><?php echo htmlspecialchars($usuario->email); ?></p>
            <p class="division-usuarios"><?php echo !empty($usuario->divisiones) ? htmlspecialchars($usuario->divisiones[0]->nombre) : 'Sin división'; ?></p>
            <div class="acciones-usuarios">
                <p class="accion ver-mas" data-id="<?php echo $usuario->id; ?>">Ver más</p>
                <p class="accion editar">Editar</p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal -->
<div id="usuarioModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-header">
            <img id="modalProfilePhoto" class="modal-profile-photo" src="" alt="Foto de perfil">
            <h2 id="modalNombreCompleto"></h2>
        </div>
        <div class="modal-body">
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span id="modalEmail" class="info-value"></span>
            </div>
            <div class="info-row">
                <span class="info-label">División:</span>
                <span id="modalDivision" class="info-value"></span>
            </div>
            <!-- Puedes agregar más campos aquí según necesites -->
        </div>
        <div class="modal-footer">
            <button class="modal-btn cerrar">Cerrar</button>
            <button class="modal-btn editar">Editar Usuario</button>
        </div>
    </div>
</div>

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/users/editar.js'></script>
    <script src='/build/js/users/verMas.js'></script>
"; ?>