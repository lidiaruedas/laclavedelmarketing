<?php
/**
 * La Clave del Marketing - Front Controller
 * 
 * Punto de entrada único para todas las peticiones.
 */

// Iniciar sesión
session_name('lcdm_session');
session_start();

// Regenerar ID de sesión periódicamente para seguridad
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutos
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Cargar configuración
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Cargar helpers
require_once APP_PATH . '/Helpers/functions.php';

// Cargar router
require_once APP_PATH . '/Router.php';

// Cargar modelos base
require_once APP_PATH . '/Models/Model.php';

// Crear instancia del router
$router = new Router();

// Cargar definición de rutas
require_once BASE_PATH . '/routes/web.php';

// Manejar la petición
try {
    $router->dispatch();
} catch (Exception $e) {
    if (APP_DEBUG) {
        echo '<div style="background:#dc2626;color:white;padding:20px;margin:20px;border-radius:8px;">';
        echo '<h2>Error</h2>';
        echo '<p>' . e($e->getMessage()) . '</p>';
        echo '<pre>' . e($e->getTraceAsString()) . '</pre>';
        echo '</div>';
    } else {
        http_response_code(500);
        echo '<h1>Error del servidor</h1>';
        echo '<p>Ha ocurrido un error. Por favor, inténtalo más tarde.</p>';
    }
}

// Limpiar errores de validación después de mostrar
clearValidation();
