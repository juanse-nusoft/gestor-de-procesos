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
        <select id="categoria" name="categoria" required>
            <option value="" disabled>Seleccione una categoría</option>
            <?php foreach ($modulos as $modulo) : ?>
                <option value="<?php echo $modulo->id; ?>" 
                    <?php echo ($solucion[0]->categories == $modulo->id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($modulo->nombre); ?>
                </option>
            <?php endforeach; ?>
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