<?php //debuguear($auditoria); ?>

<div class="contenedor">
    <div class="buscador">
        <form method="GET" action="/dashboard/soluciones">
            <input class="input-buscador text-buscador" name="query" type="text" placeholder="Busca por palabra clave" value="<?php //echo $_GET['query'] ?? ''; ?>">
            <select name="categoria" id="categoria" class="input-buscador">
                <option value="">Elige una categoría</option>
                <?php //foreach ($modulos as $modulo): ?>
                    <option value="<?php //echo $modulo->id; ?>" <?php //echo (isset($_GET['categoria']) && $_GET['categoria'] == $modulo->id) ? 'selected' : ''; ?>>
                        <?php //echo s($modulo->nombre); ?>
                    </option>
                <?php //endforeach; ?>
            </select>
            <button type="submit" class="input-buscador boton-tabla">Buscar</button>
        </form>
    </div>
</div>



<main class="principal">
        <table id="tabla-soluciones-auditoria">
            <thead class="head-tabla-auditoria">
                <tr class="titulo-tabla">
                    <th>Accion</th>
                    <th>Descripción auditoria</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditoria as $auditoria) : ?>
                    <tr class="fila-solucion-auditoria" data-id="<?php echo $auditoria->id; ?>">
                        <td><?php echo htmlspecialchars($auditoria->accion); ?></td>
                        <td><p class="descripcion_auditoria"><?php echo htmlspecialchars($auditoria->nombre); ?> realizó la acción de <?php echo htmlspecialchars($auditoria->accion); ?>
                        que tiene como título 
                        <?php 
                        $datosNuevos = json_decode($auditoria->datos_nuevos, true); // Decodifica JSON como array asociativo
                        echo htmlspecialchars($datosNuevos['title'] ?? ''); ?>
                    </p></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</main>
<?php
$script = "
    <script src='/build/js/abrirSolucionesdesactivado.js'></script>
"; ?>