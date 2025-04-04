<?php

namespace Controllers;

use MVC\Router;

class configController{
    public static function index(Router $router){
        if(!isset($_SESSION)) {
            session_start();
        }
        isAuth();

        $router->render('dashboard/configuracion/index', [

        ], 'layout-users');
    }
}