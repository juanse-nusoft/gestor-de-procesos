<?php

namespace Controllers;

use Model\Auditoria;
use Model\Usuario;
use MVC\Router;

class AuditoriaController{
    public static function auditoria(Router $router){
        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();

        $auditoria = Auditoria::datosAuditoria();
        $usuarios = Usuario::all();

        $router->render('dashboard/auditoria/index', [
            'auditoria' => $auditoria,
            'usuarios' => $usuarios
        ], 'layout-users');
    }
}