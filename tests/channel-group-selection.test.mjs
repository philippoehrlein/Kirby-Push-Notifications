import assert from 'node:assert/strict';
import { test } from 'node:test';
import { ref } from 'vue';
import { useChannelGroupSelection } from '../src/services/channelGroupSelection.ts';

test('editing either checkbox group preserves the other group’s selections', () => {
  const selectedChannels = ref(['note-approval']);
  const panel = useChannelGroupSelection(selectedChannels, () => [
    { value: 'note-approval', text: 'Note Approval' },
    { value: 'dev-alerts', text: 'Dev Alerts' },
  ]);
  const website = useChannelGroupSelection(selectedChannels, () => [
    { value: 'notes', text: 'Notes' },
  ]);

  assert.deepEqual(panel.value, ['note-approval']);
  assert.deepEqual(website.value, []);

  website.value = ['notes'];
  assert.deepEqual(panel.value, ['note-approval']);
  assert.deepEqual(website.value, ['notes']);
  assert.deepEqual(selectedChannels.value, ['note-approval', 'notes']);

  website.value = [];
  assert.deepEqual(panel.value, ['note-approval']);
  assert.deepEqual(selectedChannels.value, ['note-approval']);

  website.value = ['notes'];
  panel.value = ['dev-alerts'];
  assert.deepEqual(panel.value, ['dev-alerts']);
  assert.deepEqual(website.value, ['notes']);

  panel.value = [];
  assert.deepEqual(panel.value, []);
  assert.deepEqual(website.value, ['notes']);
  assert.deepEqual(selectedChannels.value, ['notes']);
});
