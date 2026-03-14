<?php

return [
  'philippoehrlein.kirby-push-notifications.subscribe' => require __DIR__ . '/hooks/subscribe.php',
  'philippoehrlein.kirby-push-notifications.unsubscribe' => require __DIR__ . '/hooks/unsubscribe.php',
  'philippoehrlein.kirby-push-notifications.send-to-one' => require __DIR__ . '/hooks/send-to-one.php',
  'philippoehrlein.kirby-push-notifications.send-to-many' => require __DIR__ . '/hooks/send-to-many.php',
];