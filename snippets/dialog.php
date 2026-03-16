<?php
$configChannels = $data['channels'] ?? kirby()->option('philippoehrlein.push-notifications.channels') ?? [];
if (isset($configChannels['website'])) {
    $channels = $configChannels['website'] ?? [];
} else {
    $channels = $configChannels;
}
$headline = $data['headline'] ?? t('philippoehrlein.push-notifications.panel.dialog.title');
$description = $data['description'] ?? t('philippoehrlein.push-notifications.panel.dialog.description');
?>
<?= snippet('kpn-config') ?>
<?= snippet('kpn-dialog-styles') ?>
<script src="/assets/kpn/helper.js"></script>
<div class="kpn-subscribe-dialog">
  <dialog id="kpn-dialog" class="kpn-dialog">
    <div class="kpn-dialog-content">
      <header>
        <h2><?= $headline ?></h2>
        <p><?= $description ?></p>

        <p id="kpn-dialog-message" class="kpn-dialog-message" aria-live="polite" hidden></p>

      </header>
      <main>
        <div class="kpn-dialog-channels">
          <?php foreach ($channels as $channel) : ?>
            <div class="kpn-dialog-channel">
              <input tabindex="0" type="checkbox" id="kpn-channel-<?= esc($channel['value']) ?>" name="channels[]" value="<?= esc($channel['value']) ?>">
              <label class="kpn-dialog-label" for="kpn-channel-<?= esc($channel['value']) ?>" aria-label="<?= esc($channel['info'] ?? '') ?>"><?= esc($channel['text']) ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </main>
      <footer class="kpn-dialog-footer">
        <button id="kpn-dialog-subscribe" class="kpn-dialog-button kpn-dialog-button--subscribe" tabindex="0" type="button">
          <?= t('philippoehrlein.push-notifications.panel.dialog.subscribe.label') ?>
        </button>
        <button id="kpn-dialog-unsubscribe" class="kpn-dialog-button kpn-dialog-button--unsubscribe" tabindex="0" type="button">
          <?= t('philippoehrlein.push-notifications.panel.dialog.unsubscribe.label') ?>
        </button>
      </footer>
    </div>
  </dialog>
  <button 
    id="kpn-button" 
    title="<?= t('philippoehrlein.push-notifications.panel.dialog.button.label') ?>"
    aria-label="<?= t('philippoehrlein.push-notifications.panel.dialog.button.label') ?>">
    <?= $slot ?>
  </button>
</div>

<script>
(function() {
  const dialog = document.getElementById('kpn-dialog');
  const button = document.getElementById('kpn-button');
  const subscribeBtn = document.getElementById('kpn-dialog-subscribe');
  const unsubscribeBtn = document.getElementById('kpn-dialog-unsubscribe');
  const messageEl = document.getElementById('kpn-dialog-message');
  const msg = (window.KPN_CONFIG && window.KPN_CONFIG.messages) || {};

  function showMessage(text, type) {
    if (!messageEl) return;
    messageEl.textContent = text || '';
    messageEl.hidden = !text;
    messageEl.className = 'kpn-dialog-message' + (type ? ' kpn-dialog-message--' + type : '');
  }

  function setLoading(loading) {
    if (subscribeBtn) subscribeBtn.disabled = loading;
    if (unsubscribeBtn) unsubscribeBtn.disabled = loading;
  }

  function getSelectedChannels() {
    const inputs = dialog ? dialog.querySelectorAll('.kpn-dialog-channel input[type="checkbox"]:checked') : [];
    if (!inputs.length) return [];
    return Array.from(inputs).map(function(inp) { return inp.value; }).filter(Boolean);
  }

  function getMessage(key) {
    return (msg[key] != null) ? msg[key] : (key || '');
  }

  if (!window.KPN) {
    showMessage(getMessage('browserNotSupported'), 'error');
    return;
  }

  button && button.addEventListener('click', function() {
    dialog && dialog.showModal();
    showMessage('');
  });

  dialog && dialog.addEventListener('click', function(e) {
    if (e.target === dialog) dialog.close();
  });

  dialog && dialog.addEventListener('close', function() {
    window.removeEventListener('keydown', closeOnEscape);
  });

  function closeOnEscape(e) {
    if (e.key === 'Escape') dialog && dialog.close();
  }

  dialog && dialog.addEventListener('show', function() {
    window.addEventListener('keydown', closeOnEscape);
  });

  subscribeBtn && subscribeBtn.addEventListener('click', function() {
    const channels = getSelectedChannels();
    if (!channels.length) {
      showMessage(getMessage('noChannels'), 'error');
      return;
    }
    setLoading(true);
    showMessage('');
    window.KPN.subscribe(channels).then(function() {
      showMessage(getMessage('subscriptionSuccess'), 'success');
      setTimeout(function() { dialog && dialog.close(); }, 1000);
    }).catch(function(err) {
      showMessage((err && err.code ? getMessage(err.code) : '') || (err && err.message) || getMessage('subscriptionFailedMessage'), 'error');
    }).finally(function() {
      setLoading(false);
    });
  });

  unsubscribeBtn && unsubscribeBtn.addEventListener('click', function() {
    setLoading(true);
    showMessage('');
    window.KPN.unsubscribe().then(function() {
      showMessage(getMessage('unsubscriptionSuccess'), 'success');
      setTimeout(function() { dialog && dialog.close(); }, 1000);
    }).catch(function(err) {
      showMessage((err && err.code ? getMessage(err.code) : '') || (err && err.message) || getMessage('unsubscriptionFailed'), 'error');
    }).finally(function() {
      setLoading(false);
    });
  });
})();
</script>