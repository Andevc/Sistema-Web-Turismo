<?php
/**
 * Modelo Comentario
 * Gestiona todas las operaciones CRUD de comentarios
 */

require_once __DIR__ . '/Model.php';

class Comentario extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'comentarios';
    }

    /**
     * Crea un nuevo comentario
     * @param array $data
     * @return array [success, message, id?]
     */
    public function crear($data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, [
            'id_usuario', 'id_lugar', 'calificacion', 'comentario'
        ]);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar calificación (1-5)
        if ($data['calificacion'] < 1 || $data['calificacion'] > 5) {
            return ['success' => false, 'message' => 'La calificación debe estar entre 1 y 5'];
        }

        // Verificar si el usuario ya comentó en este lugar
        if ($this->usuarioYaComento($data['id_usuario'], $data['id_lugar'])) {
            return [
                'success' => false,
                'message' => 'Ya has comentado en este lugar. Puedes editar tu comentario anterior.'
            ];
        }

        $sql = "INSERT INTO comentarios (id_usuario, id_lugar, calificacion, comentario) 
                VALUES (?, ?, ?, ?)";
        
        $result = $this->execute($sql, [
            $data['id_usuario'],
            $data['id_lugar'],
            $data['calificacion'],
            $data['comentario']
        ]);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Comentario agregado exitosamente',
                'id' => $this->lastInsertId()
            ];
        }

        return ['success' => false, 'message' => 'Error al agregar el comentario'];
    }

    /**
     * Actualiza un comentario
     * @param int $id
     * @param array $data
     * @return array [success, message]
     */
    public function actualizar($id, $data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, ['calificacion', 'comentario']);
        
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar calificación
        if ($data['calificacion'] < 1 || $data['calificacion'] > 5) {
            return ['success' => false, 'message' => 'La calificación debe estar entre 1 y 5'];
        }

        $sql = "UPDATE comentarios SET calificacion = ?, comentario = ? WHERE id_comentario = ?";
        
        $result = $this->execute($sql, [
            $data['calificacion'],
            $data['comentario'],
            $id
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Comentario actualizado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar el comentario'];
    }

    /**
     * Obtiene todos los comentarios de un lugar
     * @param int $lugar_id
     * @return array
     */
    public function getPorLugar($lugar_id) {
        $sql = "SELECT c.*, u.nombre_completo as usuario_nombre
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                WHERE c.id_lugar = ?
                ORDER BY c.fecha_comentario DESC";
        
        return $this->query($sql, [$lugar_id]);
    }

    /**
     * Obtiene comentarios de un usuario
     * @param int $usuario_id
     * @return array
     */
    public function getPorUsuario($usuario_id) {
        $sql = "SELECT c.*, l.nombre as lugar_nombre
                FROM comentarios c
                INNER JOIN lugares_turisticos l ON c.id_lugar = l.id_lugar
                WHERE c.id_usuario = ?
                ORDER BY c.fecha_comentario DESC";
        
        return $this->query($sql, [$usuario_id]);
    }

    /**
     * Verifica si un usuario ya comentó en un lugar
     * @param int $usuario_id
     * @param int $lugar_id
     * @return bool
     */
    public function usuarioYaComento($usuario_id, $lugar_id) {
        $sql = "SELECT COUNT(*) as total 
                FROM comentarios 
                WHERE id_usuario = ? AND id_lugar = ?";
        
        $result = $this->queryOne($sql, [$usuario_id, $lugar_id]);
        return $result && $result['total'] > 0;
    }

    /**
     * Obtiene el promedio de calificación de un lugar
     * @param int $lugar_id
     * @return array [promedio, total_comentarios]
     */
    public function getPromedioCalificacion($lugar_id) {
        $sql = "SELECT 
                COALESCE(AVG(calificacion), 0) as promedio,
                COUNT(*) as total_comentarios
                FROM comentarios
                WHERE id_lugar = ?";
        
        $result = $this->queryOne($sql, [$lugar_id]);
        
        return [
            'promedio' => $result ? round($result['promedio'], 1) : 0,
            'total_comentarios' => $result ? (int)$result['total_comentarios'] : 0
        ];
    }

    /**
     * Obtiene los últimos comentarios del sistema
     * @param int $limit
     * @return array
     */
    public function getUltimos($limit = 5) {
        $sql = "SELECT c.*, 
                u.nombre_completo as usuario_nombre,
                l.nombre as lugar_nombre
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                INNER JOIN lugares_turisticos l ON c.id_lugar = l.id_lugar
                ORDER BY c.fecha_comentario DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene comentarios mejor calificados
     * @param int $limit
     * @return array
     */
    public function getMejorCalificados($limit = 5) {
        $sql = "SELECT c.*, 
                u.nombre_completo as usuario_nombre,
                l.nombre as lugar_nombre
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                INNER JOIN lugares_turisticos l ON c.id_lugar = l.id_lugar
                WHERE c.calificacion >= 4
                ORDER BY c.calificacion DESC, c.fecha_comentario DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene detalle completo de un comentario
     * @param int $id
     * @return array|null
     */
    public function getDetalleCompleto($id) {
        $sql = "SELECT c.*, 
                u.nombre_completo as usuario_nombre,
                u.email as usuario_email,
                l.nombre as lugar_nombre
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                INNER JOIN lugares_turisticos l ON c.id_lugar = l.id_lugar
                WHERE c.id_comentario = ?";
        
        return $this->queryOne($sql, [$id]);
    }

    /**
     * Elimina un comentario (verifica permisos)
     * @param int $id
     * @param int $usuario_id (para verificar propiedad del comentario)
     * @return array [success, message]
     */
    public function eliminar($id, $usuario_id = null) {
        // Si se proporciona usuario_id, verificar que el comentario le pertenezca
        if ($usuario_id !== null) {
            $comentario = $this->getById($id);
            if (!$comentario || $comentario['id_usuario'] != $usuario_id) {
                return [
                    'success' => false,
                    'message' => 'No tienes permiso para eliminar este comentario'
                ];
            }
        }

        if ($this->delete($id)) {
            return ['success' => true, 'message' => 'Comentario eliminado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar el comentario'];
    }

    /**
     * Cuenta comentarios por calificación
     * @param int $lugar_id
     * @return array
     */
    public function contarPorCalificacion($lugar_id) {
        $sql = "SELECT calificacion, COUNT(*) as total
                FROM comentarios
                WHERE id_lugar = ?
                GROUP BY calificacion
                ORDER BY calificacion DESC";
        
        return $this->query($sql, [$lugar_id]);
    }

    /**
     * Obtiene estadísticas generales de comentarios
     * @return array
     */
    public function getEstadisticas() {
        $sql = "SELECT 
                COUNT(*) as total_comentarios,
                COALESCE(AVG(calificacion), 0) as promedio_general,
                COUNT(DISTINCT id_lugar) as lugares_comentados,
                COUNT(DISTINCT id_usuario) as usuarios_activos
                FROM comentarios";
        
        return $this->queryOne($sql);
    }
}