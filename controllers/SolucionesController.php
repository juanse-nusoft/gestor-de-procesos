<?php

namespace Controllers;

use MVC\Router;
use Model\Modulo;
use Model\Soluciones;
use Model\Usuario;

class SolucionesController {
    public static function soluciones(Router $router) {
    if (!isset($_SESSION)) session_start();
    isAuth();
    
    // Manejar petición de categorías por división
    if (isset($_GET['get_categorias']) && isset($_GET['division'])) {
        $modulo = new Modulo();
        $modulo->division_id = $_GET['division'];
        $categorias = $modulo->obtenerCategoriasPorDivision();
        
        header('Content-Type: application/json');
        echo json_encode($categorias);
        exit;
    }

    // Configuración de paginación
    $paginaActual = $_GET['pagina'] ?? 1;
    $solucionesPorPagina = 3;
    
    // Obtener datos del usuario
    $usuario = $_SESSION['usuario'];
    $usuarioObj = Usuario::find($usuario['id']);
    $divisiones = $usuarioObj->obtenerDivisiones();
    $divisionIds = array_column($divisiones, 'division_id');
    
    // Configurar filtros
    $filtros = [
        'query' => $_GET['query'] ?? '',
        'categoria' => $_GET['categoria'] ?? '',
        'pagina' => $paginaActual,
        'por_pagina' => $solucionesPorPagina
    ];
    
    // Lógica de filtrado por división
    if ($usuarioObj->esAdmin()) {
        if (empty($divisionIds)) {
            $filtros['division_ids'] = null;
        } elseif (!empty($_GET['division']) && in_array($_GET['division'], $divisionIds)) {
            $filtros['division_ids'] = $_GET['division'];
        } else {
            $filtros['division_ids'] = $divisionIds;
        }
    } else {
        $filtros['division_ids'] = $divisionIds;
    }

    // Obtener soluciones PAGINADAS
    
    $resultados = Soluciones::filtrarPaginado($filtros);
    $soluciones = $resultados['soluciones'];
    $totalSoluciones = $resultados['total'];
    
    // Calcular total de páginas
    $totalPaginas = ceil($totalSoluciones / $solucionesPorPagina);
    
    $modulos = Modulo::all();
    
    // Detectar si es petición Fetch (AJAX)
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if ($isAjax) {
        ob_start();
        include 'views/dashboard/soluciones/partials/tabla-soluciones.php';
        $html = ob_get_clean();
        echo $html;
        exit;
    }
    
    // Renderizar vista completa
    $router->render('dashboard/soluciones', [
        'soluciones' => $soluciones,
        'query' => $filtros['query'],
        'modulos' => $modulos,
        'categoria' => $filtros['categoria'],
        'divisiones' => $divisiones,
        'division_actual' => $_GET['division'] ?? null,
        'paginacion' => [
            'pagina_actual' => $paginaActual,
            'total_paginas' => $totalPaginas,
            'total_soluciones' => $totalSoluciones,
            'por_pagina' => $solucionesPorPagina
        ]
    ], 'layout-users');
}
    public static function getCategorias(Router $router) {
        if (!isset($_SESSION)) session_start();
        isAuth();
        
        $divisionId = $_GET['division_id'] ?? null;
        //debuguear($divisionId);
        
        if (!$divisionId) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }
        
        // Obtener categorías para la división seleccionada
        $modulo = new Modulo();
        $modulo->division_id = $divisionId;
        $categorias = $modulo->obtenerCategoriasPorDivision();
        //debuguear($categorias);
        
        header('Content-Type: application/json');
        echo json_encode($categorias);
        exit;
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
        $division_proceso = $solucion->datos_proceso();

        // Verificar que tenemos datos válidos
        if(empty($division_proceso)) {
            header('Location: /dashboard/soluciones');
            exit;
        }


        $modulo = new Modulo();
        $modulo->division_id = $solucion->division;

        $categorias = $modulo->obtenerCategoriasPorDivision();
        // Convertir arrays a objetos
        $categorias = array_map(function($cat) {
            return new Modulo($cat);
        }, $categorias);

