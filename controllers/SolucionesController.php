<?php

namespace Controllers;

use MVC\Router;
use Model\Modulo;
use Model\Soluciones;

class SolucionesController {
    public static function soluciones(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
         // Consultar los módulos desde la base de datos
         $modulos = Modulo::all();
    
        $query = $_GET['query'] ?? ''; // Captura el término de búsqueda
        $categoria = $_GET['categoria'] ?? ''; // Captura el término de búsqueda
        $soluciones = [];
    
        if ($query || $categoria) {
            // Si hay un término de búsqueda, filtra las soluciones
            $soluciones = Soluciones::buscar($query, $categoria);
        } else {
            // Si no hay búsqueda, muestra todas las soluciones
            $soluciones = Soluciones::solucionesConCategorias();
        }
    
        // Renderiza la vista con las soluciones filtradas o completas
        $router->render('dashboard/soluciones', [
            'soluciones' => $soluciones,
            'query' => $query,
            'modulos' => $modulos,
            'categoria' => $categoria
        ], 'layout-users');
    }

    public static function detalle(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
    
        // Obtener el ID de la solución desde la URL
        $id = $_GET['id'] ?? null;
    
        if (!$id) {
            // Redirigir si no se proporciona un ID
            header('Location: /dashboard/soluciones');
            exit;
        }
    
        // Buscar la solución por su ID
        $solucion = Soluciones::find($id);
    
        if (!$solucion) {
            // Redirigir si la solución no existe
            header('Location: /dashboard/soluciones');
            exit;
        }
    
        // Renderizar la vista de detalles
        $router->render('dashboard/soluciones/detalle', [
            'solucion' => $solucion
        ], 'layout-users');
    }
    public static function crear(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
    
        // Obtener las categorías (módulos) para el select
        $modulos = Modulo::all();
    
        // Renderizar la vista del formulario
        $router->render('dashboard/soluciones/crear', [
            'modulos' => $modulos
        ], 'layout-users');
    }
    
    public static function crear_post(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
    
        header('Content-Type: application/json'); // Siempre devolver JSON
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar campos obligatorios
                if (empty($_POST['titulo']) || empty($_POST['categoria']) || empty($_POST['short-description']) || empty($_POST['descripcion'])) {
                    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                    return;
                }
                $id = ($_POST['id']);
                $titulo = s($_POST['titulo']);
                $categoria = $_POST['categoria'];
                $descripcion = $_POST['descripcion'];
                $video = s($_POST['video']);
                $usuario_id = $_SESSION['id'];
                $short_description = $_POST['short-description'];
    
                // Crear y guardar la solución
                $solucion = new Soluciones([
                    'id' => $id,
                    'title' => $titulo,
                    'description' => $descripcion,
                    'categories' => $categoria,
                    'video' => $video,
                    'usuario_id' => $usuario_id,
                    'short_description' => $short_description
                ]);
                
                if ($solucion->guardar()) {
                    echo json_encode(['success' => true, 'redirect' => '/dashboard/soluciones']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
        exit; // Terminar ejecución después de enviar JSON
    }

    public static function upload() {
        header('Content-Type: application/json'); // Asegurar el tipo de respuesta
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
    
            if (!isset($data['file'])) {
                echo json_encode(['success' => false, 'message' => 'No image received']);
                return;
            }
    
            $imageData = $data['file'];
            if (!preg_match('/^data:image\/(jpeg|png|gif|webp);base64,(.+)/', $imageData, $matches)) {
                echo json_encode(['success' => false, 'message' => 'Invalid image format']);
                return;
            }
    
            $imageType = $matches[1];
            $imageData = base64_decode($matches[2]);
    
            // Generar nombre único y guardar
            $fileName = uniqid('img_' . mt_rand(), true) . '.' . $imageType;
            $uploadDir = realpath(__DIR__ . '/../public/uploads/') . DIRECTORY_SEPARATOR;
    
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            $filePath = $uploadDir . $fileName;
            if (file_put_contents($filePath, $imageData)) {
                echo json_encode(['success' => true, 'url' => "/uploads/$fileName"]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error saving image']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
        }
    }

    public static function editar(Router $router){
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();

        $id = $_GET['id'] ?? '';
        if (!$id) {
            // Redirigir si no se proporciona un ID
            header('Location: /dashboard/soluciones');
            exit;
        }
        // Buscar la solución por su ID
        $solucion = Soluciones::solucionEditar($id);
        // Obtener las categorías (módulos) para el select
        $modulos = Modulo::all();
        // Renderizar la vista del formulario
        $router->render('dashboard/soluciones/editar', [
            'solucion' => $solucion,
            'modulos' => $modulos
        ], 'layout-users');
    }
    
    public static function eliminar(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Indicar que la respuesta será JSON
            header('Content-Type: application/json');
    
            // Obtener datos del cuerpo de la petición (POST)
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;
    
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                exit;
            }
    
            // eliminar la solución
            if (Soluciones::eliminar($id)) {
                echo json_encode(['success' => true, 'redirect' => '/dashboard/soluciones/detalle?id=4']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar en la base de datos']);
            }
    
            exit; // Finalizar ejecución después de enviar la respuesta JSON
        }
    }
    

}