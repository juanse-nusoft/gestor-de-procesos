<?php 
use Model\Usuario;
// Al inicio del archivo
$usuario = isset($_SESSION['usuario']) ? new Usuario($_SESSION['usuario']) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
    <!-- Incluye la librería de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src='https://cdn.quilljs.com/1.3.6/quill.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="/build/img/login.jpg" type="image/x-icon">
    

</head>
<body>

     <!-- Sidebar -->
     <div id="sidebar" class="sidebar">
        <a href="#"><i id="toggleBtn" class="bx bx-menu"></i><span></span></a> 
        <a href="/dashboard"><i class='bx bx-home icono-sidebar'></i><span>Inicio</span></a>
        
        <!-- Elementos para todos los usuarios -->
        <a href="/dashboard/soluciones"><i class='bx bx-notepad'></i><span>Soluciones</span></a>
        <!-- <a href="/dashboard/tareas"><i class='bx bxs-add-to-queue'></i><span>Tareas</span></a> -->
        <a href="/dashboard/configuracion"><i class="bx bx-wrench icono-sidebar"></i><span>Configuración</span></a>
        <a href="/dashboard/usuarios/perfil"><i class="bx bx-user-circle icono-sidebar"></i><span>Cuenta</span></a>
        
        <?php if ($usuario && $usuario->esAdmin()): ?>
            <!-- Elementos solo para admin -->
            <a href="/dashboard/usuarios"><i class="bx bx-user icono-sidebar"></i><span>Usuarios</span></a>
            <a href="/dashboard/auditoria"><i class='bx bxs-show'></i><span>Auditoria</span></a>
            <a href="/dashboard/categoria"><i class="fa-regular fa-folder-open"></i><span>Categorías</span></a>
        <?php endif; ?>
        
    </div>

    <div id="main">
        <?php echo $contenido; ?>
    </div>

<script src="/build/js/sidebar/sidebar.js"></script>
<?php
    echo $script ?? '';
?>

</body>
</html>