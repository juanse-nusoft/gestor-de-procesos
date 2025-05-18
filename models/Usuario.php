<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'admin', 'estado', 'token', 'password', 'profile_photo'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $admin;
    public $estado;
    public $token;
    public $password;
    public $profile_photo;
    public array $divisiones = [];


    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->estado = $args['estado'] ?? '1';
        $this->token = $args['token'] ?? '';
        $this->profile_photo = $args['profile_photo'] ?? '/perfil/default.png';
    }
    
    public function toSessionArray(): array {
        return [
            'id' => (int)$this->id,
            'nombre' => htmlspecialchars($this->nombre ?? '', ENT_QUOTES, 'UTF-8'),
            'apellido' => htmlspecialchars($this->apellido ?? '', ENT_QUOTES, 'UTF-8'),
            'email' => filter_var($this->email ?? '', FILTER_SANITIZE_EMAIL),
            'admin' => (int)$this->admin === 1,
        ];
    }
    public function esAdmin(): bool {
        return (int)$this->admin === 1;
    }
    //Mensajes de validación para la creación de una cuenta

    public function validarNuevaCuenta(): array{
    // Reiniciar alertas
    self::$alertas = ['error' => [], 'exito' => []];

    // Validar nombre
    if (empty($this->nombre)) {
        self::$alertas['error'][] = 'El nombre es obligatorio';
    } elseif (strlen(trim($this->nombre)) < 2) {
        self::$alertas['error'][] = 'El nombre debe tener al menos 2 caracteres';
    } elseif (preg_match('/[0-9]/', $this->nombre)) {
        self::$alertas['error'][] = 'El nombre no puede contener números';
    }

    // Validar apellido
    if (empty($this->apellido)) {
        self::$alertas['error'][] = 'El apellido es obligatorio';
    } elseif (strlen(trim($this->apellido)) < 2) {
        self::$alertas['error'][] = 'El apellido debe tener al menos 2 caracteres';
    }

    // Validar email
    if(empty($this->email)) {
        self::$alertas['error'][] = "El email es obligatorio";
    } else {
        // Sanitizar primero
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        
        // Validar formato y caracteres peligrosos
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = "Formato de email no válido";
        } elseif(preg_match('/[\'"\-\;]/', $this->email)) {
            self::$alertas['error'][] = "El email contiene caracteres no permitidos";
        } else {
            // Validar dominio específico si es necesario
            $dominio = substr(strrchr($this->email, "@"), 1);
            if(strtolower($dominio) !== 'nusoft.com.co') {
                self::$alertas['error'][] = "Solo se permite correo corporativo";
            }
        }
    }

    // Validar contraseña
    if (empty($this->password)) {
        self::$alertas['error'][] = 'La contraseña es obligatoria';
    } else {
        if (strlen($this->password) < 8) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }
        
        if (!preg_match('/[A-Z]/', $this->password)) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos una mayúscula';
        }
        
        if (!preg_match('/[0-9]/', $this->password)) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos un número';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $this->password)) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos un carácter especial';
        }
    }

    return self::$alertas;
}
public function validarLogin(): array
{
    // Reiniciar alertas
    self::$alertas = ['error' => [], 'exito' => []];

    // Validación del email
    if (empty($this->email)) {
        self::$alertas['error'][] = 'El email es obligatorio';
    } else {
        // Sanitizar el email
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        
        // Validar formato
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El formato del email no es válido';
        } else {
            // Validar dominio específico de manera más segura
            $dominio = substr(strrchr($this->email, "@"), 1);
            if (strtolower($dominio) !== 'nusoft.com.co') {
                self::$alertas['error'][] = 'Solo se permiten iniciar sesión con el correo corporativo';
            }
        }
    }

    // Validación de la contraseña
    if (empty($this->password)) {
        self::$alertas['error'][] = 'La contraseña es obligatoria';
    } elseif (strlen($this->password) < 8) {
        self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
    }

    return self::$alertas;
}

