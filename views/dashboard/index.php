<?php //debuguear($usuario); ?>
<section class="info-usuario">
    <div class="contenedor-imagen-perfil">
        <img src="/perfil/juanse.jpg" width="500px" height="800" alt="" class="perfil-usuarios">
    </div>
    <div class="texto-perfil">
        <h3><?php echo $usuario->nombre . " " . $usuario->apellido; ?></h3>
        <p>Soporte</p>
    </div>
</section>

<section class="div-informativo">
    <div class="general">
        <div class="tarjetas tareas">
            <h4>Tareas Pendientes</h3>
            <div class="tabla">
                <table class="tabla_tareas" >
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td>15-02-2025</td>
                        <td title="En proceso"><div class="circulo"></div></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="tarjetas soluciones_open">
            <h4>Últimas soluciones abiertas</h3>
            <table class="tabla_soluciones_open" >
                    <tr>
                        <td>Problemario ¿Cómo funciona?</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                    <tr>
                        <td>Enviar correo con especificaciones</td>
                        <td title="En proceso"><i class='bx bx-right-top-arrow-circle'></i></td>
                    </tr>
                </table>
        </div>
        <div class="tarjetas notas">
            <h4>Historial de cambios en soluciones</h3>
        </div>
    </div>
</section>

<?php
$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
"; ?>