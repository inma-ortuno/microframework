<?php
class FrontController {
    static function main() {
        // Incluimos clases necesarias
        require 'libs/Config.php';
        require 'libs/SPDO.php';
        require 'libs/View.php';
        require 'setup.php';

        // Recuperamos la instancia de configuración
        $config = Config::singleton();

        // Determinar controlador
        if (!empty($_REQUEST['controlador'])) {
            $controllerName = $_REQUEST['controlador'] . 'Controller';
        } else {
            $controllerName = "ItemAutoController"; // Controlador por defecto
        }

        // Determinar acción
        if (!empty($_REQUEST['accion'])) {
            $actionName = $_REQUEST['accion'];
        } else {
            $actionName = "listar"; // Acción por defecto
        }

        // Ruta del controlador
        $controllerPath = $config->get('controllersFolder') . $controllerName . '.php';

        if (!is_file($controllerPath)) {
            die('El controlador no existe - 404 not found');
        }

        require $controllerPath;

        // Ejecutar acción
        if (class_exists($controllerName) && method_exists($controllerName, $actionName)) {
            $controller = new $controllerName();
            $controller->$actionName();
        } else {
            trigger_error($controllerName . '->' . $actionName . ' no existe', E_USER_NOTICE);
            return false;
        }
    }
}
?>

