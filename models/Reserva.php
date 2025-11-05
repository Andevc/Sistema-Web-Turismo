<?php
/**
 * Modelo Reserva
 * Gestiona todas las operaciones CRUD de reservas
 */

require_once __DIR__ . '/Model.php';

class Reserva extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'reservas';
    }

    /**
     * Crea una nueva reserva
     * @param array $data
     * @return array [success, message, id?]
     */
    public function crear($data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'id_usuario', 'id_tour', 'fecha_tour', 'cantidad_personas', 'precio_total'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar que la fecha sea futura
        if (strtotime($data['fecha_tour']) < strtotime('today')) {
            return ['success' => false, 'message' => 'La fecha del tour debe ser futura'];
        }

        // Validar cantidad de personas
        if ($data['cantidad_personas'] <= 0) {
            return ['success' => false, 'message' => 'La cantidad de personas debe ser mayor a 0'];
        }

        $sql = "INSERT INTO reservas 
                (id_usuario, id_tour, fecha_tour, cantidad_personas, precio_total) 
                VALUES (?, ?, ?, ?, ?)";
        
        $result = $this->execute($sql, [
            $data['id_usuario'],
            $data['id_tour'],
            $data['fecha_tour'],
            $data['cantidad_personas'],
            $data['precio_total']
        ]);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Reserva creada exitosamente',
                'id' => $this->lastInsertId()
            ];
        }

        return ['success' => false, 'message' => 'Error al crear la reserva'];
    }

    /**
     * Actualiza una reserva
     * @param int $id
     * @param array $data
     * @return array [success, message]
     */
    public function actualizar($id, $data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'fecha_tour', 'cantidad_personas', 'precio_total'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        $sql = "UPDATE reservas 
                SET fecha_tour = ?, cantidad_personas = ?, precio_total = ? 
                WHERE id_reserva = ?";
        
        $result = $this->execute($sql, [
            $data['fecha_tour'],
            $data['cantidad_personas'],
            $data['precio_total'],
            $id
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Reserva actualizada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar la reserva'];
    }

    /**
     * Obtiene todas las reservas de un usuario
     * @param int $usuario_id
     * @return array
     */
    public function getPorUsuario($usuario_id) {
        $sql = "SELECT r.*, t.nombre as tour_nombre, t.descripcion as tour_descripcion
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                WHERE r.id_usuario = ?
                ORDER BY r.fecha_tour DESC";
        
        return $this->query($sql, [$usuario_id]);
    }

    /**
     * Obtiene reservas activas (futuras) de un usuario
     * @param int $usuario_id
     * @return array
     */
    public function getActivasPorUsuario($usuario_id) {
        $sql = "SELECT r.*, t.nombre as tour_nombre, t.descripcion as tour_descripcion
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                WHERE r.id_usuario = ? AND r.fecha_tour >= CURDATE()
                ORDER BY r.fecha_tour ASC";
        
        return $this->query($sql, [$usuario_id]);
    }

    /**
     * Obtiene reservas pasadas de un usuario
     * @param int $usuario_id
     * @return array
     */
    public function getPasadasPorUsuario($usuario_id) {
        $sql = "SELECT r.*, t.nombre as tour_nombre, t.descripcion as tour_descripcion
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                WHERE r.id_usuario = ? AND r.fecha_tour < CURDATE()
                ORDER BY r.fecha_tour DESC";
        
        return $this->query($sql, [$usuario_id]);
    }

    /**
     * Obtiene el detalle completo de una reserva
     * @param int $id
     * @return array|null
     */
    public function getDetalleCompleto($id) {
        $sql = "SELECT r.*, 
                t.nombre as tour_nombre, 
                t.descripcion as tour_descripcion,
                t.precio as tour_precio,
                u.nombre_completo as usuario_nombre,
                u.email as usuario_email
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_reserva = ?";
        
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Obtiene todas las reservas con información completa (para admin)
     * @return array
     */
    public function getAllConDetalles() {
        $sql = "SELECT r.*, 
                t.nombre as tour_nombre,
                u.nombre_completo as usuario_nombre,
                u.email as usuario_email
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                ORDER BY r.fecha_reserva DESC";
        
        return $this->query($sql);
    }

    /**
     * Obtiene reservas por fecha de tour
     * @param string $fecha
     * @return array
     */
    public function getPorFecha($fecha) {
        $sql = "SELECT r.*, 
                t.nombre as tour_nombre,
                u.nombre_completo as usuario_nombre
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.fecha_tour = ?
                ORDER BY r.fecha_reserva ASC";
        
        return $this->query($sql, [$fecha]);
    }

    /**
     * Cancela una reserva (eliminar)
     * @param int $id
     * @param int $usuario_id (para verificar que la reserva pertenezca al usuario)
     * @return array [success, message]
     */
    public function cancelar($id, $usuario_id = null) {
        // Si se proporciona usuario_id, verificar que la reserva le pertenezca
        if ($usuario_id !== null) {
            $reserva = $this->getById($id);
            if (!$reserva || $reserva['id_usuario'] != $usuario_id) {
                return [
                    'success' => false,
                    'message' => 'No tienes permiso para cancelar esta reserva'
                ];
            }
        }

        if ($this->delete($id)) {
            return ['success' => true, 'message' => 'Reserva cancelada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al cancelar la reserva'];
    }

    /**
     * Obtiene estadísticas generales de reservas
     * @return array
     */
    public function getEstadisticas() {
        $sql = "SELECT 
                COUNT(*) as total_reservas,
                COALESCE(SUM(cantidad_personas), 0) as total_personas,
                COALESCE(SUM(precio_total), 0) as ingresos_totales,
                COUNT(DISTINCT id_usuario) as usuarios_unicos
                FROM reservas";
        
        return $this->queryOne($sql);
    }

    /**
     * Obtiene las próximas reservas (para dashboard)
     * @param int $limit
     * @return array
     */
    public function getProximas($limit = 10) {
        $sql = "SELECT r.*, 
                t.nombre as tour_nombre,
                u.nombre_completo as usuario_nombre
                FROM reservas r
                INNER JOIN tours t ON r.id_tour = t.id_tour
                INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.fecha_tour >= CURDATE()
                ORDER BY r.fecha_tour ASC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Calcula ingresos totales
     * @return float
     */
    public function calcularIngresosTotales() {
        $sql = "SELECT COALESCE(SUM(precio_total), 0) as total FROM reservas";
        $result = $this->queryOne($sql);
        return $result ? (float)$result['total'] : 0.0;
    }

    /**
     * Obtiene reservas por tour
     * @param int $tour_id
     * @return array
     */
    public function getPorTour($tour_id) {
        $sql = "SELECT r.*, 
                u.nombre_completo as usuario_nombre,
                u.email as usuario_email
                FROM reservas r
                INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_tour = ?
                ORDER BY r.fecha_tour ASC";
        
        return $this->query($sql, [$tour_id]);
    }
}