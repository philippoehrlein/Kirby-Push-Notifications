<template>
  <k-button
    :icon="icon ?? 'kpn-send'"
    :text="text"
    :variant="variant ?? 'filled'"
    :size="size ?? 'sm'"
    :title="title ?? 'Send Push Notification'"
    @click="openSendNotificationDialog"
  />
</template>

<script setup lang="ts">
import { usePanel } from 'kirbyuse';

const props = defineProps<{
  icon?: string | null;
  text?: string | null;
  variant?: string | null;
  size?: string | null;
  title?: string | null;
}>();

const panel = usePanel();

function openSendNotificationDialog() {
  panel.dialog.open('philippoehrlein/kirby-push-notifications/send-notification');
}
</script>