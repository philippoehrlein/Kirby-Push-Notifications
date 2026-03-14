/**
 * KPN Helper – generic Web-Push-API.
 * Expects window.KPN_CONFIG before loading:
 *   { vapidPublicKey, subscribeUrl, unsubscribeUrl, swPath }
 */
(function () {
  'use strict';

  function getConfig() {
    return window.KPN_CONFIG || {};
  }

  function createError(code, message) {
    var err = new Error(message);
    err.code = code;
    return err;
  }

  function base64UrlToArrayBuffer(base64Url) {
    const padding = '='.repeat((4 - (base64Url.length % 4)) % 4);
    const base64 = (base64Url + padding).replace(/-/g, '+').replace(/_/g, '/');
    const raw = window.atob(base64);
    const out = new Uint8Array(raw.length);
    for (let i = 0; i < raw.length; i++) out[i] = raw.charCodeAt(i);
    return out.buffer;
  }

  /**
   * Returns current push subscription. Optionally creates one (requests permission).
   * @param {boolean} createIfMissing
   * @returns {Promise<PushSubscription|null>}
   */
  async function getSubscription(createIfMissing) {
    const config = getConfig();
    const vapidPublicKey = config.vapidPublicKey || '';
    const swPath = config.swPath || '/kpn-sw.js';

    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return null;
    const reg = await navigator.serviceWorker.register(swPath, { scope: '/' });
    await reg.ready;
    let sub = await reg.pushManager.getSubscription();
    if (!sub && createIfMissing && vapidPublicKey) {
      if (typeof Notification !== 'undefined' && Notification.permission === 'default') {
        await Notification.requestPermission();
      }
      if (typeof Notification !== 'undefined' && Notification.permission !== 'granted') return null;
      sub = await reg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: base64UrlToArrayBuffer(vapidPublicKey)
      });
    }
    return sub;
  }

  /**
   * Register subscription with backend.
   * @param {string[]} channels
   * @returns {Promise<void>}
   */
  async function subscribe(channels) {
    const config = getConfig();
    const subscribeUrl = config.subscribeUrl || '/kpn/subscribe';
    const vapidPublicKey = config.vapidPublicKey || '';

    if (!vapidPublicKey) throw createError('noVapid', 'KPN_CONFIG.vapidPublicKey missing');
    if (!channels || !channels.length) throw createError('noChannels', 'At least one channel required');
    if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
      throw createError('browserNotSupported', 'Browser does not support push notifications');
    }
    if (Notification.permission === 'denied') throw createError('notificationsBlocked', 'Notifications blocked');

    const subscription = await getSubscription(true);
    if (!subscription) throw createError('notificationsNotGranted', 'No subscription (permission denied or failed)');

    const json = subscription.toJSON();
    const endpoint = json.endpoint || subscription.endpoint;
    const keys = json.keys;
    if (!endpoint || !keys) throw createError('subscriptionFailed', 'Subscription data incomplete');

    for (const channel of channels) {
      const res = await fetch(subscribeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ endpoint, keys, channel })
      });
      const data = await res.json().catch(function () { return {}; });
      if (!res.ok || data.status !== 'success') {
        throw createError('subscriptionFailed', data.message || 'Subscribe failed');
      }
    }
  }

  /**
   * Unregister subscription at backend and unsubscribe locally.
   * @returns {Promise<void>}
   */
  async function unsubscribe() {
    const config = getConfig();
    const unsubscribeUrl = config.unsubscribeUrl || '/kpn/unsubscribe';

    const subscription = await getSubscription(false);
    if (subscription) {
      const res = await fetch(unsubscribeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ endpoint: subscription.endpoint })
      });
      const data = await res.json().catch(function () { return {}; });
      if (!res.ok || data.status !== 'success') {
        throw createError('unsubscriptionFailed', data.message || 'Unsubscribe failed');
      }
      await subscription.unsubscribe();
    }
  }

  window.KPN = {
    getSubscription: getSubscription,
    subscribe: subscribe,
    unsubscribe: unsubscribe
  };
})();
