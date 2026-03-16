export interface Channel {
  value: string;
  text: string;
  info?: string;
}

export interface Channels {
  panel: Channel[];
  website: Channel[];
}
