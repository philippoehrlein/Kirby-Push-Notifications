<?php

return [
  // === PANEL ===
  'philippoehrlein.kirby-push-notifications.panel.notifications.title' => 'Kirby Push Notifications',
  'philippoehrlein.kirby-push-notifications.panel.notifications.confirm' => 'Bist du sicher, dass du Benachrichtigungen für {channel} aktivieren möchtest?',
  'philippoehrlein.kirby-push-notifications.panel.notifications.subscribe.button' => 'Aktivieren',
  'philippoehrlein.kirby-push-notifications.panel.notifications.unsubscribe.confirm' => 'Bist du sicher, dass du Benachrichtigungen für {channel} deaktivieren möchtest?',
  'philippoehrlein.kirby-push-notifications.panel.notifications.unsubscribe.button' => 'Deaktivieren',

  'philippoehrlein.kirby-push-notifications.panel.notifications.error.no_endpoint_or_keys' => 'Kein Endpoint oder Keys gefunden',
  'philippoehrlein.kirby-push-notifications.panel.notifications.subscribe.label' => 'Abonnieren',

  'philippoehrlein.kirby-push-notifications.panel.send-notification.title.label' => 'Titel',
  'philippoehrlein.kirby-push-notifications.panel.send-notification.body.label' => 'Inhalt',
  'philippoehrlein.kirby-push-notifications.panel.send-notification.channel.label' => 'Kanal',
  'philippoehrlein.kirby-push-notifications.panel.send-notification.url.label' => 'Link',
  'philippoehrlein.kirby-push-notifications.panel.send-notification.submit.label' => 'Senden',

  // === API ===
  'philippoehrlein.kirby-push-notifications.api.get-channels.error.no_channels_configured' => 'Keine Kanäle konfiguriert',
  'philippoehrlein.kirby-push-notifications.api.get-keys.error.no_vapid_public_key' => 'VAPID-Konfiguration fehlt',
  'philippoehrlein.kirby-push-notifications.api.status.error.invalid_endpoint_or_channel' => 'Ungültiger Endpoint oder Kanal',

  // === HOOKS ===
  'philippoehrlein.kirby-push-notifications.hooks.error.invalid_payload' => 'Ungültige Payload',
  'philippoehrlein.kirby-push-notifications.hooks.error.missing_endpoint_or_user' => 'Endpoint oder User-ID fehlt.',
  'philippoehrlein.kirby-push-notifications.hooks.error.message_required' => 'Nachricht ist erforderlich.',
  'philippoehrlein.kirby-push-notifications.hooks.error.user_ids_or_channel_required' => 'User-IDs oder Kanal ist erforderlich.',

  // === FRONTEND ===
  'philippoehrlein.kirby-push-notifications.panel.dialog.button.label' => 'Benachrichtigungen aktivieren',
  'philippoehrlein.kirby-push-notifications.panel.dialog.title' => 'Benachrichtigungen aktivieren',
  'philippoehrlein.kirby-push-notifications.panel.dialog.description' => 'Wähle die Kanäle aus, für die du Benachrichtigungen erhalten möchtest.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.subscribe.label' => 'Abonnieren',
  'philippoehrlein.kirby-push-notifications.panel.dialog.unsubscribe.label' => 'Alle Abbestellen',

  // === FRONTEND MESSAGES ===
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.no_vapid_public_key' => 'Push Notifications sind nicht konfiguriert.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.no_channels_selected' => 'Bitte mindestens einen Kanal auswählen.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.browser_not_supported' => 'Dein Browser unterstützt keine Push Notifications.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.notifications_blocked' => 'Notifications wurden blockiert. Bitte in den Browsereinstellungen erlauben.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.notifications_not_granted' => 'Notifications wurden nicht erlaubt.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.subscription_failed' => 'Abo fehlgeschlagen',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.subscription_failed_message' => 'Etwas ist schiefgelaufen. Bitte später erneut versuchen.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.success.subscription_success' => 'Du erhältst jetzt Notifications für die gewählten Kanäle.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.success.subscription_success_message' => 'Du erhältst jetzt Notifications für die gewählten Kanäle.',

  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.not_subscribed' => 'Du hast keine aktiven Benachrichtigungen.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.unsubscription_failed' => 'Abbestellung fehlgeschlagen',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.error.unsubscription_failed_message' => 'Etwas ist schiefgelaufen. Bitte später erneut versuchen.',
  'philippoehrlein.kirby-push-notifications.panel.dialog.message.success.unsubscription_success_message' => 'Du erhältst jetzt keine Notifications mehr.',
];