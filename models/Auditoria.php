<?php

namespace Model;

class Auditoria extends ActiveRecord{
    protected static $tabla = 'auditoria';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'accion', 'datos_anteriores', 'datos_nuevos', 'fecha'];

    //Propiedaades
    public $id;
    public $accion;
    public $fecha;
    public $datos_anteriores;
    public $datos_nuevos;
    public $nombre;
    public $apellido;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['nombre'] ?? '';
        $this->accion = $args['accion'] ?? '';
        $this->datos_anteriores = $args['datos_anteriores'] ?? '';
        $this->datos_nuevos = $args['datos_nuevos'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
    }

    public static function datosAuditoria(){
        $query = "SELECT a.id, a.accion, a.fecha, a.datos_anteriores, a.datos_nuevos, u.nombre AS nombre, 
        u.apellido AS apellido FROM " . static::$tabla . " a INNER JOIN usuarios u ";
        $query .= "ON a.usuario_id = u.id";
        return self::consultarSQL($query);
    }
}