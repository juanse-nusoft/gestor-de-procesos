<h1><?php echo htmlspecialchars($solucion->title); ?></h1>
<?php //debuguear($solucion); ?>
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
        <form action="">
            <div class="campo-corto">
                <input type="text" placeholder="división" value="" disabled>
            </div>
            <div class="campo-corto">
                <input type="text" placeholder="Completado" value="">
            </div>
            <div class="campo-corto">
                <input type="text" placeholder="categoría" value="">
            </div>
            <div class="campo-corto">
                <input type="text" placeholder="Creado por" value="Creado por" disabled>
            </div>
            <div class="campo-corto">
                <input type="text" placeholder="Fecha creación" value="" disabled>
            </div>
            <div class="campo-corto">
                <input type="text" placeholder="¿tiene video?" value="">
            </div>

            <button type="submit" class="boton">Actualizar</button>
        </form>
    </div>
</div>