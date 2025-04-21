<h1><?php echo htmlspecialchars($solucion->title); ?></h1>
<?php //debuguear($categorias); ?>

<div class="principal">
    <div class="detalle-solucion">
        <a href="/dashboard/soluciones" class="boton-tabla input-buscador">Volver</a>
        <p class="detalle-descripcion"><strong>Descripción:</strong></p>
        <div><?php echo $solucion->description; ?></div>
        <div class="detalle-video">
            <?php if($solucion->video ==! ''){ ?>
                <p><strong>Video referencia:</strong></p>
                <a href="<?php echo htmlspecialchars($solucion->video); ?>" class="boton-tabla input-buscador" target="_blank">Ver video en Youtube</a>
            <?php } ?>
        </div>       
        <br>
    </div>
    <div class="informacion-solucion">
        <form action="/dashboard/soluciones/detalle" id="formulario-solucion" method="POST">
        <input type="hidden" name="solucion_id" value="<?php echo $division[0]['id']; ?>">
            <div class="campo-corto">
                <label for="division">División</label>
                <input 
                    type="text" 
                    id="division" 
                    placeholder="División" 
                    value="<?php echo s($division[0]['division_nombre'] ?? ''); ?>" 
                    disabled
                >
            </div>
            <div class="campo-corto">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="input-buscador" required>
                    <option value="1" <?= ($division[0]['status'] == 1) ? 'selected' : '' ?>>Activo</option>
                    <option value="2" <?= ($division[0]['status'] == 2) ? 'selected' : '' ?>>Por Finalizar</option>
                    <option value="3" <?= ($division[0]['status'] == 3) ? 'selected' : '' ?>>Editar: Confirmar</option>
                    <option value="4" <?= ($division[0]['status'] == 4) ? 'selected' : '' ?>>Eliminar: Confirmar</option>
                </select>
            </div>
            <div class="campo-corto">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria" class="input-buscador">
                    <?php foreach ($categorias as $categoria): 
                        $selected = '';
                        if ((isset($_GET['categoria']) && $_GET['categoria'] == $categoria->id) || 
                            (!isset($_GET['categoria']) && $categoria->id == ($division[0]['categoria_id'] ?? null))) {
                            $selected = 'selected';
                        }
                    ?>
                        <option value="<?php echo $categoria->id; ?>" <?php echo $selected; ?>>
                            <?php echo s($categoria->nombre); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="campo-corto">
                <label for="creado_por">Creado por</label>
                <input type="text" id="creado_por" placeholder="Creado por" value="<?php echo s($division[0]['nombre_usuario'] ?? ''); ?>" disabled>
            </div>
            <div class="campo-corto">
                <label for="creación">Creado el</label>
                <input type="text" id="creacion" placeholder="Fecha creación" value="<?php echo s($division[0]['creation_date'] ?? ''); ?>" disabled>
            </div>
            <div class="campo-corto">
                <label for="video">¿video?</label>
                <input type="text" id="video" placeholder="¿tiene video?" 
                value="
                <?php if ($division[0]['video'] ==! ''){
                    echo 'Sí';
                }else{
                    echo 'No';
                }  ?>
                " disabled>
            </div>

            <button type="submit" class="boton">Actualizar</button>
        </form>
    </div>
</div>
<?php
$script = "
    <script src='/build/js/editarDatosBasicosDetalleSoluciones.js'></script>
"; ?>