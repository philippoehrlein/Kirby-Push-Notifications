<?php

use Kirby\Exception\InvalidArgumentException;

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
  $options = $payload['options'] ?? [];
  $lang = $payload['language'] ?? null;

  if (!is_string($userId) || $userId === '' || !is_array($message) || $message === []) {
    throw new InvalidArgumentException(t('philippoehrlein.push-notifications.hooks.error.invalid_payload'));
  }

  $channelStr = is_string($channel) && $channel !== '' ? $channel : null;
  $langStr = is_string($lang) && $lang !== '' ? $lang : null;

  $notifier = new \KirbyPushNotifications\Services\Notifier();
  $notifier->notifyUser(
    $userId,
    $message,
    $options,
    $channelStr,
    $langStr
  );
};
