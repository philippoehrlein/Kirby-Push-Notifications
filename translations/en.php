<?php

return [
  // === PANEL ===
  'philippoehrlein.push-notifications.panel.notifications.title' => 'Kirby Push Notifications',
  'philippoehrlein.push-notifications.panel.notifications.confirm' => 'Are you sure you want to subscribe to notifications?',
  'philippoehrlein.push-notifications.panel.notifications.subscribe.button' => 'Subscribe',
  'philippoehrlein.push-notifications.panel.notifications.unsubscribe.confirm' => 'Are you sure you want to unsubscribe from notifications?',
  'philippoehrlein.push-notifications.panel.notifications.unsubscribe.button' => 'Unsubscribe',

  'philippoehrlein.push-notifications.panel.notifications.error.no_endpoint_or_keys' => 'No endpoint or keys found',
  'philippoehrlein.push-notifications.panel.notifications.subscribe.label' => 'Subscribe',
  'philippoehrlein.push-notifications.panel.notifications.subscribe.panel.label' => 'Panel Notifications',
  'philippoehrlein.push-notifications.panel.notifications.subscribe.website.label' => 'Website Notifications',

  'philippoehrlein.push-notifications.panel.send-notification.title.label' => 'Title',
  'philippoehrlein.push-notifications.panel.send-notification.body.label' => 'Body',
  'philippoehrlein.push-notifications.panel.send-notification.channel.label' => 'Channel',
  'philippoehrlein.push-notifications.panel.send-notification.language.label' => 'Language',
  'philippoehrlein.push-notifications.panel.send-notification.url.label' => 'Link',
  'philippoehrlein.push-notifications.panel.send-notification.submit.label' => 'Send',

  'philippoehrlein.push-notifications.panel.send-notification.no-subscriptions.text' => 'There are <b>no subscriptions</b> for Push Notifications yet.',

  // === API ===
  'philippoehrlein.push-notifications.api.get-channels.error.no_channels_configured' => 'No channels configured',
  'philippoehrlein.push-notifications.api.get-keys.error.no_vapid_public_key' => 'VAPID configuration is missing',
  'philippoehrlein.push-notifications.api.status.error.invalid_endpoint_or_channel' => 'Invalid endpoint or channel',

  // === HOOKS ===
  'philippoehrlein.push-notifications.hooks.error.invalid_payload' => 'Invalid payload',
  'philippoehrlein.push-notifications.hooks.error.missing_endpoint_or_user' => 'Endpoint or user ID is required.',
  'philippoehrlein.push-notifications.hooks.error.message_required' => 'Message is required.',
  'philippoehrlein.push-notifications.hooks.error.user_ids_or_channel_required' => 'User IDs or channel is required.',
  'philippoehrlein.push-notifications.hooks.error.invalid_channel' => 'Invalid or not allowed channel.',

  // === FRONTEND ===
  'philippoehrlein.push-notifications.panel.dialog.button.label' => 'Enable notifications',
  'philippoehrlein.push-notifications.panel.dialog.title' => 'Enable notifications',
  'philippoehrlein.push-notifications.panel.dialog.description' => 'Select the channels you want to receive notifications for.',
  'philippoehrlein.push-notifications.panel.dialog.subscribe.label' => 'Subscribe',
  'philippoehrlein.push-notifications.panel.dialog.unsubscribe.label' => 'Unsubscribe all',

  // === FRONTEND MESSAGES ===
  'philippoehrlein.push-notifications.panel.dialog.message.error.no_vapid_public_key' => 'Push Notifications are not configured.',
  'philippoehrlein.push-notifications.panel.dialog.message.error.no_channels_selected' => 'Please select at least one channel.',
  'philippoehrlein.push-notifications.panel.dialog.message.error.browser_not_supported' => 'Your browser does not support Push Notifications.',
  'philippoehrlein.push-notifications.panel.dialog.message.error.notifications_blocked' => 'Notifications are blocked. Please allow them in the browser settings.',
  'philippoehrlein.push-notifications.panel.dialog.message.error.notifications_not_granted' => 'Notifications are not granted.',
  'philippoehrlein.push-notifications.panel.dialog.message.error.subscription_failed' => 'Subscription failed',
  'philippoehrlein.push-notifications.panel.dialog.message.error.subscription_failed_message' => 'Something went wrong. Please try again later.',
  'philippoehrlein.push-notifications.panel.dialog.message.success.subscription_success' => 'You will now receive Notifications for the selected channels.',
  'philippoehrlein.push-notifications.panel.dialog.message.success.subscription_success_message' => 'You will now receive Notifications for the selected channels.',

  'philippoehrlein.push-notifications.panel.dialog.message.error.not_subscribed' => 'You have no active notifications.',
  'philippoehrlein.push-notifications.panel.dialog.message.error.unsubscription_failed' => 'Unsubscription failed',
  'philippoehrlein.push-notifications.panel.dialog.message.error.unsubscription_failed_message' => 'Something went wrong. Please try again later.',
  'philippoehrlein.push-notifications.panel.dialog.message.success.unsubscription_success_message' => 'You will now receive no more Notifications.',
];