<?php

namespace Controllers;

use Classes\Email;
use Model\Divisiones;
use Model\Usuario;
use Model\UsuarioDivision;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
    
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
    
            if (empty($alertas)) {
                // Consulta segura con prepared statements (versión mejorada)
                $usuario = Usuario::where('email', $auth->email);
                
                if ($usuario) {
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Configuración robusta de sesión
                        if (session_status() !== PHP_SESSION_ACTIVE) {
                            session_start([
                                'cookie_lifetime' => 86400, // 1 día
                                'cookie_secure' => true,
                                'cookie_httponly' => true
                            ]);
                            session_regenerate_id(true);
                        }
    
                        // Guardar solo datos necesarios en la sesión
                        $_SESSION['usuario'] = [
                            'id' => $usuario->id,
                            'nombre' => $usuario->nombre,
                            'apellido' => $usuario->apellido,
                            'email' => $usuario->email,
                            'admin' => (int)$usuario->admin,
                            'login_time' => time()
                        ];
    
                        // Redirección segura
                        header('Location: /dashboard');
                        exit;
                    }
                }
                
                // Mensaje genérico por seguridad
                Usuario::setAlerta('error', 'Credenciales incorrectas o cuenta no verificada');
            }
        }
    
        $router->render('auth/login', [
            'alertas' => Usuario::getAlertas()
        ], 'layout');
    }
    /*
    public static function logout(){
        session_start();
        $_SESSION = [];

        header('Location: /');
    }
    */
    public static function olvide(Router $router){

            $alertas = [];
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                $auth = new Usuario($_POST);
                $alertas = $auth->validarEmail();

                if(empty($alertas)){
                    $usuario = Usuario::where('email', $auth->email);
                    if($usuario && $usuario->confirmado === "1"){
                        //Generar token de un solo uso
                        $usuario->crearToken();
                        $usuario->guardar();

                        //Enviar el email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        //TODO: Enviar de éxito
                        Usuario::setAlerta('exito', 'Revisa tu email');
                        

                    }else{
                        Usuario::setAlerta('error', 'Usuario no existe o no está confirmado');
                    }
                }
            }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        //Buscar usuario por token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            
            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
            }
        }

        //debuguear($usuario);

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas'  => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario();
        $divisiones = Divisiones::all();
        //Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
    
            //Revisar que alertas esté vacío
            if(empty($alertas)){
                $resultado = $usuario->existeUsuario();
            
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear y crear token
                    $usuario->hashPassword();
                    $usuario->crearToken();
            
                    // Guardar usuario
                    $resultadoCreacion = $usuario->guardar();
                    
                    if($resultadoCreacion['resultado']){
                        $relacion = new UsuarioDivision([
                            'usuario_id' => $resultadoCreacion['id'], // CORRECCIÓN AQUÍ: usar $resultadoCreacion
                            'division_id' => (int)$_POST['division'] // Convertimos a entero
                        ]);
                        
                        $resultadoRelacion = $relacion->guardar();
                        
                        if($resultadoRelacion['resultado']){
                            $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                            $email->enviarConfirmacion();
                            
                            header('Location: /mensaje');
                            exit;
                        } else {
                            // Si falla la relación, eliminar el usuario creado
                            $usuario->eliminar($resultadoCreacion['id']); // CORRECCIÓN AQUÍ
                            Usuario::setAlerta('error', 'Error al asignar la división');
                        }
                    } else {
                        Usuario::setAlerta('error', 'Error al crear el usuario');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas,
            'divisiones' => $divisiones
        ]);
    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
       
        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no Válido');
        }else{
            //Modificar a usuario confirmado
            
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
        
    }

}
    

