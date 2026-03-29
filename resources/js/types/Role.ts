export interface Role {
  id: number,
  name: string,
  slug: string
}

export type StoreRoleForm = {
  name: string,
  slug: string
};

export type UpdateRoleForm = Role;

export type DeleteRoleForm = {
    id: number;
}