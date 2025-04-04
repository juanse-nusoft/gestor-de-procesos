<?php
namespace Model;

class UsuarioDivision extends ActiveRecord {
    protected static $tabla = 'usuario_division';
    protected static $columnasDB = ['usuario_id', 'division_id'];
    
    // No incluyas $id aquí ya que no existe en tu tabla
    public $usuario_id;
    public $division_id;

    public function __construct($args = []) {
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->division_id = $args['division_id'] ?? null;
    }

    // Sobreescribe el método crear para adaptarlo a tu esquema
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos (sin incluir ID)
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        // Resultado de la consulta
        $resultado = self::$db->query($query);
        
        return [
           'resultado' => $resultado,
           // No devolvemos insert_id ya que no hay auto-incremental
        ];
    }
}