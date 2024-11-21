declare interface DrupalSettings {
  yelen_chat: {
    currentUser: {
      uid: number;
      name: string;
    };
  };
}

declare interface Window {
  drupalSettings: DrupalSettings;
}
