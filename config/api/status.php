<?php

use Kirby\Cms\App;
use KirbyPushNotifications\Repositories\SubscriptionsRepository;

return [
  'pattern' => 'philippoehrlein/kirby-push-notifications/status',
  'method' => 'GET',
  'action' => function () {
    /** @var App $kirby */
    $kirby = kirby();

    $user = $kirby->user();
    if ($user === null) {
      return [
        'status' => 'error',
        'subscribed' => false,
        'message' => 'User not authenticated',
        'code' => 401,
      ];
    }

    $query = $kirby->request()->query()->toArray();
    $endpoint = $query['endpoint'] ?? null;
    $channel = $query['channel'] ?? null;

    if (!is_string($endpoint) || $endpoint === '') {
      return [
        'status' => 'success',
        'subscribed' => false,
      ];
    }

    $repo = new SubscriptionsRepository();
    $row = $repo->findByEndpointAndChannel($endpoint, is_string($channel) && $channel !== '' ? $channel : null);

    $isSubscribed = $row !== null && $row['user_id'] === $user->id();

    return [
      'status' => 'success',
      'subscribed' => $isSubscribed,
    ];
  },
];

