<?php
/**
 * La Clave del Marketing - Modelo User
 * 
 * Gestión de usuarios del sistema.
 */

require_once APP_PATH . '/Models/Model.php';

class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'email_verified_at',
        'remember_token',
        'last_login_at'
    ];

    /**
     * Buscar usuario por email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findWhere('email', $email);
    }

    /**
     * Crear usuario con contraseña encriptada
     */
    public function createUser(array $data): int
    {
        // Encriptar contraseña
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        return $this->create($data);
    }

    /**
     * Verificar contraseña
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Actualizar último login
     */
    public function updateLastLogin(int $userId): void
    {
        $sql = "UPDATE {$this->table} SET last_login_at = NOW() WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }

    /**
     * Verificar si email ya existe
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Obtener usuarios activos
     */
    public function getActiveUsers(): array
    {
        return $this->where('status', 'active');
    }

    /**
     * Contar proyectos del usuario
     */
    public function getProjectsCount(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count FROM projects WHERE user_id = ?";
        $result = $this->db->query($sql, [$userId])->fetch();
        return (int) $result['count'];
    }
}
