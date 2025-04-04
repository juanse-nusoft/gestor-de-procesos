<?php

namespace Controllers;

use MVC\Router;

class tareasController{
    public static function tarea(Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAuth();


        $router->render('dashboard/tareas/index', [

        ], 'layout-users');
    }
}