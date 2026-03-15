import type { PushSubscriptionJson } from '../types/PushSubscriptionsJson';
import type { PushSupportState } from '../types/PushSupportState';
import type { VapidKeys } from '../types/VapidKeys';
import { usePanel } from 'kirbyuse';

// --- API base (Panel) ---

const API = 'philippoehrlein/push-notifications';

// --- Util ---

function base64UrlToArrayBuffer(base64Url: string): ArrayBuffer {
  const padding = '='.repeat((4 - (base64Url.length % 4)) % 4);
  const base64 = (base64Url + padding).replace(/-/g, '+').replace(/_/g, '/');
  const raw = window.atob(base64);
  const out = new Uint8Array(raw.length);
  for (let i = 0; i < raw.length; i++) out[i] = raw.charCodeAt(i);
  return out.buffer;
}

// --- Composable ---

export function usePushNotifications() {
  const panel = usePanel();

  // Support & Permission

  function checkSupport(): PushSupportState {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
      return { supported: false, reason: 'Browser does not support service worker or push API.' };
    }
    if (!('Notification' in window)) {
      return { supported: false, reason: 'Browser does not support web push notifications.' };
    }
    if (Notification.permission === 'denied') {
      return { supported: false, reason: 'Notifications were blocked by the browser.' };
    }
    return { supported: true };
  }

  async function requestPermission(): Promise<void> {
    if (Notification.permission === 'default') {
      const permission = await Notification.requestPermission();
      if (permission !== 'granted') {
        throw new Error('Notifications were not allowed.');
      }
    }
  }

  /** Permission anfragen, ohne Subscription zu erstellen. Z.B. vor Dialog öffnen. */
  async function requestPermissionEarly(): Promise<boolean> {
    if (!checkSupport().supported) return false;
    try {
      await requestPermission();
      return true;
    } catch {
      return false;
    }
  }

  // Subscription (Browser)

  async function getExistingSubscription(): Promise<PushSubscription | null> {
    if (!checkSupport().supported) return null;
    const reg = await navigator.serviceWorker.ready;
    return reg.pushManager.getSubscription();
  }

  async function ensureSubscription(): Promise<PushSubscription> {
    const support = checkSupport();
    if (!support.supported) throw new Error(support.reason);
    await requestPermission();

    const reg = await navigator.serviceWorker.ready;
    let sub = await reg.pushManager.getSubscription();
    if (!sub) {
      const keys = await panel.api.get(`${API}/get-keys`);
      if (keys.status !== 'success') throw new Error((keys as { message?: string }).message ?? 'VAPID keys failed.');
      sub = await reg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: base64UrlToArrayBuffer((keys.keys as VapidKeys).publicKey),
      });
    }
    if (!sub) throw new Error('Push-Subscription could not be created.');
    return sub;
  }

  function toJson(subscription: PushSubscription): PushSubscriptionJson {
    const j = subscription.toJSON() as PushSubscriptionJson;
    return { endpoint: j.endpoint ?? subscription.endpoint ?? undefined, keys: j.keys };
  }

  // API (Backend)

  async function isSubscribedForCurrentUser(channel: string, endpoint?: string): Promise<boolean> {
    if (!endpoint) return false;
    const res = await panel.api.get(`${API}/status`, { channel, endpoint });
    return res.status === 'success' && Boolean(res.subscribed);
  }

  async function subscribeChannel(endpoint: string, keys: Record<string, string>, channel: string): Promise<void> {
    const res = await panel.api.post(`${API}/subscribe`, { endpoint, keys, channel });
    if (res?.status !== 'success') throw new Error((res as { message?: string })?.message ?? 'Subscribe failed.');
  }

  async function unsubscribeEndpoint(endpoint: string): Promise<void> {
    const res = await panel.api.post(`${API}/unsubscribe`, { endpoint });
    if (res?.status !== 'success') throw new Error((res as { message?: string })?.message ?? 'Unsubscribe failed.');
  }

  return {
    checkSupport,
    requestPermission,
    requestPermissionEarly,
    getExistingSubscription,
    ensureSubscription,
    toJson,
    isSubscribedForCurrentUser,
    subscribeChannel,
    unsubscribeEndpoint,
  };
}

