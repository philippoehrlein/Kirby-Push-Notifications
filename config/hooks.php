<?php

return [
  'philippoehrlein.push-notifications.subscribe' => require __DIR__ . '/hooks/subscribe.php',
  'philippoehrlein.push-notifications.unsubscribe' => require __DIR__ . '/hooks/unsubscribe.php',
  'philippoehrlein.push-notifications.send-to-one' => require __DIR__ . '/hooks/send-to-one.php',
  'philippoehrlein.push-notifications.send-to-many' => require __DIR__ . '/hooks/send-to-many.php',
];