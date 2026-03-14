export type PushSupportState =
  | { supported: true }
  | { supported: false; reason: string };
