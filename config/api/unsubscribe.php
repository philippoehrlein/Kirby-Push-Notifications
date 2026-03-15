<?php

use Kirby\Cms\App;

return [
  'pattern' => 'philippoehrlein/push-notifications/unsubscribe',
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

    try {
      $kirby->trigger('philippoehrlein.push-notifications.unsubscribe', compact('payload'));
    } catch (\Throwable $e) {
      return [
        'status'  => 'error',
        'message' => $e->getMessage(),
      ];
    }

    return [
      'status' => 'success',
    ];
  },
];