        // Renderizar la vista de detalles
        $router->render('dashboard/soluciones/detalle', [
            'solucion' => $solucion,
            'division' => $division_proceso,
            'categorias' => $categorias
        ], 'layout-users');
    }
    public static function crear(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();

        // Obtener el usuario actual
        $usuario = $_SESSION['usuario'];
        $usuarioObj = Usuario::find($usuario['id']);

        // Obtener las divisiones del usuario
        $divisionesUsuario = $usuarioObj->obtenerDivisiones();
        //debuguear($divisionesUsuario);
        

        // Renderizar la vista del formulario
        $router->render('dashboard/soluciones/crear', [
            'divisiones' => $divisionesUsuario
        ], 'layout-users');
    }
    
    public static function crear_post(Router $router) {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
    
        header('Content-Type: application/json'); // Siempre devolver JSON
    
        //debuguear($_POST);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar campos obligatorios
                if (empty($_POST['titulo']) || empty($_POST['categoria']) || empty($_POST['short-description']) || empty($_POST['descripcion'])) {
                    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                    return;
                }
                
                
                //$id = ($_POST['id']);
                $titulo = s($_POST['titulo']);
                $categoria = $_POST['categoria'];
                $descripcion = $_POST['descripcion'];
                $division = $_POST['division'];
                $video = s($_POST['video']);
                $usuario_id = $_SESSION['usuario']['id'];
                $short_description = $_POST['short-description'];
                
                //debuguear($categoria);
                

                // Crear y guardar la solución
                $solucion = new Soluciones([
                    'title' => $titulo,
                    'description' => $descripcion,
                    'division' => $division,
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

    public static function editar(Router $router) {
        if (!isset($_SESSION)) session_start();
        isAuth();
    
        $id = $_GET['id'] ?? '';
        if (!$id) {
            header('Location: /dashboard/soluciones');
            exit;
        }
    
        // Buscar la solución por su ID
        $solucion = Soluciones::solucionEditar($id);
        if (!$solucion) {
            header('Location: /dashboard/soluciones');
            exit;
        }
    
        // Obtener el usuario actual
        $usuario = $_SESSION['usuario'];
        $usuarioObj = Usuario::find($usuario['id']);
    
        // Obtener las divisiones del usuario
        $divisionesUsuario = $usuarioObj->obtenerDivisiones();

        // Obtener las categorías para la división de la solución
        $modulo = new Modulo();
        $modulo->division_id = $solucion[0]->division;
        $categorias = $modulo->obtenerCategoriasPorDivision();

        // Convertir arrays a objetos
        $categorias = array_map(function($cat) {
            return new Modulo($cat);
        }, $categorias);
    
        // Si el usuario es admin, mostrar todas las categorías de sus divisiones
        if ($usuarioObj->esAdmin()) {
            $divisionIds = array_column($divisionesUsuario, 'division_id');
            if (!empty($divisionIds)) {
                $categorias = Modulo::categoriasPorDivisiones($divisionIds);
            }
        }
    
        $solucion[0]->division = (int)$solucion[0]->division;
        $solucion[0]->categories = (int)$solucion[0]->categories;
  
    
        // Renderizar la vista del formulario
        $router->render('dashboard/soluciones/editar', [
            'solucion' => $solucion,
            'modulos' => $categorias,
            'divisiones' => $divisionesUsuario
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
    
    public static function actualizarDatosBasicosSolucion(Router $router){
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
        
        header('Content-Type: application/json');

        try {
            // Verificar método
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }
            
            // Leer el input JSON
            $datos = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
            }
            
            // Validar datos básicos
            if (empty($datos['solucion_id'])) {
                throw new Exception('ID de solución no proporcionado');
            }
            
            $solucion = Soluciones::find($datos['solucion_id']);
            if (!$solucion) {
                throw new Exception('Solución no encontrada');
            }
            
            // Actualizar solo campos permitidos
            $camposPermitidos = ['estado' => 'status', 'categoria' => 'categories'];
            $actualizaciones = [];
            
            foreach ($camposPermitidos as $campoForm => $campoBD) {
                if (isset($datos[$campoForm])) {
                    $solucion->{$campoBD} = s($datos[$campoForm]);
                    $actualizaciones[] = $campoBD;
                }
            }
            
            // Solo actualizar si hay cambios
            if (!empty($actualizaciones)) {
                $solucion->modification_date = date('Y-m-d H:i:s');
                $resultado = $solucion->guardar();
                
                if (!$resultado) {
                    throw new Exception('Error al guardar en la base de datos');
                }
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Solución actualizada correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => true
            ]);
        }
    }
}