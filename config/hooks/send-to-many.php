<?php

/**
 * Sends a push notification to many users.
 * 
 * @param array $payload
 * @return void
 */
return function (array $payload): void {
  $userIds = $payload['user_ids'] ?? [];
  $message = $payload['message'] ?? [];
  $channel = $payload['channel'] ?? null;

  if (!is_array($userIds) || $userIds === [] || !is_array($message) || $message === []) {
    return;
  }

  // Nur String-IDs verwenden
  $userIds = array_values(array_filter($userIds, static function ($id) {
    return is_string($id) && $id !== '';
  }));

  if ($userIds === []) {
    return;
  }

  $notifier = new \KirbyPushNotifications\Services\Notifier();
  $notifier->notifyMany(
    $userIds,
    $message,
    is_string($channel) && $channel !== '' ? $channel : null
  );
};