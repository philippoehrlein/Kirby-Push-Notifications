<?php

return [
  'pattern' => 'push-notifications/unsubscribe',
  'method' => 'POST',
  'action' => function () {
    $kirby = kirby();
    $data = $kirby->request()->body()->toArray();
    $payload = [
      'endpoint' => $data['endpoint'] ?? null,
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
