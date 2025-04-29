<?php  

require_once __DIR__ . '/../includes/app.php';

use Controllers\asesorController;
use Controllers\AuditoriaController;
use Controllers\configController;
use Controllers\LoginController;
use Controllers\SolucionesController;
use Controllers\tareasController;
use Controllers\usuarioController;
use MVC\Router;

$router = new Router();


$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);

//Crear Cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);

//Confirmar cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);

//Recuperar Password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

//AREA PRIVADA
$router->get('/dashboard', [asesorController::class, 'index']);

//CRUD SOLUCIONES
$router->get('/dashboard/soluciones', [SolucionesController::class, 'soluciones']);
$router->post('/dashboard/soluciones', [SolucionesController::class, 'eliminar']);
$router->get('/dashboard/soluciones/get-categorias', [SolucionesController::class, 'getCategorias']);
$router->get('/dashboard/soluciones/detalle', [SolucionesController::class, 'detalle']);
$router->post('/dashboard/soluciones/detalle', [SolucionesController::class, 'actualizarDatosBasicosSolucion']);
$router->get('/dashboard/soluciones/crear', [SolucionesController::class, 'crear']);
$router->post('/dashboard/soluciones/crear', [SolucionesController::class, 'crear_post']);
$router->post('/dashboard/soluciones/upload', [SolucionesController::class, 'upload']);
$router->get('/dashboard/soluciones/editar', [SolucionesController::class, 'editar']);
$router->post('/dashboard/soluciones/editar', [SolucionesController::class, 'crear_post']);
$router->post('/dashboard/soluciones/categorias', [SolucionesController::class, 'categoria']);
$router->get('/dashboard/soluciones/categorias', [SolucionesController::class, 'categoria']);

//CRUD USUARIOS
$router->get('/dashboard/usuarios', [usuarioController::class, 'usuario']);
$router->get('/dashboard/usuarios/perfil', [usuarioController::class, 'perfil']);

//TAREAS
$router->get('/dashboard/tareas', [tareasController::class, 'tarea']);

//Auditoria
$router->get('/dashboard/auditoria', [AuditoriaController::class, 'auditoria']);

//Configuración
$router->get('/dashboard/configuracion', [configController::class, 'index']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();