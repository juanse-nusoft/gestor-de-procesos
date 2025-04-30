<?php

namespace Controllers;

use Model\Auditoria;
use Model\Usuario;
use MVC\Router;

class AuditoriaController{
    //falta mejorar este metodo para que se pueda filtrar por divisiones y por usuarios
    public static function auditoria(Router $router){
        //iniciar la sesión si no está iniciada
        if (!isset($_SESSION)) {
            session_start();
        }
        //verificar si el usuario está autenticado, si no lo está redirigir a la página de inicio de sesión
        isAuth();

        //obtiene todos los datos de la tabla auditoria
        $auditoria = Auditoria::datosAuditoria();
        //obtiene todos los usuarios de la tabla usuarios
        $usuarios = Usuario::all();

        //se envian los datos a la vista usando la plantilla 'layout-users'
        $router->render('dashboard/auditoria/index', [
            'auditoria' => $auditoria,
            'usuarios' => $usuarios
        ], 'layout-users');
    }
}