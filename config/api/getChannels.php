<?php

return [
    'pattern' => 'philippoehrlein/kirby-push-notifications/get-channels',
    'method' => 'GET',
    'action' => function () {
        $channels = option('philippoehrlein.kirby-push-notifications.channels');

        if (empty($channels)) {
            return [
                'status' => 'error',
                'message' => t('philippoehrlein.kirby-push-notifications.api.get-channels.error.no_channels_configured'),
                'code' => 400,
            ];
        }
        
        return [
            'status' => 'success',
            'channels' => $channels,
        ];
    },
];