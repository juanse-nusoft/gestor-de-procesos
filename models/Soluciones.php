<?php

namespace Model;

class Soluciones extends ActiveRecord{
    protected static $tabla = 'solutions';
    protected static $columnasDB = ['id', 'title', 'description', 'categories', 'video', 'usuario_id', 'short_description', 'division', 'creation_date', 'modification_date', 'status'];

    //Propiedades
    public $id;
    public $title;
    public $description;
    public $categories;
    public $video;
    public $categoria_nombre;
    public $usuario_id;
    public $short_description;
    public $division;
    public $division_nombre;
    public $division_id;
    public $creation_date;
    public $modification_date;
    public $status;

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
        $this->division = $args['division'] ?? '';
        $this->division_nombre = $args['division_nombre'] ?? '';
        $this->division_id = $args['division_id'] ?? '';
        $this->creation_date = $args['creation_date'] ?? '';
        $this->modification_date = $args['modification_date'] ?? '';
        $this->status = $args['status'] ?? '';
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

    public static function filtrarPaginado($filtros = []) {
        // Consulta base
        $query = "SELECT SQL_CALC_FOUND_ROWS s.*, m.nombre AS categoria_nombre, d.nombre AS division_nombre
                  FROM solutions s
                  LEFT JOIN modulos m ON s.categories = m.id
                  LEFT JOIN divisiones d ON s.division = d.division_id";
        
        $where = [];
        $params = [];
        
        // Aplicar filtros
        if (!empty($filtros['query'])) {
            $where[] = "(s.title LIKE ? OR s.description LIKE ?)";
            $params[] = "%{$filtros['query']}%";
            $params[] = "%{$filtros['query']}%";
        }
        
        if (!empty($filtros['categoria'])) {
            $where[] = "s.categories = ?";
            $params[] = $filtros['categoria'];
        }
        
        if (!empty($filtros['division_ids'])) {
            if (is_array($filtros['division_ids'])) {
                $placeholders = implode(',', array_fill(0, count($filtros['division_ids']), '?'));
                $where[] = "s.division IN ($placeholders)";
                $params = array_merge($params, $filtros['division_ids']);
            } else {
                $where[] = "s.division = ?";
                $params[] = $filtros['division_ids'];
            }
        }
        
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        // Paginación
        $pagina = max(1, (int)($filtros['pagina'] ?? 1));
        $porPagina = (int)($filtros['por_pagina'] ?? 10);
        $offset = ($pagina - 1) * $porPagina;
        
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $porPagina;
        $params[] = $offset;
        
        // Ejecutar consulta
        $stmt = self::$db->prepare($query);
        if ($params) {
            $types = str_repeat('s', count($params) - 2) . 'ii'; // Los últimos 2 son enteros
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Convertir resultados a objetos Soluciones
        $soluciones = [];
        while ($row = $result->fetch_assoc()) {
            $soluciones[] = new static($row);
        }
        
        // Obtener el total de registros
        $total = self::$db->query("SELECT FOUND_ROWS()")->fetch_row()[0];
        
        return [
            'soluciones' => $soluciones,
            'total' => $total
        ];
    }

    protected static function ejecutarSQL($sql, $params = []) {
        $stmt = self::$db->prepare($sql);
        
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $output = [];
        while ($row = $result->fetch_assoc()) {
            $output[] = new static($row);
        }
        
        return $output;
    }

    public function datos_proceso(){
        $query = "SELECT s.id, 
                        d.nombre AS division_nombre, 
                        s.status, 
                        m.nombre AS categoria_nombre, 
                        s.creation_date, 
                        s.video, 
                        CONCAT(u.nombre, ' ', u.apellido) AS nombre_usuario,
                        d.division_id,
                        s.categories AS categoria_id
                    FROM solutions s
                    INNER JOIN divisiones d ON s.division = d.division_id
                    INNER JOIN modulos m ON s.categories = m.id
                    INNER JOIN usuarios u ON s.usuario_id = u.id
                    WHERE s.id = ?";
                    
                    $stmt = self::$db->prepare($query);
                    $stmt->bind_param("i", $this->id);
                    $stmt->execute();
                    
                    $result = $stmt->get_result();
                    return $result->fetch_all(MYSQLI_ASSOC);
    }
        
    public static function obtenerPendientesEliminacion($divisionesUsuario = []) {
        $query = "SELECT s.*, d.nombre AS division_nombre, m.nombre AS categoria_nombre 
                  FROM solutions s
                  LEFT JOIN divisiones d ON s.division = d.division_id
                  LEFT JOIN modulos m ON s.categories = m.id
                  WHERE s.status = 4";
    
        // Si el usuario tiene divisiones asignadas, filtrar
        if (!empty($divisionesUsuario)) {
            $placeholders = implode(',', array_fill(0, count($divisionesUsuario), '?'));
            $query .= " AND s.division IN ($placeholders)";
            return self::ejecutarSQL($query, $divisionesUsuario);
        }
    
        return []; // Si no es admin o no tiene divisiones, retornar vacío
    }

    public static function obtenerPendientesEdicion($divisionesUsuario = []) {
        $query = "SELECT s.*, d.nombre AS division_nombre, m.nombre AS categoria_nombre 
                  FROM solutions s
                  LEFT JOIN divisiones d ON s.division = d.division_id
                  LEFT JOIN modulos m ON s.categories = m.id
                  WHERE s.status = 3"; // Filtramos por estado 3
    
        if (!empty($divisionesUsuario)) {
            $placeholders = implode(',', array_fill(0, count($divisionesUsuario), '?'));
            $query .= " AND s.division IN ($placeholders)";
            return self::ejecutarSQL($query, $divisionesUsuario);
        }
    
        return self::consultarSQL($query);
    }
    
}