public function validarEmail(): array
{
    // Reiniciar alertas
    self::$alertas = ['error' => [], 'exito' => []];

    // Validar presencia y formato básico
    if (empty($this->email)) {
        self::$alertas['error'][] = 'El email es obligatorio';
        return self::$alertas;
    }

    // Sanitizar el email
    $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

    // Validar formato
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        self::$alertas['error'][] = 'El formato del email no es válido';
        return self::$alertas;
    }

    // Validar dominio Nusoft
    $dominio = substr(strrchr($this->email, "@"), 1);
    if (strtolower($dominio) !== 'nusoft.com.co') {
        self::$alertas['error'][] = 'Solo se permiten emails corporativos';
    }

    return self::$alertas;
}

public function validarPassword(): array
{
    // Reiniciar alertas
    self::$alertas = ['error' => [], 'exito' => []];

    // Validar presencia
    if (empty($this->password)) {
        self::$alertas['error'][] = 'La contraseña es obligatoria';
        return self::$alertas;
    }

    // Validar longitud mínima
    if (strlen($this->password) < 8) {
        self::$alertas['error'][] = 'La contraseña debe tener al menos 12 caracteres';
    }

    // Validar complejidad
    if (!preg_match('/[A-Z]/', $this->password)) {
        self::$alertas['error'][] = 'Debe contener al menos una letra mayúscula';
    }

    if (!preg_match('/[a-z]/', $this->password)) {
        self::$alertas['error'][] = 'Debe contener al menos una letra minúscula';
    }

    if (!preg_match('/[0-9]/', $this->password)) {
        self::$alertas['error'][] = 'Debe contener al menos un número';
    }

    // Validar contraseñas comunes
    if ($this->esPasswordComun()) {
        self::$alertas['error'][] = 'La contraseña es demasiado común';
    }

    return self::$alertas;
}

