<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
    <!-- Incluye la librería de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="/build/img/login.jpg" type="image/x-icon">
    

</head>
<body>
    <div class="contenedor-app">
        <div class="imagen">
            <img src="build//img/login.jpg" alt="imagen">
        </div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>


        <?php
            echo $script ?? '';
        ?>
</body>
</html>