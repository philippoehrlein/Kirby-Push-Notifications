<?php

/**
 * Sends a push notification to one user.
 * 
 * @param array $payload
 * @return void
 */
return function (array $payload): void {
  $userId = $payload['user_id'] ?? null;
  $message = $payload['message'] ?? [];
  $channel = $payload['channel'] ?? null;

  if (!is_string($userId) || $userId === '' || !is_array($message) || $message === []) {
    return;
  }

  $notifier = new \KirbyPushNotifications\Services\Notifier();
  $notifier->notifyUser(
    $userId,
    $message,
    is_string($channel) && $channel !== '' ? $channel : null
  );
};