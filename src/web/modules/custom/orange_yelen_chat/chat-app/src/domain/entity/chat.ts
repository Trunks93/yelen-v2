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

export interface Conversation {
  id: number;
  created_by: number;
  participant_id: number;
  status: 'active' | 'closed';
  created: number;
  updated: number;
  other_user: User;
}
