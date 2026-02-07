<?php
/**
 * La Clave del Marketing - Funciones Helper
 * 
 * Funciones de utilidad globales para la aplicación.
 */

/**
 * Renderizar una vista
 */
function view(string $view, array $data = [], ?string $layout = 'app'): void
{
    // Extraer datos para que estén disponibles en la vista
    extract($data);

    // Construir ruta del archivo de vista
    $viewPath = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($viewPath)) {
        throw new Exception("Vista no encontrada: {$view}");
    }

    // Capturar contenido de la vista
    ob_start();
    require $viewPath;
    $content = ob_get_clean();

    // Si hay layout, renderizarlo con el contenido
    if ($layout !== null) {
        $layoutPath = VIEW_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    } else {
        echo $content;
    }
}

/**
 * Redireccionar a una URL
 */
function redirect(string $url): void
{
    header("Location: " . APP_URL . $url);
    exit;
}

/**
 * Obtener URL completa
 */
function url(string $path = ''): string
{
    return APP_URL . $path;
}

/**
 * Obtener URL de asset (CSS, JS, imágenes)
 */
function asset(string $path): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Escapar HTML para prevenir XSS
 */
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Obtener datos de sesión flash
 */
function flash(string $key, $default = null)
{
    if (isset($_SESSION['flash'][$key])) {
        $value = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $value;
    }
    return $default;
}

/**
 * Establecer datos de sesión flash
 */
function setFlash(string $key, $value): void
{
    $_SESSION['flash'][$key] = $value;
}

/**
 * Verificar si hay mensaje flash
 */
function hasFlash(string $key): bool
{
    return isset($_SESSION['flash'][$key]);
}

/**
 * Generar token CSRF
 */
function csrfToken(): string
{
    if (
        !isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) ||
        (time() - $_SESSION['csrf_token_time']) > CSRF_TOKEN_LIFETIME
    ) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generar campo hidden con token CSRF
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Verificar token CSRF
 */
function verifyCsrf(?string $token): bool
{
    return isset($_SESSION['csrf_token']) &&
        $token !== null &&
        hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Obtener usuario autenticado
 */
function auth(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Verificar si el usuario está autenticado
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Obtener ID del usuario autenticado
 */
function authId(): ?int
{
    return $_SESSION['user']['id'] ?? null;
}

/**
 * Obtener errores de validación antiguos
 */
function old(string $key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

/**
 * Obtener error de validación específico
 */
function error(string $key): ?string
{
    return $_SESSION['errors'][$key] ?? null;
}

/**
 * Verificar si hay error para un campo
 */
function hasError(string $key): bool
{
    return isset($_SESSION['errors'][$key]);
}

/**
 * Limpiar errores y datos antiguos
 */
function clearValidation(): void
{
    unset($_SESSION['errors']);
    unset($_SESSION['old']);
}

/**
 * Establecer errores de validación
 */
function setErrors(array $errors): void
{
    $_SESSION['errors'] = $errors;
}

/**
 * Establecer datos antiguos
 */
function setOld(array $data): void
{
    $_SESSION['old'] = $data;
}

/**
 * Obtener IP del cliente
 */
function getClientIp(): string
{
    $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = explode(',', $_SERVER[$key])[0];
            if (filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                return trim($ip);
            }
        }
    }

    return '0.0.0.0';
}

/**
 * Formatear fecha
 */
function formatDate(?string $date, string $format = 'd/m/Y H:i'): string
{
    if (!$date)
        return '-';
    return date($format, strtotime($date));
}

/**
 * Generar slug a partir de texto
 */
function slugify(string $text): string
{
    // Reemplazar caracteres no ASCII
    $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    // Eliminar caracteres no alfanuméricos
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    // Eliminar guiones duplicados y al inicio/final
    return trim(preg_replace('/-+/', '-', $text), '-');
}

/**
 * Validar email
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Debug variable (solo en modo desarrollo)
 */
function dd(...$vars): void
{
    if (APP_DEBUG) {
        echo '<pre style="background:#1a1a2e;color:#eee;padding:20px;margin:20px;border-radius:8px;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }
}
