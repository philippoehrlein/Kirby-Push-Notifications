<?php

use Kirby\Cms\App;

return [
  'pattern' => 'philippoehrlein/kirby-push-notifications/unsubscribe',
  'method'  => 'POST',
  'action'  => function () {
    /** @var App $kirby */
    $kirby = kirby();

    $data = $kirby->request()->body()->toArray();

    $payload = [
      'endpoint' => $data['endpoint'] ?? null,
      'user_id'  => $data['user_id'] ?? null,
      'channel'  => $data['channel'] ?? null,
    ];

    $kirby->trigger('philippoehrlein.kirby-push-notifications.unsubscribe', compact('payload'));

    return [
      'status' => 'success',
    ];
  },
];
