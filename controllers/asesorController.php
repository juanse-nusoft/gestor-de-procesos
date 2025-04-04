<?php

namespace Controllers;

use MVC\Router;

class asesorController{
    public static function index (Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAuth();
        $router->render('dashboard/index', [
        ],'layout-users');
    }
}