# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [1.2.0] - 2026-05-29

### Added
- **Development:** Local Kirby playground (`playground/`) with minimal demo site, example hooks (note approval, exception alerts) and sample VAPID configuration. Repository only — excluded from Composer/dist via `.gitattributes`. Runtime SQLite database, accounts, cache and sessions are gitignored.

### Changed
- **Panel – send notification dialog:** Shows an info message instead of the form when there are no subscriptions, so nothing can be sent unnecessarily.
- **Panel – send notification dialog:** Channel and language fields are shown only when needed (select for multiple options, hidden field with preset value otherwise). Side-by-side layout when both selects are visible.
- **Panel – send notification dialog:** Language options use localized display names (`locale_get_display_language`) based on the current Panel user language instead of raw language codes.

## [1.1.0] - 2026-03-16

### Added
- **Examples:** Added `note-approval-workflow` (editorial approval flow for notes), `simple-subscribe-button` (Starterkit example with a single “Subscribe to Notes” button) and `exception-notifications` (project-level example for sending error pushes from the `system.exception` hook with rate limiting).

### Changed
- **Configuration:** `channels` option can now be flat or grouped into `panel`/`website`; the `get-channels` API normalizes both shapes.
- **Docs:** Clarified that defining `channels` in plugin options is optional and mainly needed for config-driven UIs like `kpn-dialog`; custom UIs, helper.js and Panel buttons can pass channels directly.
- **Panel & dialog UX:** Improved subscribe button and dialog behavior, including better channel typing and labels.
- **Translations:** Updated English and German strings to match the new configuration and UI texts.

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

