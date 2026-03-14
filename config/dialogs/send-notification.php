<?php

return [
    'pattern' => 'philippoehrlein/kirby-push-notifications/send-notification',
    'load' => function () {

        $channels = option('philippoehrlein.kirby-push-notifications.channels');
        $channelOptions = array_map(function ($channel) {
            return [
                'value' => $channel['value'],
                'text' => $channel['text'],
            ];
        }, $channels);

        $fields = [
            'title' => [
                'type' => 'text',
                'label' => t('philippoehrlein.kirby-push-notifications.panel.send-notification.title.label'),
                'required' => true,
                'maxlength' => 40,
            ],
            'channel' => [
                'type' => 'select',
                'label' => t('philippoehrlein.kirby-push-notifications.panel.send-notification.channel.label'),
                'required' => true,
                'options' => $channelOptions,
            ],
            'body' => [
                'type' => 'textarea',
                'label' => t('philippoehrlein.kirby-push-notifications.panel.send-notification.body.label'),
                'required' => true,
                'buttons' => false,
                'maxlength' => 140,
            ]
        ];


        return [
            'component' => 'k-form-dialog',
            'props' => [    
                'fields' => $fields,
                'submitButton' => [
                    'text' => t('philippoehrlein.kirby-push-notifications.panel.send-notification.submit.label'),
                    'icon' => 'kpn-send',
                    'theme' => 'info'
                ],
            ],
        ];
    },
    'submit' => function () {
        $data = get();

        $title = $data['title'];
        $channel = $data['channel'];
        $body = $data['body'];

        $payload = [
            'message' => [
                'title' => $title,
                'body' => $body,
                'data' => [
                    'url' => site()->url(),
                ],
            ],
            'channel' => $channel,
        ];

        kirby()->trigger('philippoehrlein.kirby-push-notifications.send-to-many', ['payload' => $payload]);

        return [
            'status' => 'success',
        ];
    },
];