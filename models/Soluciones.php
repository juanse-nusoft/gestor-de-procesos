<?php

namespace Model;

class Soluciones extends ActiveRecord{
    protected static $tabla = 'solutions';
    protected static $columnasDB = ['id', 'title', 'description', 'categories', 'video', 'usuario_id', 'short_description'];

    //Propiedades
    public $id;
    public $title;
    public $description;
    public $categories;
    public $video;
    public $categoria_nombre;
    public $usuario_id;
    public $short_description;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->title = $args['title'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->categories = $args['categories'] ?? '';
        $this->video = $args['video'] ?? '';
        $this->categoria_nombre = $args['categoria_nombre'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->short_description = $args['short_description'] ?? '';
    }

    // Método para buscar soluciones
    public static function buscar($termino, $categoria = '') {
        $termino = self::$db->escape_string($termino);
        $categoria = self::$db->escape_string($categoria);
    
        $query = "SELECT solutions.*, modulos.nombre AS categoria_nombre 
                  FROM " . static::$tabla . " 
                  LEFT JOIN modulos ON solutions.categories = modulos.id
                  WHERE (solutions.title LIKE '%$termino%' 
                  OR solutions.description LIKE '%$termino%')";
    
        // Si se selecciona una categoría, la agregamos al filtro
        if ($categoria) {
            $query .= " AND solutions.categories = '$categoria'";
        }
    
        return self::consultarSQL($query);
    }

    public static function solucionesConCategorias() {
        $query = "SELECT solutions.*, modulos.nombre AS categoria_nombre 
                  FROM solutions 
                  LEFT JOIN modulos ON solutions.categories = modulos.id";
        return self::consultarSQL($query);
    }
    public static function solucionEditar($id){
        $query = "SELECT solutions.*, modulos.nombre AS categoria_nombre 
                  FROM solutions 
                  LEFT JOIN modulos ON solutions.categories = modulos.id
                  WHERE solutions.id = $id";
        return self::consultarSQL($query);
    }
    
}