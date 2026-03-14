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
import type { Channel } from '../types/Channel';
import { usePanel } from 'kirbyuse';

const props = defineProps<{
  channels: Channel[];
  icon?: string | null;
  text?: string | null;
  variant?: string | null;
  size?: string | null;
  title?: string | null;
}>();

const panel = usePanel();

function openSubscribeDialog() {
  const dialog = panel.dialog.open({
    component: 'kpn-subscribe-dialog',
    props: {
      channels: props.channels,
    },
  })
}

</script>