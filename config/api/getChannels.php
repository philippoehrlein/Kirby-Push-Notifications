<?php

return [
    'pattern' => 'philippoehrlein/push-notifications/get-channels',
    'method' => 'GET',
    'action' => function () {
        $channels = option('philippoehrlein.push-notifications.channels');

        if (empty($channels)) {
            return [
                'status' => 'error',
                'message' => t('philippoehrlein.push-notifications.api.get-channels.error.no_channels_configured'),
                'code' => 400,
            ];
        }
        
        return [
            'status' => 'success',
            'channels' => $channels,
        ];
    },
];