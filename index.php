<?php
// Cargar configuración
require_once 'app/config/database.php';

// Helpers
require_once 'app/helpers/securityHelper.php';
require_once 'app/helpers/cookieHelper.php';
require_once 'app/helpers/authHelper.php';

// Controladores por defecto
$controller = $_GET['controller'] ?? 'Lugar';
$action = $_GET['action'] ?? 'index';

// Cargar y ejecutar controlador
$controllerFile = "app/controllers/{$controller}Controller.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controller . 'Controller';
    $ctrl = new $controllerClass();
    if (method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        echo "Acción no encontrada.";
    }
} else {
    echo "Controlador no encontrado.";
}
