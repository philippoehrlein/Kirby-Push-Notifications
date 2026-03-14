<template>
  <k-button
    :icon="icon ?? 'kpn-icon'"
    :text="text"
    :variant="variant ?? 'filled'"
    :size="size ?? 'sm'"
    :title="title ?? 'Toggle Notifications'"
    @click="openSubscribeDialog"
  />
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import type { Channel } from '../types/Channel';
import { usePanel } from 'kirbyuse';

interface GetChannelsResponse {
  status: 'success' | 'error';
  message?: string;
  channels: Channel[];
}

const props = defineProps<{
  channels?: Channel[] | null;
  icon?: string | null;
  text?: string | null;
  variant?: string | null;
  size?: string | null;
  title?: string | null;
}>();

const panel = usePanel();
const buttonChannels = ref<Channel[] | null>(null);

function openSubscribeDialog() {
  const dialog = panel.dialog.open({
    component: 'kpn-subscribe-dialog',
    props: {
      channels: buttonChannels.value,
    },
  })
}

onMounted(async () => {
  if (!props.channels || props.channels.length === 0) {
    const channels = await panel.api.get<GetChannelsResponse>('philippoehrlein/kirby-push-notifications/get-channels');
    if (channels.status === 'success') {
      console.log(channels);
      buttonChannels.value = channels.channels;
    }
  }
});
</script>