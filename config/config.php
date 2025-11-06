<?php
/**
 * Archivo de configuración general del sistema
 * Sistema de Turismo en Coroico
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de zona horaria
date_default_timezone_set('America/La_Paz');

// Configuración de errores (cambiar a 0 en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// URL base del proyecto
// Ajustar según la estructura de tu servidor local
define('BASE_URL', 'http://localhost:81/turismo-coroico/');

// Nombre del sitio
define('SITE_NAME', 'Turismo Coroico');

// Descripción del sitio
define('SITE_DESCRIPTION', 'Descubre los mejores destinos turísticos de Coroico, La Paz - Bolivia');

// Directorio de uploads
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('UPLOAD_URL', BASE_URL . 'public/uploads/');

// Tamaño máximo de archivo (5MB)
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

// Extensiones de archivo permitidas para imágenes
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Número de registros por página
define('RECORDS_PER_PAGE', 10);

// Roles de usuario
define('ROL_ADMIN', 'admin');
define('ROL_TURISTA', 'turista');

// Estados de reserva (para futuras implementaciones)
define('RESERVA_PENDIENTE', 'pendiente');
define('RESERVA_CONFIRMADA', 'confirmada');
define('RESERVA_CANCELADA', 'cancelada');

// Categorías de lugares turísticos
define('CATEGORIAS_LUGARES', [
    'mirador' => 'Mirador',
    'cascada' => 'Cascada',
    'aventura' => 'Aventura',
    'cultural' => 'Cultural'
]);

// Configuración de cookies
define('COOKIE_LIFETIME', 30 * 24 * 60 * 60); // 30 días

/**
 * Función auxiliar para construir URLs
 * @param string $path Ruta relativa
 * @return string URL completa
 */
function url($path = '') {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Función auxiliar para redireccionar
 * @param string $path Ruta a redireccionar
 */
function redirect($path = '') {
    header('Location: ' . url($path));
    exit();
}

/**
 * Función auxiliar para verificar si el usuario está logueado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['usuario_id']);
}

/**
 * Función auxiliar para verificar si el usuario es admin
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === ROL_ADMIN;
}

/**
 * Función auxiliar para obtener el usuario actual
 * @return array|null
 */
function getUsuarioActual() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'nombre' => $_SESSION['nombre_completo'],
            'email' => $_SESSION['email'],
            'rol' => $_SESSION['rol']
        ];
    }
    return null;
}

/**
 * Función auxiliar para sanitizar entrada
 * @param string $data
 * @return string
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Función auxiliar para formatear fecha
 * @param string $date Fecha en formato Y-m-d
 * @return string Fecha formateada
 */
function formatearFecha($date) {
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    
    $timestamp = strtotime($date);
    $dia = date('d', $timestamp);
    $mes = $meses[(int)date('m', $timestamp)];
    $anio = date('Y', $timestamp);
    
    return "$dia de $mes de $anio";
}

/**
 * Función auxiliar para formatear precio
 * @param float $precio
 * @return string
 */
function formatearPrecio($precio) {
    return 'Bs. ' . number_format($precio, 2, ',', '.');
}

/**
 * Función para mostrar mensajes flash
 * @param string $key Clave del mensaje
 * @return string|null
 */
function getFlashMessage($key = 'mensaje') {
    if (isset($_SESSION[$key])) {
        $mensaje = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $mensaje;
    }
    return null;
}

/**
 * Función para establecer mensajes flash
 * @param string $mensaje
 * @param string $tipo (success, error, warning, info)
 * @param string $key
 */
function setFlashMessage($mensaje, $tipo = 'info', $key = 'mensaje') {
    $_SESSION[$key] = [
        'texto' => $mensaje,
        'tipo' => $tipo
    ];
}