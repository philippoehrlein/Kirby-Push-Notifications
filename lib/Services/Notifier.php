<?php

namespace KirbyPushNotifications\Services;

use KirbyPushNotifications\Repositories\SubscriptionsRepository;

/**
 * Service for sending notifications.
 * 
 * @author Philipp Oehrlein
 * @version 1.0.0
 */
class Notifier
{
    private SubscriptionsRepository $subscriptions;
    private WebPushService $webPush;

    public function __construct(?SubscriptionsRepository $subscriptions = null, ?WebPushService $webPush = null)
    {
        $this->subscriptions = $subscriptions ?? new SubscriptionsRepository();
        $this->webPush = $webPush ?? new WebPushService(null, $this->subscriptions);
    }

    /**
     * Sends a notification to a user (optional channel and language filtered).
     *
     * @param array<string,mixed> $message
     * @param array<string,mixed> $options
     * @param string|null $channel
     * @param string|null $lang Optional. When set, only subscriptions with this language or lang IS NULL receive the notification.
     */
    public function notifyUser(string $userId, array $message, array $options = [], ?string $channel = null, ?string $lang = null): void
    {
        $subs = $this->subscriptions->listByUser($userId, $channel, $lang);
        if ($subs === []) {
            return;
        }

        $this->webPush->sendToSubscriptions($subs, $message, $options);
    }

    /**
     * Sends a notification to multiple users.
     *
     * @param list<string> $userIds
     * @param array<string,mixed> $message
     * @param array<string,mixed> $options
     * @param string|null $channel
     * @param string|null $lang Optional. When set, only subscriptions with this language or lang IS NULL receive the notification.
     */
    public function notifyMany(array $userIds, array $message, array $options = [], ?string $channel = null, ?string $lang = null): void
    {
        foreach ($userIds as $userId) {
            $this->notifyUser($userId, $message, $options, $channel, $lang);
        }
    }

    /**
     * Sends a notification to all subscribers of a channel (e.g. anonymous visitors).
     *
     * @param array<string,mixed> $message
     * @param array<string,mixed> $options
     * @param string|null $lang Optional. When set, only subscriptions with this language or lang IS NULL receive the notification.
     */
    public function notifyByChannel(string $channel, array $message, array $options = [], ?string $lang = null): void
    {
        $subs = $this->subscriptions->listByChannel($channel, $lang);
        if ($subs === []) {
            return;
        }

        $this->webPush->sendToSubscriptions($subs, $message, $options);
    }
}

