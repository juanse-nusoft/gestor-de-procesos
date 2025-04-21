<?php

namespace Controllers;

use MVC\Router;

use Model\Soluciones;

class asesorController {
    public static function index(Router $router) {
        if (!isset($_SESSION)) session_start();
        isAuth();

        $usuario = $_SESSION['usuario'] ?? null;
        $mostrarEliminaciones = $usuario['admin'] ?? false;
        
        // Obtener IDs de divisiones
        $divisionesUsuario = array_map(function($division) {
            return $division->division_id;
        }, $usuario['division'] ?? []);

        // Obtener ambos tipos de solicitudes
        $solucionesEliminacion = [];
        $solucionesEdicion = [];
        
        if ($mostrarEliminaciones) {
            $solucionesEliminacion = Soluciones::obtenerPendientesEliminacion($divisionesUsuario);
            $solucionesEdicion = Soluciones::obtenerPendientesEdicion($divisionesUsuario);
        }

        $router->render('dashboard/index', [
            'solucionesEliminacion' => $solucionesEliminacion,
            'solucionesEdicion' => $solucionesEdicion,
            'mostrarEliminaciones' => $mostrarEliminaciones
        ], 'layout-users');
    }
}