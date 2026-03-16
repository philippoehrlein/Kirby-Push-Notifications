<?php
$languages = kirby()->languages();

$vapidPublicKey = option('philippoehrlein.push-notifications.vapid.publicKey');
if($languages->count() > 1) {
  $subscribeUrl = '/push-notifications/subscribe/lang:'.$languages->first()->code();
} else {
  $subscribeUrl = '/push-notifications/subscribe';
}
$unsubscribeUrl = '/push-notifications/unsubscribe';
$swPath = '/push-notifications-sw.js';
?>

<script>
window.KPN_CONFIG = {
  vapidPublicKey: <?= json_encode($vapidPublicKey ?? '') ?>,
  subscribeUrl: <?= json_encode($subscribeUrl) ?>,
  unsubscribeUrl: <?= json_encode($unsubscribeUrl) ?>,
  swPath: <?= json_encode($swPath) ?>,
  messages: {
    noVapid: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.no_vapid_public_key')) ?>,
    noChannels: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.no_channels_selected')) ?>,
    browserNotSupported: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.browser_not_supported')) ?>,
    notificationsBlocked: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.notifications_blocked')) ?>,
    notificationsNotGranted: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.notifications_not_granted')) ?>,
    subscriptionFailed: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.subscription_failed')) ?>,
    subscriptionFailedMessage: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.subscription_failed_message')) ?>,
    subscriptionSuccess: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.success.subscription_success')) ?>,
    unsubscriptionFailed: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.error.unsubscription_failed_message')) ?>,
    unsubscriptionSuccess: <?= json_encode(t('philippoehrlein.push-notifications.panel.dialog.message.success.unsubscription_success_message')) ?>
  }
};
</script>