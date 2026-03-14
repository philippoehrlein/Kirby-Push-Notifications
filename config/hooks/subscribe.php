<?php

use Kirby\Exception\InvalidArgumentException;

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
    throw new InvalidArgumentException(t('philippoehrlein.kirby-push-notifications.hooks.error.invalid_payload'));
  }

  $userId = isset($payload['user_id']) && is_string($payload['user_id']) && $payload['user_id'] !== ''
    ? $payload['user_id']
    : null;

  $channel = isset($payload['channel']) && is_string($payload['channel']) && $payload['channel'] !== ''
    ? $payload['channel']
    : null;

  $repo = new \KirbyPushNotifications\Repositories\SubscriptionsRepository();
  $repo->subscribe($endpoint, $keys, $channel, $userId);
};
