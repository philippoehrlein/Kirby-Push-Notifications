<?php

use Kirby\Cms\App;

return [
    'pattern' => 'philippoehrlein/kirby-push-notifications/subscribe',
    'method'  => 'POST',
    'action'  => function () {
        /** @var App $kirby */
        $kirby = kirby();

        $data = $kirby->request()->body()->toArray();

        $user = $kirby->user();
        $payload = [
            'endpoint' => $data['endpoint'] ?? null,
            'keys'     => $data['keys'] ?? null,
            'user_id'  => $data['user_id'] ?? ($user !== null ? $user->id() : null),
            'channel'  => $data['channel'] ?? null,
        ];

        $kirby->trigger('philippoehrlein.kirby-push-notifications.subscribe', compact('payload'));

        return [
            'status' => 'success',
        ];
    },
];

