<?php

namespace KirbyPushNotifications\Helpers;

use PDO;
use PDOException;

class DatabaseHelper
{
    private string $databasePath;
    private PDO $connection;

    public function __construct()
    {
        // Intentionally empty: connection is initialized lazily via getConnection()
    }

    /**
     * Singleton-like accessor for the helper instance.
     */
    public static function getInstance(): self
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Returns an active PDO connection and bootstraps the database on first use.
     */
    public function getConnection(): PDO
    {
        // Allow tests to inject a connection if needed
        if (defined('KIRBY_TESTING') && isset($GLOBALS['push_notifications_pdo_connection'])) {
            return $GLOBALS['push_notifications_pdo_connection'];
        }

        if (!isset($this->connection)) {
            $this->setupDatabase();
        }

        return $this->connection;
    }

    /**
     * Returns the absolute path to the SQLite database file.
     */
    public function getDatabasePath(): string
    {
        return $this->databasePath;
    }

    /**
     * Quick health check for the database connection.
     */
    public function isReady(): bool
    {
        try {
            $this->getConnection()->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Prepare directory, file and connection, then ensure required tables exist.
     */
    private function setupDatabase(): void
    {
        $dbDir = $this->storageDir();
        $dbName = $this->databaseName();

        $this->databasePath = $dbDir . '/' . $dbName . '.db';

        // Ensure directory exists
        if (!is_dir($dbDir)) {
            if (!mkdir($dbDir, 0755, true) && !is_dir($dbDir)) {
                throw new \RuntimeException('Could not create push notifications database directory: ' . $dbDir);
            }
        }

        // Create database file if needed
        if (!file_exists($this->databasePath)) {
            $this->createDatabase();
        }

        $this->connect();
        $this->ensureTablesExist();
    }

    /**
     * Determine the directory where the push notifications DB should live.
     * Default: site/push-notifications/db
     */
    private function storageDir(): string
    {
        $root = \kirby()->root('index'); // project root
        $default = $root . '/site/push-notifications/db';

        $configured = option('philippoehrlein.kirby-push-notifications.db.dir');

        return is_string($configured) && $configured !== '' ? $configured : $default;
    }

    /**
     * Determine the database file name.
     * Default: push_notifications.db
     */
    private function databaseName(): string
    {
        $configured = option('philippoehrlein.kirby-push-notifications.db.name');

        return is_string($configured) && $configured !== '' ? $configured : 'push_notifications.db';
    }

    /**
     * Create an empty SQLite database file.
     */
    private function createDatabase(): void
    {
        try {
            $pdo = new PDO('sqlite:' . $this->databasePath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo = null;
        } catch (PDOException $e) {
            throw new \RuntimeException('Could not create push notifications database: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Establish a PDO connection to the SQLite database.
     */
    private function connect(): void
    {
        try {
            $this->connection = new PDO('sqlite:' . $this->databasePath);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_TIMEOUT, 30);
            $this->connection->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $e) {
            throw new \RuntimeException('Could not connect to push notifications database: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Ensure all required tables for the plugin exist.
     */
    private function ensureTablesExist(): void
    {
        $this->createPushSubscriptionsDataTable();
    }

    /**
     * Create the push notifications data table.
     * Eine Zeile pro (endpoint, channel) – UNIQUE(endpoint, channel).
     */
    private function createPushSubscriptionsDataTable(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS push_subscriptions (
            id TEXT PRIMARY KEY,
            user_id TEXT NULL,
            channel TEXT NULL,
            endpoint TEXT NOT NULL,
            keys_json TEXT NOT NULL,
            created_at TEXT NOT NULL,
            updated_at TEXT NOT NULL,
            last_used_at TEXT NULL,
            UNIQUE(endpoint, channel)
        )';

        $this->connection->exec($sql);
        $this->connection->exec('CREATE INDEX IF NOT EXISTS idx_push_subscriptions_user_id ON push_subscriptions(user_id)');
        $this->connection->exec('CREATE INDEX IF NOT EXISTS idx_push_subscriptions_channel ON push_subscriptions(channel)');
        $this->connection->exec('CREATE INDEX IF NOT EXISTS idx_push_subscriptions_endpoint ON push_subscriptions(endpoint)');
    }
}

