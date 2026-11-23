import type { Ref } from 'vue';
import type { Channel } from '../types/Channel';
import { computed } from 'vue';

export function useChannelGroupSelection(
  selectedChannels: Ref<string[]>,
  channels: () => Channel[] | undefined,
) {
  const values = computed(() => new Set((channels() ?? []).map(channel => channel.value)));

  return computed<string[]>({
    get: () => selectedChannels.value.filter(value => values.value.has(value)),
    set: (selection) => {
      const otherChannels = selectedChannels.value.filter(value => !values.value.has(value));
      selectedChannels.value = [...otherChannels, ...selection.filter(value => values.value.has(value))];
    },
  });
}
