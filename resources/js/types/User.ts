import { Role } from "./Role";

export type User = {
  id: number;
  full_name: string;
  role: Role;
  email: string;
  password: string;
}

export type StoreUserForm = Omit<User, 'role' | 'id'> & {
  role_id: number | string;
};

export type EditUserForm = Omit<User, 'role'> & {
  role_id: number | string;
};

export type DeleteUserForm = {
  id: number;
}