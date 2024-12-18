declare interface DrupalSettings {
  yelen_chat: {
    current_user: {
      uid: number;
      name: string;
    };
  };
}

declare interface Window {
  drupalSettings: DrupalSettings;
}
