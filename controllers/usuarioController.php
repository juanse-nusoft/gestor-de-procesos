<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class usuarioController{
    public static function usuario(Router $router){
    if(!isset($_SESSION)){
        session_start();
    }
    isAuth();

    $usuarios = Usuario::all();

    $query = $_GET['query'] ?? '';
    $estado = $_GET['estado'] ?? '';

    if($query || $estado){
        $usuarios = Usuario::buscarUsuario($query, $estado);
    }

    // Asignar divisiones a cada usuario
    foreach ($usuarios as $usuario) {
        $usuario->divisiones = $usuario->obtenerDivisiones();
    }

    $router->render('dashboard/usuarios/all', [
        'usuarios' => $usuarios
    ], 'layout-users');
}


    public static function Perfil(Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAuth();
        //debuguear($_SESSION);
        $id = $_SESSION['usuario']['id'];
        $usuarioPerfil = Usuario::find($id);

    $router->render('dashboard/usuarios/perfil', [
        'usuarioPerfil' => $usuarioPerfil
    ], 'layout-users');
    }

    public static function updatePerfil(){
    header('Content-Type: application/json');
    if(!isset($_SESSION)){
        session_start();
    }
    isAuth();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $idUsuario = $_SESSION['usuario']['id'];
    $datos = [
        'nombre' => $_POST['nombre-perfil'] ?? null,
        'apellido' => $_POST['apellido-perfil'] ?? null,
        'password' => $_POST['nueva_contrasena'] ?? null
    ];

    $resultado = Usuario::actualizarPerfil($idUsuario, $datos);
    if ($resultado['success']) {
        // Actualizar la sesión con los nuevos datos
        $_SESSION['usuario']['nombre'] = $datos['nombre'];
        $_SESSION['usuario']['apellido'] = $datos['apellido'];
    }
    echo json_encode($resultado);
}  
    public static function uploadPerfil() {
    header('Content-Type: application/json');
    
    if(!isset($_SESSION)) {
        session_start();
    }
    isAuth();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SESSION['usuario']['id'];
        $usuario = Usuario::find($id);
        
        // Validar archivo subido
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'error' => 'No se ha subido ninguna imagen o hubo un error']);
            return;
        }
        
        // Validar tipo y tamaño
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tipoArchivo = $_FILES['imagen']['type'];
        $tamañoMaximo = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($tipoArchivo, $permitidos)) {
            echo json_encode(['success' => false, 'error' => 'Formato no permitido. Use JPEG, PNG o GIF']);
            return;
        }
        
        if ($_FILES['imagen']['size'] > $tamañoMaximo) {
            echo json_encode(['success' => false, 'error' => 'La imagen supera el límite de 2MB']);
            return;
        }
        
        // Rutas (ajustadas a tu estructura)
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/perfil/'; // Ruta física absoluta
        $rutaWeb = '/perfil/'; // Ruta relativa accesible desde el navegador
        
        // Crear directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generar nombre único
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $nombreArchivo = 'perfil_' . $id . '_' . uniqid() . '.' . $extension;
        $rutaCompleta = $uploadDir . $nombreArchivo;
        
        // Mover el archivo
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            // Actualizar BD con ruta relativa
            $rutaRelativa = $rutaWeb . $nombreArchivo;
            
            if ($usuario->actualizarImagenPerfil($rutaRelativa)) {
                $_SESSION['usuario']['imagen'] = $rutaRelativa;
                echo json_encode([
                    'success' => true, 
                    'imagen' => $rutaRelativa,
                    'message' => 'Imagen actualizada correctamente'
                ]);
            } else {
                unlink($rutaCompleta); // Rollback: borrar imagen si falla la BD
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la base de datos']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al guardar la imagen en el servidor']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    }
}
    public static function deletePerfil() {
    header('Content-Type: application/json');
    
    if(!isset($_SESSION)) {
        session_start();
    }
    isAuth();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SESSION['usuario']['id'];
        $usuario = Usuario::find($id);

        // Verificar si tiene foto actual (excepto la default)
        if (empty($usuario->profile_photo) || $usuario->profile_photo === '/perfil/default.png') {
            echo json_encode(['success' => false, 'error' => 'No hay foto personalizada para eliminar']);
            exit;
        }

        // Ruta absoluta al archivo
        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . $usuario->profile_photo;

        // Eliminar físicamente el archivo (si existe y no es la default)
        if (file_exists($rutaImagen) && $usuario->profile_photo !== '/perfil/default.png') {
            if (!unlink($rutaImagen)) {
                error_log("Error al eliminar: $rutaImagen");
            }
        }

        // Actualizar BD con imagen por defecto
        $usuario->profile_photo = '/perfil/default.png';
        if ($usuario->guardar(['profile_photo'])) {
            $_SESSION['usuario']['profile_photo'] = '/perfil/default.png';
            echo json_encode([
                'success' => true,
                'imagen' => '/perfil/default.png'
            ]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar la base de datos']);
            exit;
        }
    }
    
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

public static function updateInfoUser() {
    header('Content-Type: application/json');
    
    if(!isset($_SESSION)) {
        session_start();
    }
    isAuth();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $idUsuario = $_POST['id_usuario'] ?? null;
    $datos = [
        'nombre' => $_POST['nombre'] ?? null,
        'apellido' => $_POST['apellido'] ?? null,
        'email' => $_POST['email'] ?? null,
        'telefono' => $_POST['telefono'] ?? null,
        'estado' => $_POST['estado'] ?? null,
        'rol' => $_POST['rol'] ?? null
    ];

    $resultado = Usuario::actualizarUsuario($idUsuario, $datos);
    echo json_encode($resultado);
}
}