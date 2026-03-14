<?php

use Kirby\Filesystem\Mime;
use Kirby\Http\Response;

return [
  'pattern' => 'kpn-sw.js',
  'method' => 'GET',
  'action' => function () {
    $root = dirname(__DIR__, 2) . '/assets/';
    $full = realpath($root . 'sw.js');
    $content = @file_get_contents($full);

    if ($content === false) {
      return false; // 404
    }

    $type = Mime::type($full) ?? 'application/octet-stream';
    $ext = pathinfo($full, PATHINFO_EXTENSION);
    if ($ext === 'js' || $ext === 'mjs') {
      $type = 'application/javascript';
    }
    return new Response($content, $type, 200, [
      'Cache-Control' => 'public, max-age=31536000'
    ]);
  },
];