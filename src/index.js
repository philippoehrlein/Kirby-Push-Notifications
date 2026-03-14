import SubscribeButton from './components/SubscribeButton.vue';
import SubscribeDialog from './components/SubscribeDialog.vue';
import icons from './assets/icons.js';

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register(
    '/kpn-sw.js',
    { scope: '/' }
  );
}

window.panel.plugin('philippoehrlein/kirby-push-notifications', {
  icons,
  components: {
    'kpn-subscribe-button': SubscribeButton,
    'kpn-subscribe-dialog': SubscribeDialog,
  },
});
