<?php 

namespace Model;

class Modulo extends ActiveRecord{
    protected static $tabla = 'modulos';
    protected static $columnasDB = ['id', 'nombre', 'division_id'];

    public $id;
    public $nombre;
    public $division_id;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->division_id = $args['division_id'] ?? '';
    }

    public function obtenerCategoriasPorDivision() {
        
        if (empty($this->division_id)) {
            error_log("Error: division_id no está definido");
            return [];
        }
        
        // Debug: Verificar el valor de division_id
        error_log("Buscando categorías para division_id: " . $this->division_id);
        
        $query = "SELECT id, nombre FROM modulos WHERE division_id = ?";
        $stmt = self::$db->prepare($query);
        
        if (!$stmt) {
            error_log("Error en preparación de consulta: " . self::$db->error);
            return [];
        }
        
        // Asegurarnos que division_id es un entero
        $divisionId = (int)$this->division_id;
        $stmt->bind_param("i", $divisionId);
        
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . $stmt->error);
            return [];
        }
        
        $result = $stmt->get_result();
        $categorias = $result->fetch_all(MYSQLI_ASSOC);
        
        // Debug: Ver resultados
        error_log("Categorías encontradas: " . print_r($categorias, true));
        
        return $categorias;
    }

    public static function categoriasPorDivisiones($divisionIds) {
        if (empty($divisionIds)) {
            return [];
        }
    
        $placeholders = implode(',', array_fill(0, count($divisionIds), '?'));
        $query = "SELECT id, nombre FROM modulos WHERE division_id IN ($placeholders)";
        $stmt = self::$db->prepare($query);
        
        if (!$stmt) {
            error_log("Error en preparación de consulta: " . self::$db->error);
            return [];
        }
        
        // Tipos de parámetros (todos 'i' para integers)
        $types = str_repeat('i', count($divisionIds));
        $stmt->bind_param($types, ...$divisionIds);
        
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . $stmt->error);
            return [];
        }
        
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}