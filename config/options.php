<?php

return [
  'db' => [
    'name' => 'push_notifications',
    'dir' => 'site/push-notifications',
  ],
  'vapid' => [
    'publicKey' => null,
    'privateKey' => null,
    'subject' => null,
  ],
  'channels' => [],
  'webPush' => [
    'contentType' => 'application/json',
    'TTL' => 3600,
    'urgency' => null,
    'topic' => null,
    'batchSize' => 1000,
    'requestConcurrency' => 100,
  ],
];