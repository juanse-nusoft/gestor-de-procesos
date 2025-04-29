<?php
namespace Model;

class UsuarioDivision extends ActiveRecord {
    protected static $tabla = 'usuario_division';
    protected static $columnasDB = ['usuario_id', 'division_id'];
    
    public $usuario_id;
    public $division_id;

    public function __construct($args = []) {
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->division_id = $args['division_id'] ?? null;
    }

    
    public function crearUsuario() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        // Resultado de la consulta
        $resultado = self::$db->query($query);
        
        return [
           'resultado' => $resultado,
        ];
    }
}