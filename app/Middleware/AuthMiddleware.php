<?php
/**
 * La Clave del Marketing - Middleware de Autenticación
 * 
 * Protege rutas que requieren usuario autenticado.
 */

class AuthMiddleware
{
    /**
     * Manejar la verificación de autenticación
     */
    public function handle(): bool
    {
        // Verificar si el usuario está autenticado
        if (!isAuthenticated()) {
            // Guardar URL de destino para redirección después del login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];

            // Mensaje flash
            setFlash('warning', 'Debes iniciar sesión para acceder a esta página.');

            // Redirigir a login
            redirect('/login');
            return false;
        }

        // Verificar si la sesión no ha expirado
        if (isset($_SESSION['login_time'])) {
            $sessionAge = time() - $_SESSION['login_time'];
            if ($sessionAge > SESSION_LIFETIME) {
                // Sesión expirada
                $this->logout();
                setFlash('warning', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                redirect('/login');
                return false;
            }
        }

        // Refrescar tiempo de sesión
        $_SESSION['last_activity'] = time();

        return true;
    }

    /**
     * Cerrar sesión
     */
    private function logout(): void
    {
        // Limpiar datos de sesión del usuario
        unset($_SESSION['user']);
        unset($_SESSION['login_time']);
        unset($_SESSION['last_activity']);
    }
}
