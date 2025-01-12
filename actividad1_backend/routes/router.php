<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar las rutas
$routes = require '../routes/routes.php';

// Obtener la ruta solicitada desde el parámetro 'path'
$requestPath = $_GET['path'] ?? ''; // Tomar el valor del parámetro 'path' si existe
$requestPath = trim($requestPath, '/'); // Limpiar los slashes al inicio y al final

// Verificar si la ruta existe en la configuración
if (isset($routes[$requestPath])) {
    $route = $routes[$requestPath];
    $controllerName = $route['controller'];
    $methodName = $route['method'];

    // Incluir el archivo del controlador
    $controllerPath = "../controllers/{$controllerName}.php";
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
    } else {
        die("El archivo del controlador '{$controllerPath}' no existe.");
    }

    // Crear una instancia del controlador y llamar al método
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
        // Pasar parámetros opcionales si se envían
        $id = $_GET['id'] ?? null;
        $controller->$methodName($id);
    } else {
        die("El método '{$methodName}' no existe en el controlador '{$controllerName}'.");
    }
} else {
    // Si la ruta no existe, mostrar un error 404
    http_response_code(404);
    die("Página no encontrada.");
}
