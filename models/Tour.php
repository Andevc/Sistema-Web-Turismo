<?php
/**
 * Modelo Tour
 * Gestiona todas las operaciones CRUD de tours
 */

require_once __DIR__ . '/Model.php';

class Tour extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'tours';
    }

    /**
     * Crea un nuevo tour
     * @param array $data
     * @return array [success, message, id?]
     */
    public function crear($data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'nombre', 'descripcion', 'precio', 'cupo_maximo'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar que precio y cupo sean numéricos positivos
        if ($data['precio'] <= 0) {
            return ['success' => false, 'message' => 'El precio debe ser mayor a 0'];
        }

        if ($data['cupo_maximo'] <= 0) {
            return ['success' => false, 'message' => 'El cupo máximo debe ser mayor a 0'];
        }

        $sql = "INSERT INTO tours (nombre, descripcion, precio, cupo_maximo) 
                VALUES (?, ?, ?, ?)";
        
        $result = $this->execute($sql, [
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['cupo_maximo']
        ]);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Tour creado exitosamente',
                'id' => $this->lastInsertId()
            ];
        }

        return ['success' => false, 'message' => 'Error al crear el tour'];
    }

    /**
     * Actualiza un tour
     * @param int $id
     * @param array $data
     * @return array [success, message]
     */
    public function actualizar($id, $data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'nombre', 'descripcion', 'precio', 'cupo_maximo'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        $sql = "UPDATE tours 
                SET nombre = ?, descripcion = ?, precio = ?, cupo_maximo = ? 
                WHERE id_tour = ?";
        
        $result = $this->execute($sql, [
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['cupo_maximo'],
            $id
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Tour actualizado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar el tour'];
    }

    /**
     * Busca tours con filtros
     * @param array $filtros [precio_min, precio_max, busqueda, orden]
     * @return array
     */
    public function buscar($filtros = []) {
        $sql = "SELECT * FROM tours WHERE 1=1";
        $params = [];

        // Filtro por precio mínimo
        if (isset($filtros['precio_min']) && $filtros['precio_min'] !== '') {
            $sql .= " AND precio >= ?";
            $params[] = $filtros['precio_min'];
        }

        // Filtro por precio máximo
        if (isset($filtros['precio_max']) && $filtros['precio_max'] !== '') {
            $sql .= " AND precio <= ?";
            $params[] = $filtros['precio_max'];
        }

        // Búsqueda por nombre o descripción
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
            $busqueda = '%' . $filtros['busqueda'] . '%';
            $params[] = $busqueda;
            $params[] = $busqueda;
        }

        // Ordenamiento
        $orden = isset($filtros['orden']) ? $filtros['orden'] : 'nombre_asc';
        switch ($orden) {
            case 'precio_asc':
                $sql .= " ORDER BY precio ASC";
                break;
            case 'precio_desc':
                $sql .= " ORDER BY precio DESC";
                break;
            case 'nombre_desc':
                $sql .= " ORDER BY nombre DESC";
                break;
            default:
                $sql .= " ORDER BY nombre ASC";
        }

        return $this->query($sql, $params);
    }

    /**
     * Obtiene los tours más reservados
     * @param int $limit
     * @return array
     */
    public function getMasReservados($limit = 5) {
        $sql = "SELECT t.*, COUNT(r.id_reserva) as total_reservas
                FROM tours t
                LEFT JOIN reservas r ON t.id_tour = r.id_tour
                GROUP BY t.id_tour
                ORDER BY total_reservas DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Verifica disponibilidad de un tour para una fecha
     * @param int $tour_id
     * @param string $fecha
     * @return array [disponible, cupos_disponibles]
     */
    public function verificarDisponibilidad($tour_id, $fecha) {
        // Obtener cupo máximo del tour
        $tour = $this->getById($tour_id);
        if (!$tour) {
            return ['disponible' => false, 'cupos_disponibles' => 0];
        }

        // Contar personas reservadas para esa fecha
        $sql = "SELECT COALESCE(SUM(cantidad_personas), 0) as personas_reservadas 
                FROM reservas 
                WHERE id_tour = ? AND fecha_tour = ?";
        
        $result = $this->queryOne($sql, [$tour_id, $fecha]);
        $personas_reservadas = $result ? (int)$result['personas_reservadas'] : 0;
        
        $cupos_disponibles = $tour['cupo_maximo'] - $personas_reservadas;

        return [
            'disponible' => $cupos_disponibles > 0,
            'cupos_disponibles' => $cupos_disponibles,
            'cupo_maximo' => $tour['cupo_maximo']
        ];
    }

    /**
     * Obtiene el detalle completo de un tour con estadísticas
     * @param int $id
     * @return array|null
     */
    public function getDetalleCompleto($id) {
        $sql = "SELECT t.*, 
                COUNT(r.id_reserva) as total_reservas,
                COALESCE(SUM(r.cantidad_personas), 0) as total_personas
                FROM tours t
                LEFT JOIN reservas r ON t.id_tour = r.id_tour
                WHERE t.id_tour = ?
                GROUP BY t.id_tour";
        
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Obtiene tours disponibles (con cupos)
     * @return array
     */
    public function getDisponibles() {
        $sql = "SELECT t.*, 
                t.cupo_maximo - COALESCE(SUM(r.cantidad_personas), 0) as cupos_disponibles
                FROM tours t
                LEFT JOIN reservas r ON t.id_tour = r.id_tour AND r.fecha_tour >= CURDATE()
                GROUP BY t.id_tour
                HAVING cupos_disponibles > 0
                ORDER BY t.nombre ASC";
        
        return $this->query($sql);
    }

    /**
     * Elimina un tour (verifica dependencias)
     * @param int $id
     * @return array [success, message]
     */
    public function eliminar($id) {
        // Verificar si tiene reservas
        $sql = "SELECT COUNT(*) as total FROM reservas WHERE id_tour = ?";
        $result = $this->queryOne($sql, [$id]);
        
        if ($result && $result['total'] > 0) {
            return [
                'success' => false,
                'message' => 'No se puede eliminar el tour porque tiene reservas asociadas'
            ];
        }

        if ($this->delete($id)) {
            return ['success' => true, 'message' => 'Tour eliminado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar el tour'];
    }

    /**
     * Obtiene estadísticas de ingresos por tour
     * @return array
     */
    public function getEstadisticasIngresos() {
        $sql = "SELECT t.id_tour, t.nombre, t.precio,
                COUNT(r.id_reserva) as total_reservas,
                COALESCE(SUM(r.precio_total), 0) as ingresos_totales
                FROM tours t
                LEFT JOIN reservas r ON t.id_tour = r.id_tour
                GROUP BY t.id_tour
                ORDER BY ingresos_totales DESC";
        
        return $this->query($sql);
    }
}