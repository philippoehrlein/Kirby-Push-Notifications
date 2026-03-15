<?php

return [
  'pattern' => 'push-notifications/subscribe',
  'method' => 'POST',
  'action' => function () {
    /** @var App $kirby */
    $kirby = kirby();

    $data = $kirby->request()->body()->toArray();
    $lang = param('lang') ?? null;

    $user = $kirby->user();
    // Security: On the public route, never trust user_id from the request body (could be forged).
    // Only associate a user if they are actually logged in.
    $payload = [
        'endpoint' => $data['endpoint'] ?? null,
        'keys'     => $data['keys'] ?? null,
        'user_id'  => $user !== null ? $user->id() : null,
        'channel'  => $data['channel'] ?? null,
        'lang'     => $lang,
    ];

    try {
        $kirby->trigger('philippoehrlein.push-notifications.subscribe', compact('payload'));
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