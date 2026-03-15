# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [1.0.0] - 2026-03-14

### Initial Release
- **Channel-based subscriptions** – Configure channels in options; visitors subscribe per channel. Subscriptions stored in SQLite.
- **Website – simple:** `kpn-dialog` snippet (button + dialog with channel checkboxes, subscribe/unsubscribe).
- **Website – custom:** `helper.js` – set `KPN_CONFIG`, then use `window.KPN.subscribe(channels)` / `window.KPN.unsubscribe()` with your own UI.
- **Panel – simple:** View buttons (`kpn-subscribe`, `kpn-notification`), components (`kpn-subscribe-button`, `kpn-notification-button`, `kpn-subscribe-dialog`) for subscribe and send-notification flows.
- **Panel – custom:** Hooks and Panel API (get-channels, get-keys, status, subscribe, unsubscribe) for custom UI.
- **Hooks:** `subscribe`, `unsubscribe`, `send-to-one`, `send-to-many` for automation and custom logic.
- **Frontend routes:** `POST /push-notifications/subscribe`, `POST /push-notifications/unsubscribe`, `GET /push-notifications-sw.js`, `GET /assets/kpn/helper.js`.
- **VAPID** – Web Push via [minishlink/web-push](https://github.com/web-push-libs/web-push-php); configurable public/private key and subject.
- **i18n** – German and English translations for Panel and frontend dialog.
- **Installation** – Composer or manual (plugin ships with `vendor/`; no `composer install` in plugin folder required).

