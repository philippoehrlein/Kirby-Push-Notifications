<?php

use Kirby\Exception\InvalidArgumentException;

/**
 * Unsubscribes a user from a push notification channel.
 *
 * @param array $payload
 * @return void
 */
return function (array $payload): void {
  $endpoint = $payload['endpoint'] ?? null;
  $userId = $payload['user_id'] ?? null;
  $channel = $payload['channel'] ?? null;

  $hasEndpoint = is_string($endpoint) && $endpoint !== '';
  $hasUserId = is_string($userId) && $userId !== '';

  if (!$hasEndpoint && !$hasUserId) {
    throw new InvalidArgumentException(t('philippoehrlein.push-notifications.hooks.error.missing_endpoint_or_user'));
  }

  $repo = new \KirbyPushNotifications\Repositories\SubscriptionsRepository();

  if ($hasEndpoint) {
    $repo->unsubscribeByEndpoint($endpoint);
    return;
  }

  $repo->unsubscribeByUser($userId, is_string($channel) && $channel !== '' ? $channel : null);
};
