<?php

use KirbyPushNotifications\Repositories\SubscriptionsRepository;
use Kirby\Uuid\Uuid;
use Kirby\Toolkit\Str;

return [
    'pattern' => 'philippoehrlein/push-notifications/send-notification',
    'load' => function () {
        $uuid = get('uuid');
        $url = '';
        
        if (Str::startsWith($uuid, 'page://')) {
            $url = $uuid;
        } else  {
            $url = site()->homePage()->uuid()->toString();
        }

        $channels = option('philippoehrlein.push-notifications.channels');
        $channelsMerged = array_merge($channels['panel'] ?? [], $channels['website'] ?? []);
        $channelOptions = array_map(function ($channel) {
            return [
                'value' => $channel['value'],
                'text' => $channel['text'],
            ];
        }, $channelsMerged);

        $subscriptionsRepo = new SubscriptionsRepository();
        $languages = $subscriptionsRepo->getLanguages();

        $value = [
            'url' => $url ?? '',
        ];

        $titleField = [
            'type' => 'text',
            'label' => t('philippoehrlein.push-notifications.panel.send-notification.title.label'),
            'required' => true,
            'maxlength' => 40,
        ];

        $channelField = [
            'type' => 'select',
            'label' => t('philippoehrlein.push-notifications.panel.send-notification.channel.label'),
            'required' => true,
            'options' => $channelOptions,
            'width' => $languages !== [] ? '1/2' : '1/1',
        ];

        $languageField = null;
        if($languages !== []) {
            $languageOptions = array_map(function ($language) {
                return [
                    'value' => $language,
                    'text' => $language,
                ];
            }, $languages);

            $languageField = [
                'type' => 'select',
                'label' => t('philippoehrlein.push-notifications.panel.send-notification.language.label'),
                'width' => '1/2',
                'options' => $languageOptions,
            ];
        }

        $bodyField = [
            'type' => 'textarea',
            'label' => t('philippoehrlein.push-notifications.panel.send-notification.body.label'),
            'required' => true,
            'buttons' => false,
            'maxlength' => 140,
        ];

        $urlField = [
            'type' => 'link',
            'label' => t('philippoehrlein.push-notifications.panel.send-notification.url.label'),
            'options' => [
                'page'
            ],
        ];

        $fields = [
            'title' => $titleField,
            'channel' => $channelField,
            'language' => $languageField,
            'body' => $bodyField,
            'url' => $urlField,
        ];


        return [
            'component' => 'k-form-dialog',
            'props' => [    
                'fields' => $fields,
                'value' => $value,
                'size' => 'large',
                'submitButton' => [
                    'text' => t('philippoehrlein.push-notifications.panel.send-notification.submit.label'),
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
        $url = $data['url'] ? Uuid::for($data['url'])->toUrl() : site()->url();
        $language = $data['language'] ?? null;

        $payload = [
            'message' => [
                'title' => $title,
                'body' => $body,
                'data' => [
                    'url' => $url,
                ],
            ],
            'channel' => $channel,
            'language' => $language,
        ];

        kirby()->trigger('philippoehrlein.push-notifications.send-to-many', ['payload' => $payload]);

        return [
            'status' => 'success',
        ];
    },
];