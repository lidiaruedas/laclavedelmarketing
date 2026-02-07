<?php
/**
 * La Clave del Marketing - Modelo Base
 * 
 * Clase base abstracta para todos los modelos.
 */

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todos los registros
     */
    public function all(array $columns = ['*']): array
    {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Buscar por ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Buscar por condición
     */
    public function where(string $column, $value, string $operator = '='): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        return $this->db->query($sql, [$value])->fetchAll();
    }

    /**
     * Buscar un registro por condición
     */
    public function findWhere(string $column, $value): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        $result = $this->db->query($sql, [$value])->fetch();
        return $result ?: null;
    }

    /**
     * Crear nuevo registro
     */
    public function create(array $data): int
    {
        // Filtrar solo campos permitidos
        $data = array_intersect_key($data, array_flip($this->fillable));

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql, array_values($data));

        return (int) $this->db->lastInsertId();
    }

    /**
     * Actualizar registro
     */
    public function update(int $id, array $data): bool
    {
        // Filtrar solo campos permitidos
        $data = array_intersect_key($data, array_flip($this->fillable));

        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = ?";
        }
        $setString = implode(', ', $sets);

        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = ?";
        $params = array_merge(array_values($data), [$id]);

        $this->db->query($sql, $params);
        return true;
    }

    /**
     * Eliminar registro
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    /**
     * Contar registros
     */
    public function count(string $column = '*'): int
    {
        $sql = "SELECT COUNT({$column}) as count FROM {$this->table}";
        $result = $this->db->query($sql)->fetch();
        return (int) $result['count'];
    }

    /**
     * Contar registros con condición
     */
    public function countWhere(string $column, $value): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} = ?";
        $result = $this->db->query($sql, [$value])->fetch();
        return (int) $result['count'];
    }

    /**
     * Obtener último registro insertado
     */
    public function latest(): ?array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT 1";
        $result = $this->db->query($sql)->fetch();
        return $result ?: null;
    }

    /**
     * Ejecutar query SQL personalizado
     */
    protected function raw(string $sql, array $params = []): array
    {
        return $this->db->query($sql, $params)->fetchAll();
    }
}
