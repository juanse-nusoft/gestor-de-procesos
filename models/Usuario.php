<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'admin', 'estado', 'token', 'password'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $admin;
    public $estado;
    public $token;
    public $password;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->estado = $args['estado'] ?? '2';
        $this->token = $args['token'] ?? '';
    }
    
    public function toSessionArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'admin' => (int)$this->admin,
            'estado' => $this->estado
        ];
    }


    public function esAdmin(): bool {
        return (int)$this->admin === 1;
    }
    //Mensajes de validación para la creación de una cuenta

    public function validarNuevaCuenta()
    {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        
        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido es Obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La Contraseña es Obligatoria';
        }
        
        if(!$this->telefono){
            self::$alertas['error'][] = 'El telefono es Obligatorio';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La Contraseña debe contener al menos 6 caracteres';
        }
        
        return self::$alertas;
    }
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "El email es obligatorio";
        }elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = "El formato del email no es válido";
        }
        else{
            $correo = explode('@', $this->email);
            if($correo[1] !== 'nusoft.com.co'){
                self::$alertas['error'][] = "Email no valido";
            }
        }
        if(!$this->password || strlen($this->password) < 8){
            self::$alertas['error'][] = "Contraseña no valida";
        }
        return self::$alertas;
    }

    public function validarEmail()
    {
        if(!$this->email){
            self::$alertas['error'][] = "El email es obligatorio";
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es Obligatoria';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }
        return self::$alertas;
    }

    //Revisa si el usuario ya existe
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email .  "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = "El usuario ya está registrado";
        }
        return($resultado);
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }
    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);
        if(!$resultado || ($this->estado !== '1')){
            self::$alertas['error'][] = "Contraseña incorrecta o tu cuenta no ha sido confirmada";
        }else{
            return true;
        }
    }
    public function getEstadoTexto(){
        $estados = [
            2 => "Cancelado",
            1 => "Activo"
        ];

        return $estados[$this->estado];
    }
    public static function buscarUsuario($termino, $estado = ''){
        $termino = self::$db->escape_string($termino);
        $estado = self::$db->escape_string($estado);

        $query = "SELECT * FROM " . static::$tabla . "
        WHERE (nombre LIKE '%$termino%'
        OR apellido LIKE '%$termino%'
        OR email LIKE '%$termino%')";
        
        if($estado !== ''){
            $query .= " AND estado = '$estado'";
        }
        return self::consultarSQL($query);
    }
    
} 