<?php

namespace Controllers;

use Classes\Email;
use Model\Divisiones;
use Model\Usuario;
use Model\UsuarioDivision;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        //array de alertas donde después guardarás mensajes de error o advertencias para el usuario.
        $alertas = [];
        
        //si la petición es POST, entonces se ejecuta el siguiente bloque de código
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // se crea una nueva instancia de la clase Usuario y se le pasan los datos del formulario
            $auth = new Usuario($_POST);
            //se llama al método validarLogin de la clase Usuario para validar los datos del formulario y se guardan las alertas en la variable $alertas
            $alertas = $auth->validarLogin();
            
            //si el arreglo de alertas está vacío significa que no hay errores y que se puede continuar
            if(empty($alertas['error'])){
                //validamos que el correo exista en la base de datos
                $usuario = Usuario::where('email', $auth->email);
                //si el usuario existe, continuamos
                if ($usuario !== NULL) {
                    //verificamos que el usuario esté activo y que la contraseña sea correcta
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Configuración robusta de sesión
                        if (session_status() !== PHP_SESSION_ACTIVE) {
                            session_start([
                                'cookie_lifetime' => 86400, // 1 día
                                'cookie_secure' => true, //Solo se envía por HTTPS.
                                'cookie_httponly' => true // No accesible por JavaScript
                            ]);
                            session_regenerate_id(true); // Regenerar ID de sesión para prevenir ataques de fijación de sesión
                        }
    
                        // Guardar solo datos necesarios en la sesión 
                        $_SESSION['usuario'] = [
                            'id' => $usuario->id,
                            'nombre' => $usuario->nombre,
                            'apellido' => $usuario->apellido,
                            'email' => $usuario->email,
                            'admin' => (int)$usuario->admin,
                            'login_time' => time(),
                            'division' => $usuario->obtenerDivisiones($usuario->id),
                            'profile_photo' => $usuario->profile_photo
                        ];
    
                        // se redirige al usuario a la página de inicio después de iniciar sesión
                        // y se utiliza la función exit para asegurarse de que no se ejecute ningún otro código después de la redirección.
                        header('Location: /dashboard');
                        exit;
                    }
                }else{
                    // Mensaje genérico por seguridad
                    Usuario::setAlerta('error', 'Credenciales incorrectas o cuenta no verificada');
                }

            }
        }
        //En caso que no se haya enviado el formulario o haya errores, se renderiza la vista de login
        $router->render('auth/login', [
            'alertas' => Usuario::getAlertas()
        ], 'layout');
    }

    public static function olvide(Router $router){
            //se inicializa el array de alertas para almacenar mensajes de error o advertencias para el usuario.
            $alertas = [];
            // si la petición es POST, entonces se ejecuta el siguiente bloque de código
            if($_SERVER["REQUEST_METHOD"] === "POST"){

                //se crea una nueva instancia de la clase Usuario y se le pasan los datos del formulario
                $auth = new Usuario($_POST);

                //se llama al método validarEmail de la clase Usuario para validar el email y se guardan las alertas en la variable $alertas
                $alertas = $auth->validarEmail();

                //si el arreglo de alertas está vacío significa que no hay errores y que se puede continuar
                if(empty($alertas['error'])){
                    //validamos que el correo exista en la base de datos
                    $usuario = Usuario::where('email', $auth->email);
                    
                    //si el usuario existe y está activo, continuamos
                    if($usuario && $usuario->estado === 1){
                        //Generar token de un solo uso
                        $usuario->crearToken();
                        $usuario->guardar();

                        //Enviar el email con el token
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        //enviamos un mensaje de éxito al usuario
                        Usuario::setAlerta('exito', 'Revisa tu email');
                        

                    }else{
                        //si el usuario no existe o no está activo, se muestra un mensaje de error
                        Usuario::setAlerta('error', 'Usuario no existe o no está confirmado');
                    }
                }
            }
        //obtenemos las alertas generadas y las pasamos a la vista
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        //iniciamos la variable donde guardaremos las alertas
        $alertas = [];

        //esta variable se usará para mostrar un mensaje de error si el token no es válido
        $error = false;

        //obtenemos el token de la URL y lo sanitizamos
        $token = s($_GET['token']);
        //Buscar usuario por token
        $usuario = Usuario::where('token', $token);

        //si el usuario no existe o el token no es válido, se muestra un mensaje de error
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        //si el token es válido, se muestra el formulario para ingresar el nuevo password
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //Leer el nuevo password y guardarlo en la variable $password
            $password = new Usuario($_POST);
            //validar si hay errores en el nuevo password
            $alertas = $password->validarPassword();
            
            //validamos si el arreglo de alertas está vacío, lo que significa que no hay errores
            if(empty($alertas['error'])){
                $usuario->password = null; // Limpiamos el password anterior
                $usuario->password = $password->password; // Asignamos el nuevo password
                $usuario->hashPassword(); // Hasheamos el nuevo password
                $usuario->token = null; // Limpiamos el token

                // Guardar el nuevo password en la base de datos
                $resultado = $usuario->guardar();

                //si el resultado es verdadero, significa que se guardó correctamente
                //y redirigimos al usuario a la página de inicio
                if($resultado){
                    header('Location: /');
                }
            }
        }

        //obtenemos las alertas generadas y las pasamos a la vista
        //si el token no es válido, se muestra un mensaje de error
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
            //debuguear($alertas);
    
            //Revisar que alertas esté vacío
            if(empty($alertas['error'])){
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
                            'usuario_id' => $resultadoCreacion['id'],
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
                            $usuario->eliminar($resultadoCreacion['id']);
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
    

