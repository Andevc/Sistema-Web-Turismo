<?php
/**
 * Front Controller - Punto de entrada único de la aplicación
 * Sistema de Turismo en Coroico
 */

// Cargar configuraciones
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Obtener la URL solicitada
$request_uri = $_SERVER['REQUEST_URI'];

// Remover la base URL si existe
$base_path = parse_url(BASE_URL, PHP_URL_PATH);
if ($base_path && strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// Remover query string
$request_uri = strtok($request_uri, '?');

// Remover slash inicial y final
$request_uri = trim($request_uri, '/');

// Si está vacío, es la página de inicio
if (empty($request_uri)) {
    $request_uri = 'inicio/index';
}

// Separar la URL en segmentos
$segments = explode('/', $request_uri);

// Obtener controlador y método
$controllerName = ucfirst($segments[0]) . 'Controller';
$methodName = isset($segments[1]) ? $segments[1] : 'index';

// Convertir método de kebab-case a camelCase (ej: mis-reservas -> misReservas)
$methodName = lcfirst(str_replace('-', '', ucwords($methodName, '-')));

// Mapeo de rutas especiales
$routeMap = [
    'inicio' => 'Inicio',
    'usuario' => 'Usuario',
    'lugares' => 'Lugar',
    'tours' => 'Tour',
    'reservas' => 'Reserva',
    'comentarios' => 'Comentario',
    'admin' => 'Admin'
];

// Ajustar nombre del controlador si está en el mapeo
if (isset($routeMap[$segments[0]])) {
    $controllerName = $routeMap[$segments[0]] . 'Controller';
}

// Ruta del archivo del controlador
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

// Verificar si el controlador existe
if (!file_exists($controllerFile)) {
    // Página 404
    header("HTTP/1.0 404 Not Found");
    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>404 - Página no encontrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #2d5016 0%, #4a9d5f 100%);
            color: white;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        h1 { font-size: 6rem; margin: 0; }
        h2 { font-size: 2rem; margin: 1rem 0; }
        a {
            display: inline-block;
            margin-top: 2rem;
            padding: 1rem 2rem;
            background: white;
            color: #2d5016;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a:hover { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class='error-container'>
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>Lo sentimos, la página que buscas no existe.</p>
        <a href='" . BASE_URL . "'>Volver al Inicio</a>
    </div>
</body>
</html>";
    exit;
}

// Incluir el controlador
require_once $controllerFile;

// Verificar si la clase existe
if (!class_exists($controllerName)) {
    die("Error: La clase del controlador '$controllerName' no existe.");
}

// Crear instancia del controlador
$controller = new $controllerName();

// Verificar si el método existe
if (!method_exists($controller, $methodName)) {
    die("Error: El método '$methodName' no existe en el controlador '$controllerName'.");
}

// Ejecutar el método del controlador
try {
    $controller->$methodName();
} catch (Exception $e) {
    // Manejo de errores
    error_log("Error en el controlador: " . $e->getMessage());
    
    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 { color: #d32f2f; }
        a {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background: #2d5016;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover { background: #4a9d5f; }
    </style>
</head>
<body>
    <div class='error-container'>
        <h1>Error en la aplicación</h1>
        <p>Ha ocurrido un error inesperado. Por favor, intenta nuevamente.</p>
        <a href='" . BASE_URL . "'>Volver al Inicio</a>
    </div>
</body>
</html>";
}