protected function esPasswordComun(): bool
{
    $listadoComunes = [
        'password', '123456789', 'qwerty123', 'empresa123', 
        'nusoft2025', 'bienvenido1', 'admin1234', 'Nusoft889'
    ];
    
    return in_array(strtolower($this->password), $listadoComunes);
}

    //Revisa si el usuario ya existe
    public function existeUsuario(): bool {
        
        $query = "SELECT id FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function hashPassword(): void{
    // Validar que la contraseña existe antes de hashear
    if (empty($this->password)) {
        throw new RuntimeException("No se puede hashear una contraseña vacía");
    }

    // Verificar si la contraseña ya está hasheada
    if (password_needs_rehash($this->password, PASSWORD_BCRYPT)) {
        // Configuración robusta de bcrypt
        $opciones = [
            'cost' => 12, // Coste adecuado para balance seguridad/rendimiento
        ];
        
        $hash = password_hash($this->password, PASSWORD_BCRYPT, $opciones);
        
        if ($hash === false) {
            throw new RuntimeException("Error al generar el hash de la contraseña");
        }
        
        $this->password = $hash;
    }
}

    public function crearToken()
    {
        $this->token = uniqid();
    }
    public function comprobarPasswordAndVerificado(string $password): bool
{
    // Validaciones iniciales
    if (empty($password) || empty($this->password)) {
        self::$alertas['error'][] = "La contraseña no puede estar vacía";
        return false;
    }

    // Verificación segura de la contraseña
    $resultado = password_verify($password, $this->password);
    if (!$resultado) {
        self::$alertas['error'][] = "La contraseña es incorrecta";
        return false;
    }

    // Verificación del estado de la cuenta
    if ($this->estado !== 1) {
        self::$alertas['error'][] = "La cuenta no ha sido verificada";
        return false;
    }

    // Si todo es correcto, verificar si el hash necesita actualización
    if (password_needs_rehash($this->password, PASSWORD_BCRYPT, ['cost' => 12])) {
        $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->guardar(); // Actualizar el hash en la base de datos
    }

    return true;
}
    public function getEstadoTexto(){
        $estados = [
            2 => "Cancelado",
            1 => "Activo"
        ];

        return $estados[$this->estado];
    }

    public static function buscarUsuario(string $termino, string $estado = ''): array{
    // Validar conexión
    if (!self::$db || !self::$db->ping()) {
        throw new RuntimeException("Error de conexión a la base de datos");
    }

    // Construir consulta preparada
    $query = "SELECT * FROM " . static::$tabla . " 
              WHERE (nombre LIKE CONCAT('%', ?, '%') 
              OR apellido LIKE CONCAT('%', ?, '%') 
              OR email LIKE CONCAT('%', ?, '%'))";
    
    

    $params = [$termino, $termino, $termino];
    
    $types = 'sss'; // 3 strings

    // Agregar condición de estado si existe
    if ($estado !== '') {
        if (!in_array($estado, ['0', '1', '2'])) {
            throw new InvalidArgumentException("Estado no válido");
        }
        
        $query .= " AND estado = ?";
        $params[] = $estado;
        $types .= 's';
    }
    
    // Ejecutar consulta preparada
    $stmt = self::$db->prepare($query);

    if (!$stmt) {
        throw new RuntimeException("Error al preparar consulta: " . self::$db->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $usuarios = [];
    
    while ($usuario = $result->fetch_assoc()) {
        $usuarios[] = static::crearObjeto($usuario);
    }
    
    $stmt->close();
    return $usuarios;
}
public function obtenerDivisiones(): array{
    // Validar que el usuario tenga ID
    if (empty($this->id)) {
        throw new InvalidArgumentException("ID de usuario no válido");
    }

    // Consulta SQL con JOIN seguro
    $query = "SELECT d.division_id, d.nombre 
              FROM divisiones d
              INNER JOIN usuario_division ud ON d.division_id = ud.division_id
              WHERE ud.usuario_id = ?";
    
    try {
        // Preparar y ejecutar consulta
        $stmt = self::$db->prepare($query);
        if (!$stmt) {
            throw new RuntimeException("Error al preparar consulta: " . self::$db->error);
        }

        $stmt->bind_param("i", $this->id);
        if (!$stmt->execute()) {
            throw new RuntimeException("Error al ejecutar consulta: " . $stmt->error);
        }

        // Obtener resultados
        $result = $stmt->get_result();
        $divisiones = [];
        
        while ($fila = $result->fetch_object()) {
            // Sanitizar los nombres antes de devolverlos
            $fila->nombre = htmlspecialchars($fila->nombre, ENT_QUOTES, 'UTF-8');
            $divisiones[] = $fila;
        }
        
        return $divisiones;
    } finally {
        // Asegurarse de cerrar el statement
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

    public function esAdminConDivisiones() {
        return $this->admin == 1 && !empty($this->obtenerDivisiones());
    }

    public function actualizarImagenPerfil($rutaImagen = '') {
    // Asignar la nueva ruta
    $this->profile_photo = $rutaImagen;
    
    // Guardar solo el campo de imagen (actualización parcial)
    return $this->guardar(['imagen']);
}

public static function actualizarPerfil($id, $datos) {
    $usuario = self::find($id);
    if (!$usuario) {
        return ['success' => false, 'message' => 'Usuario no encontrado'];
    }

    if (empty($datos['nombre']) || empty($datos['apellido'])) {
        return ['success' => false, 'message' => 'Nombre y apellido son obligatorios'];
    }

    $usuario->nombre = $datos['nombre'];
    $usuario->apellido = $datos['apellido'];

    if (!empty($datos['password'])) {
        $usuario->password = password_hash($datos['password'], PASSWORD_DEFAULT);
    }

    $exito = $usuario->guardar();

    if ($exito) {
        return ['success' => true, 'message' => 'Perfil actualizado correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al guardar en la base de datos'];
    }
}

} 