<?php
/**
 * Modelo LugarTuristico
 * Gestiona todas las operaciones CRUD de lugares turísticos
 */

require_once __DIR__ . '/Model.php';

class LugarTuristico extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'lugares_turisticos';
    }

    /**
     * Override del método getById para usar la columna correcta
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM lugares_turisticos WHERE id_lugar = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("Error en LugarTuristico::getById(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crea un nuevo lugar turístico
     * @param array $data
     * @return array [success, message, id?]
     */
    public function crear($data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'nombre', 'descripcion', 'categoria', 'precio_entrada'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar categoría
        $categorias_validas = ['mirador', 'cascada', 'aventura', 'cultural'];
        if (!in_array($data['categoria'], $categorias_validas)) {
            return ['success' => false, 'message' => 'Categoría no válida'];
        }

        $sql = "INSERT INTO lugares_turisticos 
                (nombre, descripcion, categoria, direccion, precio_entrada, 
                 horario_apertura, horario_cierre, imagen_lugar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $result = $this->execute($sql, [
            $data['nombre'],
            $data['descripcion'],
            $data['categoria'],
            $data['direccion'] ?? null,
            $data['precio_entrada'],
            $data['horario_apertura'] ?? null,
            $data['horario_cierre'] ?? null,
            $data['imagen_lugar'] ?? 'default.jpg'
        ]);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Lugar turístico creado exitosamente',
                'id' => $this->lastInsertId()
            ];
        }

        return ['success' => false, 'message' => 'Error al crear el lugar turístico'];
    }

    /**
     * Actualiza un lugar turístico
     * @param int $id
     * @param array $data
     * @return array [success, message]
     */
    public function actualizar($id, $data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'nombre', 'descripcion', 'categoria', 'precio_entrada'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        $sql = "UPDATE lugares_turisticos 
                SET nombre = ?, descripcion = ?, categoria = ?, direccion = ?, 
                    precio_entrada = ?, horario_apertura = ?, horario_cierre = ?, 
                    imagen_lugar = ? 
                WHERE id_lugar = ?";
        
        $result = $this->execute($sql, [
            $data['nombre'],
            $data['descripcion'],
            $data['categoria'],
            $data['direccion'] ?? null,
            $data['precio_entrada'],
            $data['horario_apertura'] ?? null,
            $data['horario_cierre'] ?? null,
            $data['imagen_lugar'] ?? 'default.jpg',
            $id
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Lugar turístico actualizado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar el lugar turístico'];
    }

    /**
     * Busca lugares turísticos con filtros
     * @param array $filtros [categoria, precio_min, precio_max, busqueda]
     * @return array
     */
    public function buscar($filtros = []) {
        $sql = "SELECT * FROM lugares_turisticos WHERE 1=1";
        $params = [];

        // Filtro por categoría
        if (!empty($filtros['categoria'])) {
            $sql .= " AND categoria = ?";
            $params[] = $filtros['categoria'];
        }

        // Filtro por precio mínimo
        if (isset($filtros['precio_min']) && $filtros['precio_min'] !== '') {
            $sql .= " AND precio_entrada >= ?";
            $params[] = $filtros['precio_min'];
        }

        // Filtro por precio máximo
        if (isset($filtros['precio_max']) && $filtros['precio_max'] !== '') {
            $sql .= " AND precio_entrada <= ?";
            $params[] = $filtros['precio_max'];
        }

        // Búsqueda por nombre o descripción
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
            $busqueda = '%' . $filtros['busqueda'] . '%';
            $params[] = $busqueda;
            $params[] = $busqueda;
        }

        $sql .= " ORDER BY nombre ASC";

        return $this->query($sql, $params);
    }

    /**
     * Obtiene lugares por categoría
     * @param string $categoria
     * @return array
     */
    public function getPorCategoria($categoria) {
        $sql = "SELECT * FROM lugares_turisticos WHERE categoria = ? ORDER BY nombre ASC";
        return $this->query($sql, [$categoria]);
    }

    /**
     * Obtiene los lugares más comentados
     * @param int $limit
     * @return array
     */
    public function getMasComentados($limit = 5) {
        $sql = "SELECT l.*, COUNT(c.id_comentario) as total_comentarios
                FROM lugares_turisticos l
                LEFT JOIN comentarios c ON l.id_lugar = c.id_lugar
                GROUP BY l.id_lugar
                ORDER BY total_comentarios DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene lugares destacados (mejor calificados)
     * @param int $limit
     * @return array
     */
    public function getDestacados($limit = 3) {
        $sql = "SELECT l.*, 
                COALESCE(AVG(c.calificacion), 0) as promedio_calificacion,
                COUNT(c.id_comentario) as total_comentarios
                FROM lugares_turisticos l
                LEFT JOIN comentarios c ON l.id_lugar = c.id_lugar
                GROUP BY l.id_lugar
                HAVING total_comentarios > 0
                ORDER BY promedio_calificacion DESC, total_comentarios DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Cuenta lugares por categoría
     * @return array
     */
    public function contarPorCategoria() {
        $sql = "SELECT categoria, COUNT(*) as total 
                FROM lugares_turisticos 
                GROUP BY categoria";
        return $this->query($sql);
    }

    /**
     * Elimina un lugar turístico (verifica dependencias)
     * @param int $id
     * @return array [success, message]
     */
    public function eliminar($id) {
        // Verificar si tiene comentarios
        $sql = "SELECT COUNT(*) as total FROM comentarios WHERE id_lugar = ?";
        $result = $this->queryOne($sql, [$id]);
        
        if ($result && $result['total'] > 0) {
            return [
                'success' => false,
                'message' => 'No se puede eliminar el lugar porque tiene comentarios asociados'
            ];
        }

        // Eliminar usando la columna correcta
        try {
            $stmt = $this->db->prepare("DELETE FROM lugares_turisticos WHERE id_lugar = ?");
            $success = $stmt->execute([$id]);
            
            if ($success) {
                return ['success' => true, 'message' => 'Lugar turístico eliminado exitosamente'];
            }
            return ['success' => false, 'message' => 'Error al eliminar el lugar turístico'];
        } catch (PDOException $e) {
            error_log("Error al eliminar lugar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar el lugar turístico'];
        }
    }

    /**
     * Obtiene el detalle completo de un lugar con estadísticas
     * @param int $id
     * @return array|null
     */
    public function getDetalleCompleto($id) {
        $sql = "SELECT l.*, 
                COALESCE(AVG(c.calificacion), 0) as promedio_calificacion,
                COUNT(c.id_comentario) as total_comentarios
                FROM lugares_turisticos l
                LEFT JOIN comentarios c ON l.id_lugar = c.id_lugar
                WHERE l.id_lugar = ?
                GROUP BY l.id_lugar";
        
        return $this->queryOne($sql, [$id]);
    }
}