<?php
/**
 * Clase Session
 * Maneja la sesión de usuarios y autenticación
 */

class Session {
    
    /**
     * Inicia la sesión si no está iniciada
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Configuración de seguridad para cookies de sesión
            session_set_cookie_params([
                'lifetime' => 0, // La sesión expira al cerrar el navegador
                'path' => '/',
                'domain' => '',
                'secure' => false, // Cambiar a true en producción con HTTPS
                'httponly' => true, // Previene acceso JavaScript
                'samesite' => 'Lax' // Protección CSRF
            ]);
            
            session_start();
        }
    }
    
    /**
     * Establece un valor en la sesión
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Obtiene un valor de la sesión
     * @param string $key
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Verifica si existe una clave en la sesión
     * @param string $key
     * @return bool
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Elimina un valor de la sesión
     * @param string $key
     */
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Destruye toda la sesión
     */
    public static function destroy() {
        self::start();
        session_destroy();
        $_SESSION = [];
    }
    
    /**
     * Inicia sesión de usuario
     * @param array $usuario Datos del usuario
     */
    public static function login($usuario) {
        self::start();
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['rol'] = $usuario['rol'];
        
        // Regenerar ID de sesión para prevenir session fixation
        session_regenerate_id(true);
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public static function logout() {
        self::destroy();
    }
    
    /**
     * Verifica si hay un usuario autenticado
     * @return bool
     */
    public static function isAuthenticated() {
        self::start();
        return isset($_SESSION['usuario_id']);
    }
    
    /**
     * Obtiene el ID del usuario actual
     * @return int|null
     */
    public static function getUserId() {
        return self::get('usuario_id');
    }
    
    /**
     * Obtiene el rol del usuario actual
     * @return string|null
     */
    public static function getUserRole() {
        return self::get('rol');
    }
    
    /**
     * Verifica si el usuario es administrador
     * @return bool
     */
    public static function isAdmin() {
        return self::get('rol') === 'admin';
    }
    
    /**
     * Obtiene los datos del usuario actual
     * @return array|null
     */
    public static function getUser() {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => self::get('usuario_id'),
            'nombre' => self::get('nombre_completo'),
            'email' => self::get('email'),
            'rol' => self::get('rol')
        ];
    }
    
    /**
     * Establece un mensaje flash
     * @param string $mensaje
     * @param string $tipo success|error|warning|info
     */
    public static function setFlash($mensaje, $tipo = 'info') {
        self::start();
        $_SESSION['flash_message'] = [
            'texto' => $mensaje,
            'tipo' => $tipo
        ];
    }
    
    /**
     * Obtiene y elimina el mensaje flash
     * @return array|null
     */
    public static function getFlash() {
        self::start();
        if (isset($_SESSION['flash_message'])) {
            $mensaje = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $mensaje;
        }
        return null;
    }
    
    /**
     * Verifica si existe un mensaje flash
     * @return bool
     */
    public static function hasFlash() {
        self::start();
        return isset($_SESSION['flash_message']);
    }
    
    /**
     * Guarda la URL anterior para redirección
     * @param string $url
     */
    public static function setPreviousUrl($url) {
        self::set('previous_url', $url);
    }
    
    /**
     * Obtiene la URL anterior
     * @return string|null
     */
    public static function getPreviousUrl() {
        return self::get('previous_url');
    }
    
    /**
     * Establece un token CSRF
     * @return string
     */
    public static function setCsrfToken() {
        $token = bin2hex(random_bytes(32));
        self::set('csrf_token', $token);
        return $token;
    }
    
    /**
     * Obtiene el token CSRF
     * @return string|null
     */
    public static function getCsrfToken() {
        return self::get('csrf_token');
    }
    
    /**
     * Verifica el token CSRF
     * @param string $token
     * @return bool
     */
    public static function verifyCsrfToken($token) {
        $sessionToken = self::getCsrfToken();
        return $sessionToken && hash_equals($sessionToken, $token);
    }
}