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

defineProps<{
  icon?: string | null;
  text?: string | null;
  variant?: string | null;
  size?: string | null;
  title?: string | null;
}>();

const panel = usePanel();

function openSendNotificationDialog() {
  const uuid = panel.view?.props?.model?.uuid ?? null;
  panel.dialog.open('philippoehrlein/push-notifications/send-notification', {
    query: {
      uuid: uuid ?? null,
    },
  });
}
</script>