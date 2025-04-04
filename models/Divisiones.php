<?php

namespace Model;

class Divisiones extends ActiveRecord{
    protected static $tabla = 'divisiones';
    protected static $columnasDB = ['division_id', 'nombre'];

    public $division_id;
    public $nombre;

    public function __construct($args = [])
    {
        $this->division_id = $args['division_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}
?>