<?php

namespace KirbyPushNotifications\Services;

use KirbyPushNotifications\Repositories\SubscriptionsRepository;

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
     * Sendet eine Benachrichtigung an einen User (optional kanalgefiltert).
     *
     * @param array<string,mixed> $message
     */
    public function notifyUser(string $userId, array $message, ?string $channel = null): void
    {
        $subs = $this->subscriptions->listByUser($userId, $channel);
        if ($subs === []) {
            return;
        }

        $this->webPush->sendToSubscriptions($subs, $message);
    }

    /**
     * Sendet eine Benachrichtigung an mehrere User.
     *
     * @param list<string> $userIds
     * @param array<string,mixed> $message
     */
    public function notifyMany(array $userIds, array $message, ?string $channel = null): void
    {
        foreach ($userIds as $userId) {
            $this->notifyUser($userId, $message, $channel);
        }
    }
}

