<?php //debuguear($modulos); ?>
<div class="informativo">
    <div class="titulo">
        <h1>Editar</h1>
    </div>
    <div class="navegacion">
        <a href="/dashboard/soluciones" class="boton-navegación">Atrás</a>
    </div>
</div>
<?php //debuguear($solucion); ?>
<form method="POST" action="/dashboard/soluciones/editar" class="formulario" id="formulario" enctype="multipart/form-data">
    <div class="campo">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($solucion[0]->id); ?>">
        <label for="titulo">Titulo</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo ($solucion[0]->title); ?>" required>
        
    </div>
    <div class="campo">

        <label for="division" <?php echo (count($divisiones) <= 1) ? 'style="display:none"' : ''; ?>>División</label>
        <select id="division" name="division" required <?php echo (count($divisiones) <= 1) ? 'style="display:none"' : ''; ?>>
            <option value="" disabled>Seleccione una división</option>
            <?php foreach ($divisiones as $division): ?>
                <option value="<?php echo $division->division_id; ?>"
                    <?php echo ($solucion[0]->division == $division->division_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($division->nombre); ?>
                </option>
            <?php endforeach; ?>
        </select>
 
        <label for="categoria" class="margen-izquierdo">Categoría </label>
        <select id="categoria" name="categoria" required>
        <?php 
        $categoriaActual = $solucion[0]->categories ?? null;
        $mostrarTodas = true; 
        
        if ($mostrarTodas && !empty($modulos)): ?>
            <?php foreach ($modulos as $modulo): ?>
                <option value="<?= $modulo['id'] ?>"
                    <?= ($categoriaActual == $modulo['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($modulo['nombre']) ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="" disabled>Categorías se cargarán al seleccionar división</option>
        <?php endif; ?>
    </select>
    </div>
    <div class="campo">
        <label for="short-description">Contexto: </label>
        <input type="text" id="short-description" name="short-description" value="<?php echo ($solucion[0]->short_description); ?>" placeholder="Resumen de la descripción">
    </div>
    <div id="descripcionServidor" data-content='<?= htmlspecialchars($solucion[0]->description, ENT_QUOTES, "UTF-8") ?>'></div>

    <div id="editor">
        
    </div>
    <div class="campo">
        <label for="video">Video</label>
        <input type="text" id="video" name="video" placeholder="Ingresa la URL del video en caso que exista">
    </div>
    <button type="submit" class="boton">Editar</button>
</form>


<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/editarSoluciones.js' defer></script>
"; ?>