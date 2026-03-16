<?php

return [
    'pattern' => 'philippoehrlein/push-notifications/get-channels',
    'method' => 'GET',
    'action' => function () {
        $channels = option('philippoehrlein.push-notifications.channels') ?? [];
        $computedChannels = [];

        if (is_array($channels) && (isset($channels['panel']) || isset($channels['website']))) {
            $computedChannels['panel'] = $channels['panel'] ?? [];
            $computedChannels['website'] = $channels['website'] ?? [];
        } else {
            $computedChannels = $channels;
        }

        if (empty($computedChannels)) {
            return [
                'status' => 'error',
                'message' => t('philippoehrlein.push-notifications.api.get-channels.error.no_channels_configured'),
                'code' => 400,
            ];
        }

        return [
            'status' => 'success',
            'channels' => $computedChannels,
        ];
    },
];