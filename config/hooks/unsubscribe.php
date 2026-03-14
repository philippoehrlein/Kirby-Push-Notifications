<?php

/**
 * Unsubscribes a user from a push notification channel.
 * 
 * @param array $payload
 * @return void
 */
return function (array $payload): void {
  $repo = new \KirbyPushNotifications\Repositories\SubscriptionsRepository();

  $endpoint = $payload['endpoint'] ?? null;
  $userId = $payload['user_id'] ?? null;
  $channel = $payload['channel'] ?? null;

  if (is_string($endpoint) && $endpoint !== '') {
    $repo->unsubscribeByEndpoint($endpoint);
    return;
  }

  if (is_string($userId) && $userId !== '') {
    $repo->unsubscribeByUser($userId, is_string($channel) && $channel !== '' ? $channel : null);
  }
};