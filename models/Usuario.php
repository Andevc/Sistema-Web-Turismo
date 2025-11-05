<?php
/**
 * Modelo Usuario
 * Gestiona todas las operaciones CRUD de usuarios
 */

require_once __DIR__ . '/Model.php';

class Usuario extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'usuarios';
    }

    /**
     * Crea un nuevo usuario (Registro)
     * @param array $data
     * @return array [success, message, id?]
     */
    public function crear($data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, ['nombre_completo', 'email', 'password']);
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar email
        if (!$this->validateEmail($data['email'])) {
            return ['success' => false, 'message' => 'El formato del email no es válido'];
        }

        // Verificar si el email ya existe
        if ($this->existeEmail($data['email'])) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        // Insertar usuario
        $sql = "INSERT INTO usuarios (nombre_completo, email, password, rol) 
                VALUES (?, ?, ?, ?)";
        
        $rol = isset($data['rol']) ? $data['rol'] : 'turista';
        
        $result = $this->execute($sql, [
            $data['nombre_completo'],
            $data['email'],
            $data['password'], // Texto plano como se solicitó
            $rol
        ]);

        if ($result) {
            return [
                'success' => true, 
                'message' => 'Usuario registrado exitosamente',
                'id' => $this->lastInsertId()
            ];
        }

        return ['success' => false, 'message' => 'Error al registrar el usuario'];
    }

    /**
     * Autentica un usuario (Login)
     * @param string $email
     * @param string $password
     * @return array [success, message, usuario?]
     */
    public function autenticar($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email y contraseña son requeridos'];
        }

        $sql = "SELECT * FROM usuarios WHERE email = ? AND password = ?";
        $usuario = $this->queryOne($sql, [$email, $password]);

        if ($usuario) {
            return [
                'success' => true,
                'message' => 'Autenticación exitosa',
                'usuario' => $usuario
            ];
        }

        return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
    }

    /**
     * Actualiza los datos de un usuario
     * @param int $id
     * @param array $data
     * @return array [success, message]
     */
    public function actualizar($id, $data) {
        // Validar campos requeridos
        $errors = $this->validateRequired($data, ['nombre_completo', 'email']);
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        // Validar email
        if (!$this->validateEmail($data['email'])) {
            return ['success' => false, 'message' => 'El formato del email no es válido'];
        }

        // Verificar si el email ya existe en otro usuario
        $emailExistente = $this->queryOne(
            "SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?",
            [$data['email'], $id]
        );

        if ($emailExistente) {
            return ['success' => false, 'message' => 'El email ya está registrado por otro usuario'];
        }

        // Actualizar usuario
        if (isset($data['password']) && !empty($data['password'])) {
            // Si se proporciona nueva contraseña
            $sql = "UPDATE usuarios SET nombre_completo = ?, email = ?, password = ? 
                    WHERE id_usuario = ?";
            $result = $this->execute($sql, [
                $data['nombre_completo'],
                $data['email'],
                $data['password'],
                $id
            ]);
        } else {
            // Si no se cambia la contraseña
            $sql = "UPDATE usuarios SET nombre_completo = ?, email = ? 
                    WHERE id_usuario = ?";
            $result = $this->execute($sql, [
                $data['nombre_completo'],
                $data['email'],
                $id
            ]);
        }

        if ($result) {
            return ['success' => true, 'message' => 'Usuario actualizado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar el usuario'];
    }

    /**
     * Verifica si un email ya existe
     * @param string $email
     * @return bool
     */
    public function existeEmail($email) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE email = ?";
        $result = $this->queryOne($sql, [$email]);
        return $result && $result['total'] > 0;
    }

    /**
     * Obtiene usuario por email
     * @param string $email
     * @return array|null
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        return $this->queryOne($sql, [$email]);
    }

    /**
     * Cuenta total de usuarios por rol
     * @param string $rol
     * @return int
     */
    public function contarPorRol($rol) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE rol = ?";
        $result = $this->queryOne($sql, [$rol]);
        return $result ? (int)$result['total'] : 0;
    }

    /**
     * Obtiene todos los turistas
     * @return array
     */
    public function getTuristas() {
        $sql = "SELECT id_usuario, nombre_completo, email, fecha_registro 
                FROM usuarios WHERE rol = 'turista' ORDER BY fecha_registro DESC";
        return $this->query($sql);
    }

    /**
     * Elimina un usuario (Override para verificar dependencias)
     * @param int $id
     * @return array [success, message]
     */
    public function eliminar($id) {
        // Verificar si tiene reservas
        $sql = "SELECT COUNT(*) as total FROM reservas WHERE id_usuario = ?";
        $result = $this->queryOne($sql, [$id]);
        
        if ($result && $result['total'] > 0) {
            return [
                'success' => false, 
                'message' => 'No se puede eliminar el usuario porque tiene reservas asociadas'
            ];
        }

        if ($this->delete($id)) {
            return ['success' => true, 'message' => 'Usuario eliminado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar el usuario'];
    }
}