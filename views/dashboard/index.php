<?php //debuguear($solucionesEliminacion); ?>
<section class="info-usuario">
    <div class="contenedor-imagen-perfil">
        <img src="/perfil/juanse.jpg" width="500px" height="800" alt="" class="perfil-usuarios">
    </div>
    <div class="texto-perfil">
        <h3><?php echo $usuario->nombre . " " . $usuario->apellido; ?></h3>
        
        <p><?php echo $_SESSION['usuario']['division'][0]->nombre; ?> <?php if($usuario->admin === 1){
                echo "<i class='bx bx-crown'></i>";
            } ?>
        </p>
    </div>
</section>

<section class="div-informativo">
    <div class="general">
        <?php if ($mostrarEliminaciones): ?>
            <!-- Contenedor para Eliminaciones (estado 4) -->
            <div class="tarjetas soluciones_open">
                <h4>Solicitudes de eliminación</h4>
                <?php if (!empty($solucionesEliminacion)): ?>
                    <table class="tabla_soluciones_open">
                        <?php foreach ($solucionesEliminacion as $solucion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($solucion->title); ?></td>
                                <td title="Eliminación pendiente">
                                    <a href="/dashboard/soluciones/detalle?id=<?php echo $solucion->id; ?>">
                                        <i class='bx bx-right-top-arrow-circle'></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p class="mensaje-vacio">No hay eliminaciones pendientes</p>
                <?php endif; ?>
            </div>

            <!-- Nuevo contenedor para Ediciones (estado 3) -->
            <div class="tarjetas soluciones_open">
                <h4>Solicitudes de edición</h4>
                <?php if (!empty($solucionesEdicion)): ?>
                    <table class="tabla_soluciones_open">
                        <?php foreach ($solucionesEdicion as $solucion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($solucion->title); ?></td>
                                <td title="Edición pendiente">
                                    <a href="/dashboard/soluciones/detalle?id=<?php echo $solucion->id; ?>">
                                        <i class='bx bx-edit-alt'></i> <!-- Icono diferente para edición -->
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p class="mensaje-vacio">No hay ediciones pendientes</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        
    </div>
</section>

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
"; ?>