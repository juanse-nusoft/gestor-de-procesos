<?php //debuguear($usuario); ?>
<?php //debuguear($soluciones); ?>
<div class="contenedor">
<div class="buscador">
    <form id="form-filtro" method="GET" action="/dashboard/soluciones">
        <input class="input-buscador text-buscador" name="query" type="text" 
               placeholder="Busca por palabra clave" value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>">
        
               <?php 
                // Obtener el ID de división (ya sea de GET o de la única división disponible)
                $divisionId = $_GET['division'] ?? ($divisiones[0]->division_id ?? null);
                ?>

                <!-- Mostrar select de divisiones SOLO si hay más de una -->
                <?php if (count($divisiones) > 1): ?>
                    <select name="division" id="division" class="input-buscador">
                        <option value="">Todas las divisiones</option>
                        <?php foreach ($divisiones as $division): ?>
                            <option value="<?= $division->division_id ?>"
                                <?= ($divisionId == $division->division_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($division->nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <!-- Campo oculto con la única división disponible -->
                    <input type="hidden" name="division" value="<?= $divisiones[0]->division_id ?? '' ?>">
                <?php endif; ?>

                <!-- Select de Categorías (siempre visible) -->
                <select name="categoria" id="categoria" class="input-buscador">
                    <option value="">Todas las categorías</option>
                    <?php if (!empty($categoriasIniciales)): ?>
                        <?php foreach ($categoriasIniciales as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
        
        <button type="submit" class="input-buscador boton-tabla">Buscar</button>
    </form>
</div>
    <div class="agregar">
        <a href="/dashboard/soluciones/crear" class="input-buscador boton-tabla">Agregar</a>
    </div>
</div>



<main class="principal-soluciones" id="resultados-soluciones">
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
                    <tr class="fila-solucion 
                     "
                     data-id="<?php echo $solucion->id; ?>">
                        <td><?php echo htmlspecialchars($solucion->title); ?></td>
                        <td><?php echo htmlspecialchars($solucion->short_description); ?></td>
                        <td><?php echo htmlspecialchars($solucion->categoria_nombre); ?></td>
                        <td class="boton-acciones">
                        <?php
                            switch ($solucion->status) {
                                case '3':
                                    echo <<<HTML
                                        <a class="tabla-acciones"><i class="fa-solid fa-circle" style="color: orange;"></i></a>
                                        <a href="/dashboard/soluciones/editar?id={$solucion->id}" class="tabla-acciones editar"><i class="bx bxs-edit-alt copy"></i></a>
                                        <a class="tabla-acciones eliminar"><i class="bx bxs-x-circle copy"></i></a>
                                        <a href="/dashboard/soluciones/detalle?id={$solucion->id}" class="tabla-acciones copiar"><i class="bx bxs-copy copy"></i></a>
                                    HTML;
                                    break;
                                
                                case '4':
                                    echo <<<HTML
                                        <a class="tabla-acciones"><i class="fa-solid fa-circle" style="color: red;"></i></a>
                                        <a href="/dashboard/soluciones/detalle?id={$solucion->id}" class="tabla-acciones copiar"><i class="bx bxs-copy copy"></i></a>
                                    HTML;
                                    break;
                                
                                default:
                                    echo <<<HTML
                                        <a href="/dashboard/soluciones/editar?id={$solucion->id}" class="tabla-acciones editar"><i class="bx bxs-edit-alt copy"></i></a>
                                        <a class="tabla-acciones eliminar"><i class="bx bxs-x-circle copy"></i></a>
                                        <a href="/dashboard/soluciones/detalle?id={$solucion->id}" class="tabla-acciones copiar"><i class="bx bxs-copy copy"></i></a>
                                    HTML;
                            }
                        ?>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php if (!empty($soluciones) && $paginacion['total_paginas'] > 1): ?>
<div class="paginador">
    <div class="paginador-info">
            <?php
            $solucionesPorPagina = $paginacion['por_pagina'];
            $inicio = (($paginacion['pagina_actual'] - 1) * $solucionesPorPagina + 1);
            $fin = min($paginacion['pagina_actual'] * $solucionesPorPagina, $paginacion['total_soluciones']);
            ?>
            <?php echo '<p>Página'?> <?php echo $paginacion['pagina_actual']; ?>: mostrando <?php echo '<span>' ?> <?php echo $inicio; ?>  - <?php echo $fin; ?> <?php echo '</span>' ?> de <?php echo '<span>' ?><?php echo $paginacion['total_soluciones']; ?> <?php echo '</span>' ?> <?php echo 'soluciones</p>' ?>
    </div>
    
    <div class="paginador-controles">
        <?php if ($paginacion['pagina_actual'] > 1): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => 1])); ?>" class="paginador-boton">
                &laquo; Primera
            </a>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $paginacion['pagina_actual'] - 1])); ?>" class="paginador-boton">
                &lsaquo; Anterior
            </a>
        <?php endif; ?>
        
        <?php 
        // Mostrar números de página (hasta 5 alrededor de la actual)
        $inicio = max(1, $paginacion['pagina_actual'] - 2);
        $fin = min($paginacion['total_paginas'], $paginacion['pagina_actual'] + 2);
        
        for ($i = $inicio; $i <= $fin; $i++): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>" 
               class="paginador-boton <?php echo $i == $paginacion['pagina_actual'] ? 'activo' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($paginacion['pagina_actual'] < $paginacion['total_paginas']): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $paginacion['pagina_actual'] + 1])); ?>" class="paginador-boton">
                Siguiente &rsaquo;
            </a>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $paginacion['total_paginas']])); ?>" class="paginador-boton">
                Última &raquo;
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php
$script = "
    <script src='/build/js/abrirSoluciones.js'></script>
    <script src='/build/js/eliminarSoluciones.js'></script>
    <script src='/build/js/buscadorSoluciones.js'></script>
"; ?>