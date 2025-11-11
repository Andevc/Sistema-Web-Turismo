<?php
/**
 * Clase Model - Clase base para todos los modelos
 * Proporciona conexión a la base de datos y métodos comunes
 */

require_once __DIR__ . '/../config/database.php';

class Model {
    protected $db;
    protected $table;

    /**
     * Constructor - Obtiene la conexión a la base de datos
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todos los registros de una tabla
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getAll(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un registro por ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        try {
            // Determinar el nombre de la columna ID según la tabla
            $idColumn = 'id_' . $this->getSingularTable();
            
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$idColumn} = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("Error en getById(): " . $e->getMessage());
            error_log("Tabla: {$this->table}, ID Column: {$idColumn}, ID: {$id}");
            return null;
        }
    }

    /**
     * Elimina un registro por ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_{$this->getSingularTable()} = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error en delete(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cuenta el total de registros
     * @return int
     */
    public function count() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
            $result = $stmt->fetch();
            return (int)$result['total'];
        } catch (PDOException $e) {
            error_log("Error en count(): " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el nombre singular de la tabla (para IDs)
     * @return string
     */
    protected function getSingularTable() {
        // Remover 's' final si existe
        if (substr($this->table, -1) === 's') {
            return substr($this->table, 0, -1);
        }
        return $this->table;
    }

    /**
     * Valida que los campos requeridos no estén vacíos
     * @param array $data
     * @param array $required
     * @return array Errores encontrados
     */
    protected function validateRequired($data, $required) {
        $errors = [];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[] = "El campo {$field} es requerido";
            }
        }
        return $errors;
    }

    /**
     * Valida formato de email
     * @param string $email
     * @return bool
     */
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Ejecuta una consulta preparada y retorna el resultado
     * @param string $sql
     * @param array $params
     * @return array
     */
    protected function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en query(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ejecuta una consulta preparada y retorna un solo registro
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    protected function queryOne($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("Error en queryOne(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Ejecuta una consulta de inserción, actualización o eliminación
     * @param string $sql
     * @param array $params
     * @return bool
     */
    protected function execute($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error en execute(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el ID del último registro insertado
     * @return int
     */
    protected function lastInsertId() {
        return $this->db->lastInsertId();
    }
}