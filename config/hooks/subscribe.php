<?php

/**
 * Subscribes a user to a push notification channel.
 * 
 * @param array $payload
 * @return void
 */
return function (array $payload): void {
  $endpoint = $payload['endpoint'] ?? null;
  $keys = $payload['keys'] ?? null;

  if (!is_string($endpoint) || $endpoint === '' || !is_array($keys) || $keys === []) {
    error_log('Invalid payload: ' . print_r($payload, true));
    return;
  }

  $userId = isset($payload['user_id']) && is_string($payload['user_id']) && $payload['user_id'] !== ''
    ? $payload['user_id']
    : null;

  $channel = isset($payload['channel']) && is_string($payload['channel']) && $payload['channel'] !== ''
    ? $payload['channel']
    : null;

  $repo = new \KirbyPushNotifications\Repositories\SubscriptionsRepository();
  $repo->subscribe($endpoint, $keys, $userId, $channel);
};