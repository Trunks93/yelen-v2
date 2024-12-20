export interface User {
  uid: number;
  name: string;
}

export interface Message {
  id: number;
  conversation_id: number;
  user_id: number;
  username: string;
  message: string;
  created: number;
  read: number;
}

export type ConversationStatus = 'active' | 'closed'

export interface Conversation {
  id: number;
  created_by: number;
  participant_id: number;
  status: ConversationStatus;
  created: number;
  updated: number;
  other_user: User;
}
