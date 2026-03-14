## Kirby Push Notifications – Ideen & Plan

### Ziele
- Generischer Push-Benachrichtigungsdienst für Kirby (Panel + Frontend).
- Keine Domänenlogik im Plugin; nur Infra (Subscriptions speichern, WebPush anstoßen).
- Ansteuerbar über eigene `philippoehrlein.kirby-push-notifications.*`-Hooks aus anderen Plugins (z.B. Habitat, Blog, Campaign Manager).

### Technische Basis
- PHP 8.2+, Extensions: `mbstring`, `curl`, `openssl` (EC), optional `bcmath`/`gmp`.
- Library: [`minishlink/web-push`](https://github.com/web-push-libs/web-push-php) via Composer.
- VAPID-Konfiguration über Kirby-Config (`philippoehrlein.kirby-push-notifications.vapid.subject`, `.publicKey`, `.privateKey`).

### Öffentliche Hooks des Plugins
- `philippoehrlein.kirby-push-notifications.subscribe`
  - Payload: `user_id` (optional), `channel`, `subscription` (roh vom Browser).
  - Aufgabe: Subscription für User/Channel speichern oder aktualisieren.
- `philippoehrlein.kirby-push-notifications.unsubscribe`
  - Payload: `user_id` oder `endpoint`, optional `channel`.
  - Aufgabe: Subscription(en) deaktivieren/löschen.
- `philippoehrlein.kirby-push-notifications.send-to-user`
  - Payload: `user_id`, `title`, `body`, optional `url`, `icon`, `tag`, `data`, `topic`, `urgency`, `channel`.
  - Aufgabe: Alle aktiven Subscriptions des Users (optional gefiltert nach Channel) benachrichtigen.
- `philippoehrlein.kirby-push-notifications.send-to-many`
  - Payload: `user_ids` (array) **oder** `filter` (z.B. Rolle), plus Payload wie bei `send-to-user`.
  - Aufgabe: Broadcast an mehrere User.
- (Optional intern) `philippoehrlein.kirby-push-notifications.sent`
  - Wird vom Plugin nach Versand/Fehler ausgelöst (Logging, Cleanup).

### PHP-API (intern, kann später public gemacht werden)
- `KirbyPush\SubscriptionsRepository`
  - `subscribe(string $endpoint, array $keys, ?string $userId = null, ?string $channel = null): void`
  - `unsubscribeByEndpoint(string $endpoint): void`
  - `unsubscribeByUser(string $userId, ?string $channel = null): void`
  - `listByUser(string $userId, ?string $channel = null): array`
  - `deleteExpiredByEndpoint(string $endpoint): void`
- `KirbyPush\WebPushService`
  - Konstruktor nimmt VAPID-Config + Default-Options.
  - `sendToSubscriptions(array $subscriptions, array $payload, array $options = []): void`
  - Kümmert sich um Mapping auf `Minishlink\WebPush\Subscription` und Fehlerbehandlung (z.B. abgelaufene Subscriptions entfernen).
- `KirbyPush\Notifier`
  - Höhere Ebene: `notifyUser(string $userId, array $message, array $options = []): void`
  - `notifyMany(array $userIds, array $message, array $options = []): void`

### Datenmodell / Storage
- Eigene Tabelle (z.B. `push_subscriptions`) über Migrationsskript:
  - `id` (UUID), `user_id` (nullable), `channel` (string, z.B. `panel`, `expert`, `frontend`),
  - `endpoint` (string, unique),
  - `keys_json` (JSON: `p256dh`, `auth`, evtl. `contentEncoding`),
  - `created_at`, `updated_at`, optional `last_used_at`.
- Alternative für kleine Setups: Dateibasiert (später).

### Frontend / Service Worker (Ideen)
- Eine gemeinsame JS-Hilfsfunktion (z.B. in `assets/push.js`), die:
  - Service Worker registriert.
  - `pushManager.subscribe` mit VAPID-Public-Key aufruft.
  - Subscription-JSON an eine Kirby-Route schickt, die dann `philippoehrlein.kirby-push-notifications.subscribe` triggert.
- Service Worker-Kontrakt:
  - Erwartetes Payload-Format: `{ title, body, url?, icon?, tag?, data? }`.
  - Klick auf Notification öffnet `url` oder fokussiert bestehenden Tab.

### Beispiel-Integrationen (später)
- Habitat:
  - Bei neuem Short-Candidate: `philippoehrlein.kirby-push-notifications.send-to-many` an Reviewer-User.
  - Bei `short_accepted` / `short_denied`: `philippoehrlein.kirby-push-notifications.send-to-user` an Expert:in.
- Kirby-Core:
  - `page.create:after` → `philippoehrlein.kirby-push-notifications.send-to-many` an Admins mit „Neuer Blog-Post“.
- Campaign Manager:
  - Reminder/Follow-Ups als Push ergänzen.

### ToDos (MVP)
- [x] Composer-Setup für `minishlink/web-push` finalisieren.
- [x] VAPID-Key-Handling definieren (Config-Struktur + Doku, optional CLI-Helfer zum Generieren).
- [x] Datenmodell für Subscriptions festziehen (Tabelle + einfache Migration).
- [x] `SubscriptionsRepository` implementieren.
- [x] `WebPushService` implementieren (inkl. sinnvollen Default-Options).
- [ ] Kirby-Hooks `philippoehrlein.kirby-push-notifications.subscribe` / `philippoehrlein.kirby-push-notifications.unsubscribe` / `philippoehrlein.kirby-push-notifications.send-to-user` / `philippoehrlein.kirby-push-notifications.send-to-many` registrieren und mit Repo/Service verdrahten.
- [ ] Minimale Kirby-Route + JS-Snippet für Subscription-Registrierung entwerfen.
- [ ] Einfachen Service-Worker-Kontrakt skizzieren (nur im Ideen/Doku-Teil, Implementierung später).
- [ ] Logging / Fehlerbehandlung (expired endpoints entfernen, optionale Debug-Ausgabe).

