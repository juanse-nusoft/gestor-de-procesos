<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria
    public static function consultarSQL($query, $params = []) {
        // Verificar conexión
        if (!self::$db || !self::$db->ping()) {
            throw new Exception("Error de conexión a la base de datos");
        }
    
        $stmt = self::$db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . self::$db->error);
        }
    
        // Bindear parámetros si existen
        if (!empty($params)) {
            $types = '';
            $values = [];
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i'; // integer
                } elseif (is_double($param)) {
                    $types .= 'd'; // double
                } else {
                    $types .= 's'; // string
                }
                $values[] = $param;
            }
            
            $stmt->bind_param($types, ...$values);
        }
    
        // Ejecutar consulta
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }
    
        // Obtener resultados
        $result = $stmt->get_result();
        $array = [];
        
        if ($result) {
            while ($registro = $result->fetch_assoc()) {
                $array[] = static::crearObjeto($registro);
            }
            $result->free();
        }
        
        $stmt->close();
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    protected function sanitizarAtributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            
            // Manejar valores nulos o vacíos
            if (property_exists($this, $columna)) {
                $value = $this->{$columna};
                $atributos[$columna] = ($value === null || $value === '') ? null : $value;
            }
        }
        return $atributos;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    public function guardar() {
        // Verificar conexión a la base de datos
        if (!self::$db || !self::$db->ping()) {
            throw new Exception("Error de conexión a la base de datos");
        }
    
        // Sanitizar todos los atributos antes de guardar
        $atributos = $this->sanitizarAtributos();
        
        // Determinar si es INSERT o UPDATE
        if (!is_null($this->id)) {
            return $this->actualizar($atributos);
        } else {
            return $this->crear($atributos);
        }
    }

    // Todos los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        return self::consultarSQL($query);
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ? LIMIT 1";
        $resultado = self::consultarSQL($query, [$id]);
        return array_shift($resultado);
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        if (!is_numeric($limite)) {
            throw new Exception("El límite debe ser numérico");
        }
        
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ?";
        return self::consultarSQL($query, [$limite]);
    }

     // Busca un registro por su id
     public static function where($columna, $valor) {
        // Validar nombre de columna para prevenir inyección
        if (!in_array($columna, static::$columnasDB)) {
            throw new Exception("Columna no válida");
        }
        
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = ? LIMIT 1";
        $resultado = self::consultarSQL($query, [$valor]);
        return array_shift($resultado);
    }

    //Consulta plana de SQL y usar cuando los modelos no son suficientes
    public static function SQL($query) {
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // crea un nuevo registro
    public function crear($atributos) {
        // Construir la consulta SQL preparada
        $columnas = implode(', ', array_keys($atributos));
        $placeholders = implode(', ', array_fill(0, count($atributos), '?'));
        
        $query = "INSERT INTO " . static::$tabla . " ($columnas) VALUES ($placeholders)";
        
        // Preparar la consulta
        $stmt = self::$db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . self::$db->error);
        }
        
        // Generar tipos para bind_param (s = string, i = integer, d = double)
        $tipos = '';
        $valores = [];
        
        foreach ($atributos as $valor) {
            if (is_int($valor)) {
                $tipos .= 'i';
            } elseif (is_float($valor)) {
                $tipos .= 'd';
            } else {
                $tipos .= 's';
            }
            $valores[] = $valor;
        }
        
        // Vincular parámetros y ejecutar
        $stmt->bind_param($tipos, ...$valores);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al crear registro: " . $stmt->error);
        }
        
        // Obtener el ID insertado
        $nuevoId = self::$db->insert_id;
        $this->id = $nuevoId;
        
        $stmt->close();
        
        return [
            'resultado' => true,
            'id' => $nuevoId
        ];
    }

    // Actualizar el registro
    protected function actualizar($atributos) {
        // Validar que exista ID
        if (is_null($this->id)) {
            throw new Exception("No se puede actualizar un registro sin ID");
        }
    
        // Construir partes SET de la consulta
        $setParts = [];
        $valores = [];
        $tipos = '';
        
        foreach ($atributos as $columna => $valor) {
            $setParts[] = "$columna = ?";
            
            if (is_int($valor)) {
                $tipos .= 'i';
            } elseif (is_float($valor)) {
                $tipos .= 'd';
            } else {
                $tipos .= 's';
            }
            
            $valores[] = $valor;
        }
        
        // Añadir ID al final para el WHERE
        $valores[] = $this->id;
        $tipos .= 'i';
        
        // Construir consulta completa
        $query = "UPDATE " . static::$tabla . " SET " . implode(', ', $setParts) . 
                 " WHERE id = ? LIMIT 1";
        
        // Preparar y ejecutar
        $stmt = self::$db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . self::$db->error);
        }
        
        $stmt->bind_param($tipos, ...$valores);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar registro: " . $stmt->error);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return $affectedRows > 0;
    }

    // Eliminar un Registro por su ID
    public static function eliminar($id) {
        // Validar conexión
        if (!self::$db || !self::$db->ping()) {
            throw new Exception("Error de conexión a la base de datos");
        }
    
        // Validar ID
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("ID de eliminación no válido");
        }
    
        // Consulta preparada
        $query = "DELETE FROM " . static::$tabla . " WHERE id = ? LIMIT 1";
        $stmt = self::$db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error al preparar consulta de eliminación: " . self::$db->error);
        }
    
        // Vincular parámetro y ejecutar
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            throw new Exception("Error al eliminar registro: " . $stmt->error);
        }
    
        // Verificar si realmente se eliminó algo
        $filasAfectadas = $stmt->affected_rows;
        $stmt->close();
    
        return $filasAfectadas > 0;
    }
}