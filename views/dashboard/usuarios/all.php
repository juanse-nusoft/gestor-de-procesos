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
    <div class="section-usuarios" 
         data-id="<?php echo $usuario->id; ?>"
         data-telefono="<?php echo htmlspecialchars($usuario->telefono); ?>"
         data-estado="<?php echo $usuario->estado; ?>"
         data-admin="<?php echo $usuario->admin; ?>"
         data-divisiones='<?php echo json_encode($usuario->divisiones); ?>'>
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
            <div class="badges-container">
                <span id="modalAdminBadge" class="badge admin">Admin</span>
                <span id="modalEstadoBadge" class="badge activo">Activo</span>
            </div>
        </div>
        <div class="modal-body">
            <div class="info-section">
                <h3 class="section-title">Información Básica</h3>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span id="modalEmail" class="info-value"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Teléfono:</span>
                    <span id="modalTelefono" class="info-value"></span>
                </div>
            </div>
            
            <div class="info-section">
                <h3 class="section-title">Divisiones</h3>
                <ul id="modalDivisiones" class="divisiones-list">
                    <!-- Las divisiones se llenarán con JavaScript -->
                </ul>
            </div>
            
            <div class="info-section">
                <h3 class="section-title">Detalles de Cuenta</h3>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span id="modalEstado" class="info-value"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rol:</span>
                    <span id="modalRol" class="info-value"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">ID Usuario:</span>
                    <span id="modalId" class="info-value"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn cerrar">Cerrar</button>
            <button class="modal-btn editar">Editar Usuario</button>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div id="editarUsuarioModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-header">
            <h2>Editar Usuario</h2>
        </div>
        <form id="formEditarUsuario" class="modal-form">
            <input type="hidden" id="editUsuarioId" name="id">
            
            <div class="form-group">
                <label for="editNombre">Nombre</label>
                <input type="text" id="editNombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="editApellido">Apellido</label>
                <input type="text" id="editApellido" name="apellido" required>
            </div>
            
            <div class="form-group">
                <label for="editEmail">Email</label>
                <input type="email" id="editEmail" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="editTelefono">Teléfono</label>
                <input type="tel" id="editTelefono" name="telefono">
            </div>
            
            <div class="form-group">
                <label for="editEstado">Estado</label>
                <select id="editEstado" name="estado" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="editAdmin">Rol</label>
                <select id="editAdmin" name="admin" required>
                    <option value="1">Administrador</option>
                    <option value="0">Usuario normal</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Divisiones</label>
                <div id="editDivisionesContainer" class="divisiones-checkbox">
                    <!-- Las divisiones se cargarán dinámicamente -->
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="modal-btn cerrar">Cancelar</button>
                <button type="submit" class="modal-btn guardar">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/users/editar.js'></script>
    <script src='/build/js/users/verMas.js'></script>
"; ?>