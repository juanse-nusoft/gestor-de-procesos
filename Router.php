<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {
        // Proteger Rutas...
        session_start();

        $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // Buscar la función asociada a la ruta
        $fn = null;
        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } elseif ($method === 'POST') {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }

        // Ejecutar la función si existe
        if ($fn) {
            call_user_func($fn, $this);
        } else {
            echo "Página no encontrada";
        }
    }

    public function render($view, $datos = [], $layout = 'layout') {
        
        // Crear objeto Usuario desde sesión si existe
        $datos['usuario'] = isset($_SESSION['usuario']) 
            ? new \Model\User($_SESSION['usuario']) 
            : null;
    
        extract($datos);
        
        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();
    
        if ($layout) {
            include_once __DIR__ . "/views/{$layout}.php";
        } else {
            echo $contenido;
        }
    }
}