<?php

use Kirby\Http\Response;

return [
    'pattern' => 'assets/kpn/helper.js',
    'method' => 'GET',
    'action' => function () {
        $root = dirname(__DIR__, 2) . '/assets/';
        $full = realpath($root . 'helper.js');
        if ($full === false || !is_file($full)) {
            return false;
        }
        $content = @file_get_contents($full);
        if ($content === false) {
            return false;
        }
        return new Response($content, 'application/javascript', 200, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    },
];