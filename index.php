<?php
// Démarre la session
session_start();

// Définit le répertoire racine
define('ROOT_DIR', __DIR__);

// Fonction d'autoloading des classes
function autoload($className) {
    $paths = [
        'controllers/',
        'models/',
        'utils/'
    ];
    
    foreach ($paths as $path) {
        $file = ROOT_DIR . '/' . $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}

// Enregistre la fonction d'autoload
spl_autoload_register('autoload');

// Routage simple
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'User';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Construit le nom de classe du contrôleur
$controllerClass = ucfirst($controller) . 'Controller';

// Vérifie si le contrôleur existe
if (file_exists(ROOT_DIR . '/controllers/' . $controllerClass . '.php')) {
    require_once ROOT_DIR . '/controllers/' . $controllerClass . '.php';
    
    // Instancie le contrôleur
    $controllerInstance = new $controllerClass();
    
    // Vérifie si l'action existe
    if (method_exists($controllerInstance, $action)) {
        // Appelle l'action
        $controllerInstance->$action();
    } else {
        // Action non trouvée
        header('Location: index.php');
    }
} else {
    // Contrôleur non trouvé
    header('Location: index.php');
}
?>
