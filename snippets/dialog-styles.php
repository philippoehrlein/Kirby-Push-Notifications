<style>
  .kpn-subscribe-dialog {
  --kpn-bg: white;
  --kpn-color: black;
  --kpn-message-color: black;
  --kpn-message-error-color: #c00;
  --kpn-message-success-color: #080;
  --kpn-radius: 0px;
  --kpn-padding: 1.5rem;
  --kpn-shadow: 0 10px 30px rgba(0,0,0,0.15);
  --kpn-max-width: 460px;

  --kpn-channels-padding: 1rem 0 2.5rem 0;

  --kpn-headline-size: 1.5rem;
  --kpn-headline-margin: 0 0 0.25rem 0;

  --kpn-button-background: black;
  --kpn-button-color: white;
}

.kpn-dialog {
  border: none;
  padding: 0;
  background: transparent;
  position: fixed;
  inset: 0;
  margin: auto;
  max-width: var(--kpn-max-width);
}

.kpn-dialog::backdrop {
  background: rgba(0,0,0,0.4);
}

.kpn-dialog-content {
  box-sizing: border-box;
  background: var(--kpn-bg);
  color: var(--kpn-color);
  padding: var(--kpn-padding);
  border-radius: var(--kpn-radius);
  max-width: var(--kpn-max-width);
  width: 100%;
  box-shadow: var(--kpn-shadow);
  margin: 0;
}

.kpn-dialog-content h2 {
  font-size: var(--kpn-headline-size);
  margin: var(--kpn-headline-margin);
}

.kpn-dialog-channels {
  padding: var(--kpn-channels-padding);
  display: flex;
  flex-direction: column;
}

.kpn-dialog-channel {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.kpn-dialog-label {
  padding: 0.5rem 0;
  display: block;
}

.kpn-dialog-footer {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.kpn-dialog-button {
  width: 100%;
  padding: 0.5rem 0;
  display: block;
  text-align: center;
  border: none;
  background: var(--kpn-button-background);
  color: var(--kpn-button-color);
  cursor: pointer;
}
.kpn-dialog-button--subscribe {
  background: var(--kpn-button-background);
  color: var(--kpn-button-color);
}
.kpn-dialog-button--unsubscribe {
  background: var(--kpn-bg);
  color: var(--kpn-color);
  font-size: 0.8rem;
}

#kpn-button {
  cursor: pointer;
}

.kpn-dialog-message {
  margin: 0.5rem 0 0.75rem 0;
  font-size: 0.9rem;
  color: var(--kpn-message-color);
}

.kpn-dialog-message.kpn-dialog-message--error {
  color: var(--kpn-message-error-color);
}

.kpn-dialog-message.kpn-dialog-message--success {
  color: var(--kpn-message-success-color);
}

.kpn-dialog-subscribe:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>