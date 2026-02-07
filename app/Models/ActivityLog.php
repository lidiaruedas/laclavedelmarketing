<?php
/**
 * La Clave del Marketing - Modelo ActivityLog
 * 
 * Registro de actividad del sistema.
 */

require_once APP_PATH . '/Models/Model.php';

class ActivityLog extends Model
{
    protected string $table = 'activity_logs';
    protected array $fillable = [
        'user_id',
        'project_id',
        'action',
        'entity_type',
        'entity_id',
        'details',
        'ip_address',
        'user_agent'
    ];

    /**
     * Acciones comunes
     */
    public const ACTION_LOGIN = 'user.login';
    public const ACTION_LOGOUT = 'user.logout';
    public const ACTION_REGISTER = 'user.register';
    public const ACTION_PROJECT_CREATE = 'project.create';
    public const ACTION_PROJECT_UPDATE = 'project.update';
    public const ACTION_PROJECT_DELETE = 'project.delete';
    public const ACTION_PROJECT_VIEW = 'project.view';

    /**
     * Registrar actividad
     */
    public function log(
        string $action,
        ?int $userId = null,
        ?int $projectId = null,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $details = null
    ): int {
        $data = [
            'user_id' => $userId ?? authId(),
            'project_id' => $projectId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details ? json_encode($details) : null,
            'ip_address' => getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];

        return $this->create($data);
    }

    /**
     * Obtener actividad de un usuario
     */
    public function getByUser(int $userId, int $limit = 50): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?";
        return $this->db->query($sql, [$userId, $limit])->fetchAll();
    }

    /**
     * Obtener actividad de un proyecto
     */
    public function getByProject(int $projectId, int $limit = 50): array
    {
        $sql = "SELECT al.*, u.name as user_name 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.project_id = ? 
                ORDER BY al.created_at DESC 
                LIMIT ?";
        return $this->db->query($sql, [$projectId, $limit])->fetchAll();
    }

    /**
     * Obtener actividad reciente global (para admin)
     */
    public function getRecent(int $limit = 100): array
    {
        $sql = "SELECT al.*, u.name as user_name, p.name as project_name
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                LEFT JOIN projects p ON al.project_id = p.id
                ORDER BY al.created_at DESC 
                LIMIT ?";
        return $this->db->query($sql, [$limit])->fetchAll();
    }

    /**
     * Limpiar logs antiguos
     */
    public function cleanOldLogs(int $days = 90): int
    {
        $sql = "DELETE FROM {$this->table} WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = $this->db->query($sql, [$days]);
        return $stmt->rowCount();
    }
}
