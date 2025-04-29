<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class usuarioController{
    public static function usuario(Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAuth();

        $usuarios = Usuario::all();

        $query = $_GET['query'] ?? '';
        $estado = $_GET['estado'] ?? '';

        //debuguear($estado);
        if($query || $estado){
            $usuarios = Usuario::buscarUsuario($query, $estado);
        }
        

        $router->render('dashboard/usuarios/all', [
            'usuarios' => $usuarios
        ], 'layout-users');
    }

    public static function Perfil(Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAuth();
        //debuguear($_SESSION);
        $id = $_SESSION['usuario']['id'];
        $usuario = Usuario::find($id);

    $router->render('dashboard/usuarios/perfil', [
        'usuario' => $usuario
    ], 'layout-users');
    }
}