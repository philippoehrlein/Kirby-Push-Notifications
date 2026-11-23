<?php

declare(strict_types=1);

use Kirby\Cms\App;
use KirbyPushNotifications\Helpers\DatabaseHelper;
use KirbyPushNotifications\Repositories\SubscriptionsRepository;
use KirbyPushNotifications\Services\Notifier;
use KirbyPushNotifications\Services\WebPushService;

require dirname(__DIR__) . '/playground/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/autoload.php';

function expectSame(mixed $expected, mixed $actual): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(sprintf(
            'Expected %s, got %s',
            var_export($expected, true),
            var_export($actual, true)
        ));
    }
}

function testSubscriptions(SubscriptionsRepository $subscriptions): void
{
    $endpoint = 'https://push.example.test/browser';
    $subscriptions->subscribe($endpoint, ['auth' => 'old'], 'notes', userId: 'editor');
    $original = $subscriptions->findByEndpointAndChannel($endpoint, 'notes');
    $subscriptions->subscribe($endpoint, ['auth' => 'new'], 'notes', userId: 'editor');
    $subscriptions->subscribe($endpoint, ['auth' => 'other'], 'dev-alerts', userId: 'editor');

    $notes = $subscriptions->listByChannel('notes');
    expectSame(1, count($notes));
    expectSame($original['id'], $notes[0]['id']);
    expectSame(['auth' => 'new'], json_decode($notes[0]['keys_json'], true, flags: JSON_THROW_ON_ERROR));

    $subscriptions->unsubscribeByUser('editor', 'notes');
    expectSame(null, $subscriptions->findByEndpointAndChannel($endpoint, 'notes'));
    expectSame($endpoint, $subscriptions->findByEndpointAndChannel($endpoint, 'dev-alerts')['endpoint']);
}

function testRecipients(SubscriptionsRepository $subscriptions): void
{
    $matching = 'https://push.example.test/matching';
    $unlocalized = 'https://push.example.test/unlocalized';
    $subscriptions->subscribe($matching, [], 'notes', 'de');
    $subscriptions->subscribe($unlocalized, [], 'notes');
    $subscriptions->subscribe('https://push.example.test/other-language', [], 'notes', 'en');
    $subscriptions->subscribe('https://push.example.test/other-channel', [], 'dev-alerts', 'de');

    $transport = new class extends WebPushService {
        public array $calls = [];

        public function __construct()
        {
        }

        public function sendToSubscriptions(array $subscriptions, array $payload, array $options = []): void
        {
            $endpoints = array_column($subscriptions, 'endpoint');
            sort($endpoints);
            $this->calls[] = [$endpoints, $payload, $options];
        }
    };

    $notifier = new Notifier($subscriptions, $transport);
    $payload = ['title' => 'New note'];
    $options = ['TTL' => 60];
    $notifier->notifyByChannel('notes', $payload, $options, 'de');
    $notifier->notifyByChannel('empty', $payload);

    expectSame([[[$matching, $unlocalized], $payload, $options]], $transport->calls);
}

foreach ([
    'SQLite subscriptions are updated and removed per channel' => 'testSubscriptions',
    'Notifications reach only subscribers matching the channel and language' => 'testRecipients',
] as $name => $test) {
    $directory = sys_get_temp_dir() . '/kpn-tests-' . bin2hex(random_bytes(8));
    new App([
        'roots' => ['index' => $directory],
        'options' => [
            'philippoehrlein.push-notifications.db.dir' => $directory,
            'philippoehrlein.push-notifications.db.name' => 'subscriptions',
        ],
    ]);

    try {
        $database = new DatabaseHelper();
        $test(new SubscriptionsRepository($database->getConnection()));
        echo "PASS $name\n";
    } finally {
        unset($database);
        if (is_file($directory . '/subscriptions.db')) {
            unlink($directory . '/subscriptions.db');
        }
        if (is_dir($directory)) {
            rmdir($directory);
        }
    }
}
