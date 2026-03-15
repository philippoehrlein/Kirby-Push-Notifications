<?php

use Kirby\Cms\App;

return [
    'pattern' => 'philippoehrlein/push-notifications/subscribe',
    'method'  => 'POST',
    'action'  => function () {
        /** @var App $kirby */
        $kirby = kirby();

        $data = $kirby->request()->body()->toArray();
        $user = $kirby->user();
        $lang = $user->language() ?? null;

        $payload = [
            'endpoint' => $data['endpoint'] ?? null,
            'keys'     => $data['keys'] ?? null,
            'user_id'  => $data['user_id'] ?? ($user !== null ? $user->id() : null),
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

