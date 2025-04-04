<?php debuguear($usuario); ?>
<div class="contenedor">
    <div class="buscador">
        <form method="GET" action="/dashboard/soluciones">
            <input class="input-buscador text-buscador" name="query" type="text" placeholder="Busca por palabra clave" value="<?php echo $_GET['query'] ?? ''; ?>">
            <select name="categoria" id="categoria" class="input-buscador">
                <option value="">Elige una categoría</option>
                <?php foreach ($modulos as $modulo): ?>
                    <option value="<?php echo $modulo->id; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $modulo->id) ? 'selected' : ''; ?>>
                        <?php echo s($modulo->nombre); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="input-buscador boton-tabla">Buscar</button>
        </form>
    </div>
    <div class="agregar">
        <a href="/dashboard/soluciones/crear" class="input-buscador boton-tabla">Agregar</a>
    </div>
</div>



<main class="principal">
    <?php if (empty($soluciones)) : ?>
        <p>No se encontraron resultados.</p>
    <?php else : ?>
        <table id="tabla-soluciones">
            <thead class="head-tabla">
                <tr class="titulo-tabla">
                    <th>Titulo</th>
                    <th>Descripcion</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($soluciones as $solucion) : ?>
                    <tr class="fila-solucion" data-id="<?php echo $solucion->id; ?>">
                        <td><?php echo htmlspecialchars($solucion->title); ?></td>
                        <td><?php echo htmlspecialchars($solucion->short_description); ?></td>
                        <td><?php echo htmlspecialchars($solucion->categoria_nombre); ?></td>
                        <td class="boton-acciones">
                            <a href="/dashboard/soluciones/editar?id=<?php echo $solucion->id; ?>" class="tabla-acciones editar"><i class='bx bxs-edit-alt copy'></i></a>
                            <a class="tabla-acciones eliminar"><i class='bx bxs-x-circle copy'></i></a>
                            <a href="/dashboard/soluciones/detalle?id=<?php echo $solucion->id; ?>" class="tabla-acciones copiar" ><i class='bx bxs-copy copy'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
<?php
$script = "
    <script src='/build/js/abrirSoluciones.js'></script>
    <script src='/build/js/eliminarSoluciones.js'></script>
"; ?>