<?php

use KirbyPushNotifications\Repositories\SubscriptionsRepository;
use Kirby\Uuid\Uuid;
use Kirby\Toolkit\Str;

return [
    'pattern' => 'philippoehrlein/push-notifications/send-notification',
    'load' => function () {
        $uuid = get('uuid');
        $url = '';
        
        $subscriptionsRepo = new SubscriptionsRepository();
        $hasSubscriptions = $subscriptionsRepo->hasSubscriptions();

        if (!$hasSubscriptions) {
            return [
                'component' => 'k-text-dialog',
                'props' => [
                    'text' => t('philippoehrlein.push-notifications.panel.send-notification.no-subscriptions.text'),
                    'theme' => 'info',
                    'cancelButton' => false
                ],
            ];
        }
        
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

        $languages = $subscriptionsRepo->getLanguages();
        $userLanguage = kirby()->user()->language() ?? 'en';

        $showLanguageSelect = count($languages) > 1;
        $showChannelSelect = count($channelOptions) > 1;
        $selectWidth = $showLanguageSelect && $showChannelSelect ? '1/2' : '1/1';

        $value = [
            'url' => $url ?? '',
        ];

        $titleField = [
            'type' => 'text',
            'label' => t('philippoehrlein.push-notifications.panel.send-notification.title.label'),
            'required' => true,
            'maxlength' => 40,
        ];

        if($showChannelSelect) {
            $channelField = [
                'type' => 'select',
                'label' => t('philippoehrlein.push-notifications.panel.send-notification.channel.label'),
                'required' => true,
                'options' => $channelOptions,
                'width' => $selectWidth,
            ];
        } else {
            $channelField = [
                'type' => 'hidden',
                'value' => $channelOptions[0]['value'],
            ];
        }

        if($showLanguageSelect) {
            
            $languageOptions = array_map(function ($language) use ($userLanguage) {
                return [
                    'value' => $language,
                    'text' => locale_get_display_language($language, $userLanguage),
                ];
            }, $languages);

            $languageField = [
                'type' => 'select',
                'label' => t('philippoehrlein.push-notifications.panel.send-notification.language.label'),
                'width' => $selectWidth,
                'options' => $languageOptions,
            ];
        } else {
            $languageField = [
                'type' => 'hidden',
                'value' => $languages[0],
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
            'url' => $urlField
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

        if($data === null || $data === []) {
            return true;
        }

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