<?php
require_once __DIR__ . '/config/classloader.php';
require_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App as Kirby;
use KirbyPushNotifications\Helpers\TranslationHelper;
use KirbyPushNotifications\Helpers\PathHelper;

$plugin = [
  'api' => require __DIR__ . '/config/api/index.php',
  'hooks' => require __DIR__ . '/config/hooks.php',
  'options' => require __DIR__ . '/config/options.php',
  'routes' => require __DIR__ . '/config/routes.php',
  'translations' => TranslationHelper::loadTranslations(PathHelper::translationDir()),
  'version' => '1.0.0',
];

Kirby::plugin('philippoehrlein/kirby-push-notifications', $plugin);