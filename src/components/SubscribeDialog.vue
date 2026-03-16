<template>
  <k-dialog
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
        <div v-else>
          <k-checkboxes-field
            v-if="uiChannels.length > 0"
            name="channels"
            :label="panel.t('philippoehrlein.push-notifications.panel.notifications.subscribe.label')"
            :options="uiChannels"
            :value="selectedChannels"
            :disabled="loading"
            :counter="false"
            @input="selectedChannels = $event"
          />
          <div v-else-if="defaultChannels" class="kpn-dialog-channels">
            <k-checkboxes-field
              name="channels"
              :label="panel.t('philippoehrlein.push-notifications.panel.notifications.subscribe.panel.label')"
              :options="defaultChannels.panel"
              :value="selectedChannels"
              :disabled="loading"
              :counter="false"
              @input="selectedChannels = $event"
            />
            <k-checkboxes-field
              name="channels"
              :label="panel.t('philippoehrlein.push-notifications.panel.notifications.subscribe.website.label')"
              :options="defaultChannels.website"
              :value="selectedChannels"
              :disabled="loading"
              :counter="false"
              @input="selectedChannels = $event"
            />
          </div>
        </div>
      </div>
    </template>
  </k-dialog>
</template>

<script setup lang="ts">
import type { Channel, Channels } from '../types/Channel';
import { usePanel } from 'kirbyuse';
import { computed, onMounted, ref } from 'vue';
import { usePushNotifications } from '../services/pushNotifications';

const props = defineProps<{
  channels: Channel[];
}>();

const emit = defineEmits<{
  cancel: [];
  submit: [];
}>();

const panel = usePanel();
const push = usePushNotifications();

const loading = ref(false);
const error = ref<string | null>(null);
const selectedChannels = ref<string[]>([]);
const uiChannels = ref<Channel[]>(props.channels ?? []);
const defaultChannels = ref<Channels | null>(null);

const availableChannels = computed<Channel[]>(() => {
  if (uiChannels.value && uiChannels.value.length > 0) {
    return uiChannels.value;
  }
  if (defaultChannels.value) {
    return [
      ...(defaultChannels.value.panel || []),
      ...(defaultChannels.value.website || []),
    ];
  }
  return [];
});

const support = computed(() => push.checkSupport());
const supported = computed(() => support.value.supported);
const supportReason = computed(() =>
  support.value.supported ? '' : (support.value as { reason: string }).reason
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
    for (const ch of availableChannels.value) {
      const isSub = await push.isSubscribedForCurrentUser(ch.value, endpoint);
      if (isSub) subscribed.push(ch.value);
    }
    selectedChannels.value = subscribed;
  } catch (e: unknown) {
    console.error('[kpn] Error loading subscriptions:', e);
    error.value = e instanceof Error ? e.message : 'Unknown error';
  } finally {
    loading.value = false;
  }
}

async function submit(): Promise<void> {
  if (!supported.value) {
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
    console.error('[kpn] Error saving:', e);
    error.value = e instanceof Error ? e.message : 'Unknown error';
  } finally {
    loading.value = false;
  }
}

async function loadDefaultChannels(): Promise<void> {
  const response = await panel.api.get('philippoehrlein/push-notifications/get-channels');
  if (response.status === 'success') {
    const ch = response.channels;
    if(Array.isArray(ch)) {
      uiChannels.value = ch;
    } else {
      defaultChannels.value = ch;
    }
  }
}
onMounted(async () => {
  if (!props.channels || props.channels.length === 0) await loadDefaultChannels();
  await loadInitialState();
});
</script>


<style scoped>
.kpn-dialog-channels {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-4);
}
</style>