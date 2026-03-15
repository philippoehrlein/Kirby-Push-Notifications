<?php
require_once __DIR__ . '/config/classloader.php';
$autoloader = __DIR__ . '/vendor/autoload.php';
if(file_exists($autoloader)) {
  require_once $autoloader;
}

use Kirby\Cms\App as Kirby;
use KirbyPushNotifications\Helpers\TranslationHelper;
use KirbyPushNotifications\Helpers\PathHelper;

$plugin = [
  'areas' => require __DIR__ . '/config/areas.php',
  'api' => require __DIR__ . '/config/api/index.php',
  'hooks' => require __DIR__ . '/config/hooks.php',
  'options' => require __DIR__ . '/config/options.php',
  'routes' => require __DIR__ . '/config/routes.php',
  'snippets' => require __DIR__ . '/config/snippets.php',
  'translations' => TranslationHelper::loadTranslations(PathHelper::translationDir()),
  'version' => '1.0.0',
];

Kirby::plugin('philippoehrlein/push-notifications', $plugin);