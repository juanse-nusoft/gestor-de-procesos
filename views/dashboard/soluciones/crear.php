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
        <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($modulos as $modulo) : ?>
                    <option value="<?php echo $modulo->id; ?>"><?php echo htmlspecialchars($modulo->nombre); ?></option>
                <?php endforeach; ?>
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