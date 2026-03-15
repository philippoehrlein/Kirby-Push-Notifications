<template>
  <k-dialog
    ref="dialog"
    :visible="true"
    size="medium"
    :submit-button="!loading"
    :cancel-button="!loading"
    @cancel="emit('cancel')"
    @submit="submit()"
  >
    <template #default>
      <div v-if="loading">
        <k-icon-frame icon="loader" ratio="2/1" />
      </div>
      <div v-else>
        <k-box v-if="error" :text="error" theme="negative" />
        <k-box v-else-if="!supported" :text="supportReason" theme="warning" />
        <k-checkboxes-field
          v-else
          name="channels"
          :label="panel.t('philippoehrlein.push-notifications.panel.notifications.subscribe.label')"
          :options="channelOptions"
          :value="selectedChannels"
          :disabled="loading"
          :counter="false"
          @input="selectedChannels = $event"
        />
      </div>
    </template>
  </k-dialog>
</template>

<script setup lang="ts">
import type { Channel } from '../types/Channel';
import { ref, computed, onMounted } from 'vue';
import { usePushNotifications } from '../services/pushNotifications';
import { usePanel } from 'kirbyuse';

const props = defineProps<{
  channels: Channel[];
}>();

const emit = defineEmits<{
  cancel: [];
  submit: [];
}>();

const panel = usePanel();
const push = usePushNotifications();

const loading = ref(true);
const error = ref<string | null>(null);
const selectedChannels = ref<string[]>([]);

const support = computed(() => push.checkSupport());
const supported = computed(() => support.value.supported);
const supportReason = computed(() =>
  support.value.supported ? '' : (support.value as { reason: string }).reason
);

const channelOptions = computed(() =>
  props.channels.map((c) => ({ value: c.value, text: c.text }))
);

async function loadInitialState(): Promise<void> {
  loading.value = true;
  error.value = null;
  selectedChannels.value = [];

  try {
    if (!push.checkSupport().supported) {
      loading.value = false;
      return;
    }

    const subscription = await push.getExistingSubscription();
    if (!subscription) {
      loading.value = false;
      return;
    }

    const json = push.toJson(subscription);
    const endpoint = json.endpoint;
    if (!endpoint) {
      loading.value = false;
      return;
    }

    const subscribed: string[] = [];
    for (const ch of props.channels) {
      const isSub = await push.isSubscribedForCurrentUser(ch.value, endpoint);
      if (isSub) subscribed.push(ch.value);
    }
    selectedChannels.value = subscribed;
  } catch (e: unknown) {
    console.error('[kpn] Fehler beim Laden der Abos:', e);
    error.value = e instanceof Error ? e.message : 'Unbekannter Fehler';
  } finally {
    loading.value = false;
  }
}

async function submit(): Promise<void> {
  if (!supported) {
    emit('cancel');
    return;
  }

  loading.value = true;
  error.value = null;

  try {
    const subscription = await push.ensureSubscription();
    const json = push.toJson(subscription);
    const endpoint = json.endpoint;
    const keys = json.keys;

    if (!endpoint || !keys) {
      error.value = panel.t(
        'philippoehrlein.push-notifications.panel.notifications.error.no_endpoint_or_keys'
      );
      loading.value = false;
      return;
    }

    await push.unsubscribeEndpoint(endpoint);

    for (const channelValue of selectedChannels.value) {
      await push.subscribeChannel(endpoint, keys, channelValue);
    }

    emit('submit');
  } catch (e: unknown) {
    console.error('[kpn] Fehler beim Speichern:', e);
    error.value = e instanceof Error ? e.message : 'Unbekannter Fehler';
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  loadInitialState();
});
</script>
