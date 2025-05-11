<?php
    //debuguear($divisiones);
?>
<div class="informativo">
    <div class="titulo">
        <h1>Agregar nuevo proceso</h1>
    </div>
    <div class="navegacion">
        <a href="/dashboard/soluciones" class="boton-navegación">Atrás</a>
    </div>
</div>

<form method="POST" action="/dashboard/soluciones/crear" class="formulario" id="formulario" enctype="multipart/form-data">
    <div class="campo">
        <label for="titulo">Titulo</label>
        <input type="text" id="titulo" name="titulo" placeholder="Ingresa el titulo a crear" required>
        
    </div>
    <div class="campo">
        <label for="division" <?php echo (count($divisiones) <= 1) ? 'style="display:none"' : ''; ?>>División</label>
        <select id="division" name="division" required <?php echo (count($divisiones) <= 1) ? 'style="display:none"' : ''; ?>>
            <option value="" disabled selected>Seleccione una división</option>
            <?php foreach ($divisiones as $division): ?>
                <option value="<?php echo $division->division_id; ?>">
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
            <option value="" disabled selected>Categorías se cargarán al seleccionar división</option>
        <?php endif; ?>
    </select>
    </div>
    <div class="campo">
        <label for="short-description">Contexto: </label>
        <input type="text" id="short-description" name="short-description" placeholder="Resumen de la descripción">
    </div>
    <div id="editor">

    </div>
    <div class="campo">
        <label for="video">Video</label>
        <input type="text" id="video" name="video" placeholder="Ingresa la URL del video en caso que exista">
    </div>
    <button type="submit" class="boton">Agregar</button>
</form>

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/agregarSoluciones.js'></script>
"; ?>