<?php
/**
 * La Clave del Marketing - Modelo Project
 * 
 * Gestión de proyectos de usuario.
 */

require_once APP_PATH . '/Models/Model.php';

class Project extends Model
{
    protected string $table = 'projects';
    protected array $fillable = [
        'user_id',
        'name',
        'description',
        'niche',
        'target_audience',
        'status',
        'settings'
    ];

    /**
     * Estados de proyecto
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Obtener proyectos de un usuario
     */
    public function getByUser(int $userId, string $status = null): array
    {
        if ($status) {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND status = ? ORDER BY updated_at DESC";
            return $this->db->query($sql, [$userId, $status])->fetchAll();
        }

        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY updated_at DESC";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    /**
     * Buscar proyecto por ID verificando propiedad
     */
    public function findByUserAndId(int $userId, int $projectId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND user_id = ?";
        $result = $this->db->query($sql, [$projectId, $userId])->fetch();
        return $result ?: null;
    }

    /**
     * Contar proyectos de un usuario
     */
    public function countByUser(int $userId): int
    {
        return $this->countWhere('user_id', $userId);
    }

    /**
     * Contar proyectos activos de un usuario
     */
    public function countActiveByUser(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ? AND status = 'active'";
        $result = $this->db->query($sql, [$userId])->fetch();
        return (int) $result['count'];
    }

    /**
     * Crear proyecto para un usuario
     */
    public function createForUser(int $userId, array $data): int
    {
        $data['user_id'] = $userId;
        return $this->create($data);
    }

    /**
     * Actualizar proyecto verificando propiedad
     */
    public function updateForUser(int $userId, int $projectId, array $data): bool
    {
        // Verificar que el proyecto pertenece al usuario
        $project = $this->findByUserAndId($userId, $projectId);
        if (!$project) {
            return false;
        }

        return $this->update($projectId, $data);
    }

    /**
     * Eliminar proyecto verificando propiedad
     */
    public function deleteForUser(int $userId, int $projectId): bool
    {
        // Verificar que el proyecto pertenece al usuario
        $project = $this->findByUserAndId($userId, $projectId);
        if (!$project) {
            return false;
        }

        return $this->delete($projectId);
    }

    /**
     * Obtener nichos únicos usados por el usuario
     */
    public function getUserNiches(int $userId): array
    {
        $sql = "SELECT DISTINCT niche FROM {$this->table} WHERE user_id = ? AND niche IS NOT NULL AND niche != ''";
        $results = $this->db->query($sql, [$userId])->fetchAll();
        return array_column($results, 'niche');
    }

    /**
     * Obtener estadísticas de proyectos del usuario
     */
    public function getStats(int $userId): array
    {
        $sql = "SELECT 
                    status, 
                    COUNT(*) as count 
                FROM {$this->table} 
                WHERE user_id = ? 
                GROUP BY status";

        $results = $this->db->query($sql, [$userId])->fetchAll();

        $stats = [
            'total' => 0,
            'draft' => 0,
            'active' => 0,
            'paused' => 0,
            'completed' => 0,
            'archived' => 0
        ];

        foreach ($results as $row) {
            $stats[$row['status']] = (int) $row['count'];
            $stats['total'] += (int) $row['count'];
        }

        return $stats;
    }

    /**
     * Buscar proyectos por nombre
     */
    public function searchByName(int $userId, string $query): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = ? AND name LIKE ? 
                ORDER BY updated_at DESC";
        return $this->db->query($sql, [$userId, "%{$query}%"])->fetchAll();
    }
}
