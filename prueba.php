public static function filtrarPaginado($filtros = []) {
        // Consulta base (similar a tu método filtrar existente)
        $query = "SELECT SQL_CALC_FOUND_ROWS s.*, m.nombre AS categoria_nombre, d.nombre AS division_nombre
                  FROM solutions s
                  LEFT JOIN modulos m ON s.categories = m.id
                  LEFT JOIN divisiones d ON s.division = d.division_id";
        
        $where = [];
        $params = [];
        
        // Filtros (tu lógica existente)
        if (!empty($filtros['query'])) {
            $where[] = "(s.title LIKE ? OR s.description LIKE ?)";
            $params[] = "%{$filtros['query']}%";
            $params[] = "%{$filtros['query']}%";
        }
        
        // ... (agrega tus otros filtros aquí)
        
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
            $types = str_repeat('s', count($params) - 2) . 'ii'; // Los últimos 2 parámetros son enteros
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $soluciones = $result->fetch_all(MYSQLI_ASSOC);
        
        // Obtener el total de registros (sin límite)
        $total = self::$db->query("SELECT FOUND_ROWS()")->fetch_row()[0];
        
        return [
            'soluciones' => array_map(function($solucion) {
                return new static($solucion);
            }, $soluciones),
            'total' => $total
        ];
    }