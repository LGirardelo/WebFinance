<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';

$app = new \Slim\App($settings);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Autentication 
require __DIR__ . '/../src/autentication/autentication.php';

// Modulos do sistema
require __DIR__ . '/../src/modulos/usuarios.php';
require __DIR__ . '/../src/modulos/grupoFamiliar.php';
require __DIR__ . '/../src/modulos/tipoConta.php';
require __DIR__ . '/../src/modulos/conta.php';
require __DIR__ . '/../src/modulos/lancamento.php';

// Register routes
require __DIR__ . '/../src/routes.php';

$app->run();
