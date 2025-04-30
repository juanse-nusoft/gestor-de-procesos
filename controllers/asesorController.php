<?php

namespace Controllers;

use MVC\Router;

use Model\Soluciones;

class asesorController {
    public static function index(Router $router) {
        // Comprobar si la sesión está iniciada
        // Si no se ha iniciado la sesión se inicia la sesión
        if (!isset($_SESSION)) session_start();
        //verificar si el usuario está autenticado, si no lo está redirigir a la página de inicio de sesión
        isAuth();

        //Obtener los datos del usuario autenticado mediante la variable de sesión
        $usuario = $_SESSION['usuario'] ?? null;
        //validar si el usuario es un administrador para mostrar las eliminaciones
        // Si el usuario no es un administrador, se le asigna false a la variable $mostrarEliminaciones
        $mostrarEliminaciones = $usuario['admin'] ?? false;
        
        // Obtener IDs de divisiones del usuario
        //se usa array_map para transformar el array de divisiones a un array de IDs
        $divisionesUsuario = array_map(function($division) {
            return $division->division_id;
        }, $usuario['division'] ?? []);

        // Obtener ambos tipos de solicitudes
        $solucionesEliminacion = [];
        $solucionesEdicion = [];
        
        // Si el usuario es un administrador, se obtienen las soluciones pendientes de eliminación y edición
        if ($mostrarEliminaciones) {
            $solucionesEliminacion = Soluciones::obtenerPendientesEliminacion($divisionesUsuario);
            $solucionesEdicion = Soluciones::obtenerPendientesEdicion($divisionesUsuario);
        }

        //se envian los datos a la vista usando la plantilla 'layout-users'
        $router->render('dashboard/index', [
            'solucionesEliminacion' => $solucionesEliminacion,
            'solucionesEdicion' => $solucionesEdicion,
            'mostrarEliminaciones' => $mostrarEliminaciones
        ], 'layout-users');
    }
}