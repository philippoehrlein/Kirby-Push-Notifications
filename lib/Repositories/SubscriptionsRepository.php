<?php

namespace KirbyPushNotifications\Repositories;

use Kirby\Uuid\Uuid;
use KirbyPushNotifications\Helpers\DatabaseHelper;
use PDO;

/**
 * Repository for push subscriptions.
 * 
 * @author Philipp Oehrlein
 * @version 1.0.0
 */
class SubscriptionsRepository
{
    private PDO $db;

    public function __construct(?PDO $connection = null)
    {
        $this->db = $connection ?? DatabaseHelper::getInstance()->getConnection();
    }

    /**
     * Subscribes a user to a push notification channel.
     * Channel is required – subscriptions without a channel are not allowed.
     *
     * @param string $endpoint
     * @param array $keys
     * @param string $channel required, non-empty
     * @param string|null $userId
     * @return void
     */
    public function subscribe(string $endpoint, array $keys, string $channel, ?string $lang = null, ?string $userId = null): void
    {
        if ($channel === '') {
            throw new \InvalidArgumentException('Channel is required for subscription.');
        }

        $now = date('c');
        $keysJson = json_encode($keys, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $existing = $this->findByEndpointAndChannel($endpoint, $channel);

        if ($existing === null) {
            $id = Uuid::generate();
            $sql = 'INSERT INTO push_subscriptions (
                id, user_id, channel, lang, endpoint, keys_json, created_at, updated_at, last_used_at
            ) VALUES (
                :id, :user_id, :channel, :lang, :endpoint, :keys_json, :created_at, :updated_at, NULL
            )';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId,
                ':channel' => $channel,
                ':lang' => $lang,
                ':endpoint' => $endpoint,
                ':keys_json' => $keysJson,
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } else {
            $sql = 'UPDATE push_subscriptions
                    SET user_id = :user_id,
                        lang = :lang,
                        keys_json = :keys_json,
                        updated_at = :updated_at
                    WHERE endpoint = :endpoint AND channel = :channel';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':lang' => $lang,
                ':keys_json' => $keysJson,
                ':updated_at' => $now,
                ':endpoint' => $endpoint,
                ':channel' => $channel,
            ]);
        }
    }

    /**
     * Unsubscribes a user from a push notification channel by endpoint.
     *
     * @param string $endpoint
     * @return void
     */
    public function unsubscribeByEndpoint(string $endpoint): void
    {
        $stmt = $this->db->prepare('DELETE FROM push_subscriptions WHERE endpoint = :endpoint');
        $stmt->execute([':endpoint' => $endpoint]);
    }

    /**
     * Unsubscribes a user from a push notification channel by user ID.
     *
     * @param string $userId
     * @param string|null $channel
     * @return void
     */
    public function unsubscribeByUser(string $userId, ?string $channel = null): void
    {
        if ($channel === null || $channel === '') {
            $sql = 'DELETE FROM push_subscriptions WHERE user_id = :user_id';
            $params = [':user_id' => $userId];
        } else {
            $sql = 'DELETE FROM push_subscriptions WHERE user_id = :user_id AND channel = :channel';
            $params = [
                ':user_id' => $userId,
                ':channel' => $channel,
            ];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * @param string|null $lang Optional. When set, only subscriptions with this language or lang IS NULL are returned.
     * @return list<array{
     *   id: string,
     *   user_id: ?string,
     *   channel: ?string,
     *   endpoint: string,
     *   keys_json: string,
     *   created_at: string,
     *   updated_at: string,
     *   last_used_at: string|null
     * }>
     */
    public function listByUser(string $userId, ?string $channel = null, ?string $lang = null): array
    {
        if ($channel === null || $channel === '') {
            $sql = 'SELECT * FROM push_subscriptions
                    WHERE user_id = :user_id';
            $params = [':user_id' => $userId];
        } else {
            $sql = 'SELECT * FROM push_subscriptions
                    WHERE user_id = :user_id AND channel = :channel';
            $params = [
                ':user_id' => $userId,
                ':channel' => $channel,
            ];
        }

        if ($lang !== null && $lang !== '') {
            $sql .= ' AND (lang IS NULL OR lang = :lang)';
            $params[':lang'] = $lang;
        }

        $sql .= ' ORDER BY created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(
            static fn(array $row): array => [
                'id' => (string) $row['id'],
                'user_id' => $row['user_id'] !== null ? (string) $row['user_id'] : null,
                'channel' => $row['channel'] !== null ? (string) $row['channel'] : null,
                'lang' => $row['lang'] !== null ? (string) $row['lang'] : null,
                'endpoint' => (string) $row['endpoint'],
                'keys_json' => (string) $row['keys_json'],
                'created_at' => (string) $row['created_at'],
                'updated_at' => (string) $row['updated_at'],
                'last_used_at' => $row['last_used_at'] !== null ? (string) $row['last_used_at'] : null,
            ],
            $rows
        );
    }

    /**
     * Lists all subscriptions for a channel (e.g. for anonymous visitors).
     *
     * @param string|null $lang Optional. When set, only subscriptions with this language or lang IS NULL are returned.
     * @return list<array{
     *   id: string,
     *   user_id: ?string,
     *   channel: ?string,
     *   endpoint: string,
     *   keys_json: string,
     *   created_at: string,
     *   updated_at: string,
     *   last_used_at: string|null
     * }>
     */
    public function listByChannel(string $channel, ?string $lang = null): array
    {
        if ($channel === '') {
            return [];
        }

        $sql = 'SELECT * FROM push_subscriptions WHERE channel = :channel';
        $params = [':channel' => $channel];

        if ($lang !== null && $lang !== '') {
            $sql .= ' AND (lang IS NULL OR lang = :lang)';
            $params[':lang'] = $lang;
        }

        $sql .= ' ORDER BY created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(
            static fn(array $row): array => [
                'id' => (string) $row['id'],
                'user_id' => $row['user_id'] !== null ? (string) $row['user_id'] : null,
                'lang' => $row['lang'] !== null ? (string) $row['lang'] : null,
                'channel' => $row['channel'] !== null ? (string) $row['channel'] : null,
                'endpoint' => (string) $row['endpoint'],
                'keys_json' => (string) $row['keys_json'],
                'created_at' => (string) $row['created_at'],
                'updated_at' => (string) $row['updated_at'],
                'last_used_at' => $row['last_used_at'] !== null ? (string) $row['last_used_at'] : null,
            ],
            $rows
        );
    }

    /**
     * Checks if a user is subscribed to a channel.
     *
     * @param string $userId
     * @param string|null $channel
     * @return bool
     */
    public function isSubscribed(string $userId, ?string $channel = null): bool
    {
        return $this->listByUser($userId, $channel) !== [];
    }

    public function deleteExpiredByEndpoint(string $endpoint): void
    {
        $this->unsubscribeByEndpoint($endpoint);
    }

    /**
     * @return array<string,mixed>|null
     */
    public function findByEndpoint(string $endpoint): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM push_subscriptions WHERE endpoint = :endpoint LIMIT 1');
        $stmt->execute([':endpoint' => $endpoint]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapRow($row);
    }

    /**
     * Finds a subscription by endpoint and channel.
     * Channel is required – subscriptions without a channel are not allowed.
     *
     * @param string $endpoint
     * @param string $channel
     *
     * @return array<string,mixed>|null
     */
    public function findByEndpointAndChannel(string $endpoint, string $channel): ?array
    {
        if ($channel === '') {
            return null;
        }

        $stmt = $this->db->prepare('SELECT * FROM push_subscriptions WHERE endpoint = :endpoint AND channel = :channel LIMIT 1');
        $stmt->execute([':endpoint' => $endpoint, ':channel' => $channel]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapRow($row);
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string,mixed>
     */
    private function mapRow(array $row): array
    {
        return [
            'id' => (string) $row['id'],
            'user_id' => $row['user_id'] !== null ? (string) $row['user_id'] : null,
            'channel' => $row['channel'] !== null ? (string) $row['channel'] : null,
            'lang' => $row['lang'] !== null ? (string) $row['lang'] : null,
            'endpoint' => (string) $row['endpoint'],
            'keys_json' => (string) $row['keys_json'],
            'created_at' => (string) $row['created_at'],
            'updated_at' => (string) $row['updated_at'],
            'last_used_at' => $row['last_used_at'] !== null ? (string) $row['last_used_at'] : null,
        ];
    }

    /**
     * Returns all languages with subscriptions.
     *
     * @return list<string>
     */
    public function getLanguages(): array
    {
        $stmt = $this->db->prepare('SELECT DISTINCT lang FROM push_subscriptions WHERE lang IS NOT NULL');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return array_map(static fn(array $row): string => (string) $row['lang'], $rows);
    }
}

