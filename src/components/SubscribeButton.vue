<template>
  <k-button
    :icon="icon ?? 'kpn-icon'"
    :text="text"
    :variant="variant ?? 'filled'"
    :size="size ?? 'sm'"
    :theme="theme"
    :disabled="disabled ?? false"
    :title="title ?? 'Toggle Notifications'"
    @click="openSubscribeDialog"
  />
</template>

<script setup lang="ts">
import type { Channel } from '../types/Channel';
import { usePanel } from 'kirbyuse';

const props = defineProps<{
  channels?: Channel[] | null;
  icon?: string | null;
  text?: string | null;
  variant?: string | null;
  size?: string | null;
  title?: string | null;
  theme?: string | null;
  disabled?: boolean | null;
}>();

const panel = usePanel();

function openSubscribeDialog() {
  panel.dialog.open({
    component: 'kpn-subscribe-dialog',
    props: {
      channels: props.channels,
    },
  })
}
</script>