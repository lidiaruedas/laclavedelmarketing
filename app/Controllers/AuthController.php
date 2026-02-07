<?php
/**
 * La Clave del Marketing - Controlador de Autenticación
 * 
 * Gestiona login, registro y logout de usuarios.
 */

require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Models/ActivityLog.php';

class AuthController
{
    private User $userModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->userModel = new User();
        $this->activityLog = new ActivityLog();
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin(): void
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isAuthenticated()) {
            redirect('/dashboard');
        }

        view('auth.login', [
            'title' => 'Iniciar Sesión'
        ], 'auth');
    }

    /**
     * Procesar login
     */
    public function login(): void
    {
        // Verificar CSRF
        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            setFlash('error', 'Token de seguridad inválido. Por favor, intenta de nuevo.');
            redirect('/login');
        }

        // Obtener datos
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validación básica
        $errors = [];

        if (empty($email)) {
            $errors['email'] = 'El email es obligatorio.';
        }

        if (empty($password)) {
            $errors['password'] = 'La contraseña es obligatoria.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld(['email' => $email]);
            redirect('/login');
        }

        // Buscar usuario
        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            setFlash('error', 'Credenciales incorrectas.');
            setOld(['email' => $email]);
            redirect('/login');
        }

        // Verificar estado del usuario
        if ($user['status'] !== 'active') {
            setFlash('error', 'Tu cuenta no está activa. Contacta con soporte.');
            redirect('/login');
        }

        // Crear sesión
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();

        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);

        // Actualizar último login
        $this->userModel->updateLastLogin($user['id']);

        // Log de actividad
        $this->activityLog->log(ActivityLog::ACTION_LOGIN, $user['id']);

        // Redirigir a URL original o dashboard
        $redirectUrl = $_SESSION['intended_url'] ?? '/dashboard';
        unset($_SESSION['intended_url']);

        setFlash('success', '¡Bienvenido de nuevo, ' . e($user['name']) . '!');
        redirect($redirectUrl);
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister(): void
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isAuthenticated()) {
            redirect('/dashboard');
        }

        view('auth.register', [
            'title' => 'Crear Cuenta'
        ], 'auth');
    }

    /**
     * Procesar registro
     */
    public function register(): void
    {
        // Verificar CSRF
        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            setFlash('error', 'Token de seguridad inválido. Por favor, intenta de nuevo.');
            redirect('/register');
        }

        // Obtener datos
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validación
        $errors = [];
        $old = ['name' => $name, 'email' => $email];

        if (empty($name)) {
            $errors['name'] = 'El nombre es obligatorio.';
        } elseif (strlen($name) < 2) {
            $errors['name'] = 'El nombre debe tener al menos 2 caracteres.';
        }

        if (empty($email)) {
            $errors['email'] = 'El email es obligatorio.';
        } elseif (!isValidEmail($email)) {
            $errors['email'] = 'El formato del email no es válido.';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Este email ya está registrado.';
        }

        if (empty($password)) {
            $errors['password'] = 'La contraseña es obligatoria.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Las contraseñas no coinciden.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($old);
            redirect('/register');
        }

        // Crear usuario
        try {
            $userId = $this->userModel->createUser([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user',
                'status' => 'active'
            ]);

            // Log de actividad
            $this->activityLog->log(ActivityLog::ACTION_REGISTER, $userId);

            setFlash('success', '¡Cuenta creada con éxito! Ahora puedes iniciar sesión.');
            redirect('/login');

        } catch (Exception $e) {
            setFlash('error', 'Error al crear la cuenta. Por favor, intenta de nuevo.');
            setOld($old);
            redirect('/register');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        $userId = authId();

        // Log de actividad antes de destruir sesión
        if ($userId) {
            $this->activityLog->log(ActivityLog::ACTION_LOGOUT, $userId);
        }

        // Destruir sesión completamente
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        // Iniciar nueva sesión para mensaje flash
        session_start();
        setFlash('success', 'Has cerrado sesión correctamente.');
        redirect('/login');
    }
}
