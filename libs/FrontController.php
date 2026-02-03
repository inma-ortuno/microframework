<?php
class FrontController {
    static function main() {
        require 'libs/Config.php';
        require 'libs/SPDO.php';
        require 'libs/View.php';
        require 'setup.php';

        // Hacemos visible la variable $config creada en setup.php
        global $config;

        // 1. Obtener la ruta solicitada
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 2. Eliminar el directorio base si existe
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath !== '' && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        // 3. Normalizar
        $requestUri = trim($requestUri, '/');

        // 4. Determinar controlador y acción
        if ($requestUri === '') {
            $controllerName = "ItemController";
            $actionName = "listar";
        } else {
            $parts = explode('/', $requestUri);
            $controllerName = ucfirst(array_shift($parts)) . 'Controller';
            $actionName = $parts ? array_shift($parts) : 'listar';
        }

        // 5. Cargar controlador
        $controllerPath = $config->get('controllersFolder') . $controllerName . '.php';

        if (!file_exists($controllerPath)) {
            http_response_code(404);
            die("Controlador no encontrado: $controllerName");
        }

        require $controllerPath;

        // 6. Ejecutar acción
        if (!class_exists($controllerName) || !method_exists($controllerName, $actionName)) {
            http_response_code(404);
            die("No existe la acción $actionName en $controllerName");
        }

        $controller = new $controllerName();
        $controller->$actionName();
    }
}
?>
