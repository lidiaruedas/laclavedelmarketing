<?php
/**
 * La Clave del Marketing - Configuración de Base de Datos
 * 
 * Clase Singleton para conexión PDO a MySQL.
 */

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    // Configuración de base de datos
    private string $host = 'localhost';
    private string $database = 'laclavedelmarketing';
    private string $username = 'root';
    private string $password = '';
    private string $charset = 'utf8mb4';

    /**
     * Constructor privado para patrón Singleton
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Obtener instancia única de Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establecer conexión PDO
     */
    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                die("Error de conexión: " . $e->getMessage());
            } else {
                die("Error de conexión a la base de datos.");
            }
        }
    }

    /**
     * Obtener conexión PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Ejecutar query con prepared statement
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtener último ID insertado
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Revertir transacción
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * Prevenir clonación
     */
    private function __clone()
    {
    }

    /**
     * Prevenir deserialización
     */
    public function __wakeup()
    {
        throw new Exception("No se puede deserializar singleton");
    }
}
