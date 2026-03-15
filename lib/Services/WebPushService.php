<?php

namespace KirbyPushNotifications\Services;

use KirbyPushNotifications\Repositories\SubscriptionsRepository;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/**
 * Service for sending push notifications.
 * 
 * @author Philipp Oehrlein
 * @version 1.0.0
 */
class WebPushService
{
    private WebPush $webPush;
    private SubscriptionsRepository $subscriptions;

    public function __construct(?WebPush $webPush = null, ?SubscriptionsRepository $subscriptions = null)
    {
        $this->subscriptions = $subscriptions ?? new SubscriptionsRepository();
        $this->webPush = $webPush ?? $this->createWebPushFromOptions();
    }

    /**
     * Sends a message to all given subscriptions.
     *
     * @param list<array<string,mixed>> $subscriptions
     * @param array<string,mixed> $payload
     * @param array<string,mixed> $options
     */
    public function sendToSubscriptions(array $subscriptions, array $payload, array $options = []): void
    {
        if ($subscriptions === []) {
            return;
        }

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        foreach ($subscriptions as $row) {
            if (empty($row['endpoint']) || empty($row['keys_json'])) {
                continue;
            }

            $keys = json_decode((string) $row['keys_json'], true);
            if (!is_array($keys)) {
                continue;
            }

            $subscription = Subscription::create([
                'endpoint' => (string) $row['endpoint'],
                'keys' => $keys,
            ]);

            $this->webPush->queueNotification(
                $subscription,
                $jsonPayload,
                $options
            );
        }

        foreach ($this->webPush->flush() as $report) {
            $endpoint = $report->getEndpoint();

            if ($report->isSubscriptionExpired()) {
                $this->subscriptions->deleteExpiredByEndpoint($endpoint);
            }

            if (!$report->isSuccess()) {
                error_log(sprintf(
                    '[push-notifications] Push failed for %s: %s',
                    $endpoint,
                    $report->getReason()
                ));
            }
        }
    }

    /**
     * Creates a WebPush object from the plugin options.
     *
     * @return WebPush
     */
    private function createWebPushFromOptions(): WebPush
    {
        $options = option('philippoehrlein.push-notifications', []);
        $vapid = $options['vapid'] ?? [];

        $subject = $vapid['subject'] ?? null;
        $publicKey = $vapid['publicKey'] ?? null;
        $privateKey = $vapid['privateKey'] ?? null;

        if (empty($subject) || empty($publicKey) || empty($privateKey)) {
            throw new \RuntimeException('VAPID Configuration for push-notifications is incomplete (subject/publicKey/privateKey).');
        }

        $auth = [
            'VAPID' => [
                'subject' => $subject,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ];

        $defaultOptions = $options['webPush'];

        return new WebPush($auth, $defaultOptions);
    }
}

