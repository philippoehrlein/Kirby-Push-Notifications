<?php

use Kirby\Toolkit\Str;

return [
  'debug' => false,
  'cache' => [
    // enables a file cache at `site/cache/kpn-exceptions`
    'kpn-exceptions' => true,
  ],
  'content' => [
    'locking' => false
  ],
  'panel' => [
    'vue' => [
      'compiler' => false
    ],
    'viewButtons' => [
      'page' => [
        /**
         * Example:
         * Add a subscribe button to the notes page for approvers.
         * See hooks for more details.
         * https://github.com/philippoehrlein/Kirby-Push-Notifications/tree/main/examples/note-approval-workflow
         */
        'subscribe-notes-approve' => function ($kirby, $site, $user, $page) {
          if (!$user || !$page) return null;
          if ($page->intendedTemplate()->name() !== 'notes') return null;

          $approverIds = $page->approver()->toUsers()->pluck('id');
          if (!in_array($user->id(), $approverIds, true)) return null;

          return [
            'component' => 'kpn-subscribe-button',
            'props' => [
              'icon'     => 'check',
              'theme'    => 'blue',
              'text'     => 'Subscribe',
              'title'    => 'Subscribe to Note Approval',
              'channels' => [
                [
                  'value' => 'note-approval',
                  'text'  => 'Note Approval',
                  'info'  => 'Get notified when a note is waiting for approval',
                ],
              ],
            ],
          ];
        },
      ],
    ],
  ],
  'philippoehrlein.push-notifications' => [
    /**
     * VAPID keys for the push notifications
     * Don't push this keys in production!
     * To generate new keys, use the following command: https://github.com/web-push-libs/web-push-php?tab=readme-ov-file#create-vapid-keys
     */
    'vapid' => [
      'publicKey' => 'BGX9qo1fxnyR2qbzfu9UlpP2U48jc117mCIL00orMedxgwmqi607oUbpcZYnrB_kw9jWKXWmcmK_uqLw5sPrkYQ',
      'privateKey' => 'CFxLUg3CjviWTAR339SbW_ZTHUOge5qgrlrPFdY3KJk',
      /** check if your browser supports localhost as secure origin for testing purposes */
      'subject' => 'http://localhost:8000',
    ],
    'channels' => [
      'panel' => [
        [
          'value' => 'note-approval',
          'text' => 'Note Approval',
          'info' => 'Get informed when a note is waiting for approval',
        ],
        [
          'value' => 'dev-alerts',
          'text' => 'Dev Alerts',
          'info' => 'Receive dev alerts',
        ],
      ],
      'website' => [
        [
          'value' => 'notes',
          'text'  => 'Notes',
          'info'  => 'Receive new notes',
        ]
      ]
    ],
    'db' => [
      'name' => 'push_notifications',
      /* default: site/push-notifications */
      'dir'  => 'playground/site/push-notifications',
    ],
  ],
  'hooks' => [
    /**
     * Send a push notification to all approvers when a note is set to unlisted
     * Example: https://github.com/philippoehrlein/Kirby-Push-Notifications/tree/main/examples/note-approval-workflow
     */
    'page.changeStatus:after' => function (Kirby\Cms\Page $newPage, Kirby\Cms\Page $oldPage) {
      if ($newPage->intendedTemplate()->name() !== 'note') return;
      if ($newPage->status() !== 'unlisted') return;

      $notes = site()->find('notes');
      $approvers = $notes->approver()->toUsers();
      $userIds = $approvers->pluck('id');

      $payload = [
        'message' => [
          'title' => 'Note waiting for approval',
          'body'  => 'Ready for review: ' . $newPage->title(),
          'data'  => ['url' => $newPage->panel()->url()],
        ],
        'channel' => 'note-approval',
      ];

      if (count($userIds) > 0) {
        $payload['user_ids'] = $userIds;
      }

      kirby()->trigger('philippoehrlein.push-notifications.send-to-many', ['payload' => $payload]);
    },
    /**
     * Send a push notification to all subscribers when a Kirby exception occurs
     * Example: https://github.com/philippoehrlein/Kirby-Push-Notifications/tree/main/examples/exception-notifications
     */
    'system.exception' => function (Throwable $exception) {
      // No push notifications while you are working on it
      if (option('debug')) {
        return;
      }

      $kirby = kirby();
      $cache = $kirby->cache('kpn-exceptions');

      // Build a key that groups similar exceptions together
      $url     = $kirby->request()->url()->toString();
      $key     = md5($exception->getMessage() . '|' . $url);
      $now     = time();
      $cooldown = 1200; // 20 minutes

      $lastSent = $cache->get($key);

      if ($lastSent && ($now - $lastSent) < $cooldown) {
        // Within cooldown window: skip sending a new push
        return;
      }

      // Store current timestamp with TTL (in minutes)
      $cache->set($key, $now, $cooldown / 60);

      $body = Str::short($exception->getMessage(), 120);

      $payload = [
        'message' => [
          'title' => '🙀 Ooops! Kirby Exception',
          'body'  => $body,
          'data'  => [
            'url' => $url,
          ],
        ],
        'channel' => 'dev-alerts',
        'options' => [
          'urgency' => 'high',
          'topic'   => 'dev-errors',
        ],
      ];

      kirby()->trigger(
        'philippoehrlein.push-notifications.send-to-many',
        ['payload' => $payload]
      );
    },
  ],
];
