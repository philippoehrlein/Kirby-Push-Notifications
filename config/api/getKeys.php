<?php

return [
  'pattern' => 'philippoehrlein/kirby-push-notifications/get-keys',
  'method' => 'GET',
  'action' => function () {
    $publicKey = option('philippoehrlein.kirby-push-notifications.vapid.publicKey');
    $subject = option('philippoehrlein.kirby-push-notifications.vapid.subject');
    if (empty($publicKey)) {
      return [
        'status' => 'error',
        'message' => 'VAPID-Configuration is missing',
        'code' => 400,
      ];
    }

    return [
      'status' => 'success',
      'keys' => [
        'publicKey' => $publicKey,
        'subject' => $subject ?? null,
      ],
    ];
  },
];