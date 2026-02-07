<?php
/**
 * La Clave del Marketing - Configuración Global
 * 
 * Archivo de configuración principal de la aplicación.
 */

// Modo de desarrollo
define('APP_DEBUG', true);

// Información de la aplicación
define('APP_NAME', 'La Clave del Marketing');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost:8000');

// Timezone
date_default_timezone_set('Europe/Madrid');

// Configuración de sesiones
define('SESSION_LIFETIME', 7200); // 2 horas
define('SESSION_NAME', 'lcdm_session');

// Rutas base
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', BASE_PATH . '/views');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Configuración de errores según modo
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// CSRF Token lifetime
define('CSRF_TOKEN_LIFETIME', 3600);
