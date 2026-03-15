<?php

use Kirby\Exception\InvalidArgumentException;
use KirbyPushNotifications\Services\Notifier;

/**
 * Sends a push notification to many recipients.
 * Either by user IDs (logged-in users) or by channel (all subscribers, e.g. anonymous visitors).
 *
 * Payload:
 * - message: array (required) – push message
 * - user_ids: list<string> (optional) – send to these users (optionally filtered by channel)
 * - channel: string (optional) – if set with user_ids: filter subscriptions by channel; if set without user_ids: send to all channel subscribers
 * - options: array (optional) – additional options for the notification (ttl, urgency, topic, etc.)
 * - language: string (optional) – filter recipients by subscription language (only subscriptions with this lang or lang IS NULL)
 *
 * @param array $payload
 * @return void
 */
return function (array $payload): void {
  $userIds = $payload['user_ids'] ?? [];
  $message = $payload['message'] ?? [];
  $channel = $payload['channel'] ?? null;
  $options = $payload['options'] ?? [];
  $lang = $payload['language'] ?? null;

  if (!is_array($message) || $message === []) {
    throw new InvalidArgumentException(t('philippoehrlein.push-notifications.hooks.error.message_required'));
  }

  $channelStr = is_string($channel) && $channel !== '' ? $channel : null;
  $langStr = is_string($lang) && $lang !== '' ? $lang : null;

  $userIds = is_array($userIds)
    ? array_values(array_filter($userIds, static function ($id) {
        return is_string($id) && $id !== '';
      }))
    : [];

  $notifier = new Notifier();

  if ($userIds !== []) {
    $notifier->notifyMany($userIds, $message, $options, $channelStr, $langStr);
    return;
  }

  if ($channelStr !== null) {
    $notifier->notifyByChannel($channelStr, $message, $options, $langStr);
    return;
  }

  throw new InvalidArgumentException(t('philippoehrlein.push-notifications.hooks.error.user_ids_or_channel_required'));
};
