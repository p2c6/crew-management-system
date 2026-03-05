import type { Component } from 'vue'

export type BaseAction = {
  icon?: Component
  label: string
}

export type LinkAction = BaseAction & {
  type: 'link'
  url: string
}

export type ComponentAction = BaseAction & {
  type: 'component'
  component: Component
}

export type AllowedAction = LinkAction | ComponentAction

export type ColumnDef = {
  accessorKey: string
  label: string
  visibility?: 'hidden' | 'visible'
  allowedActions?: AllowedAction[]
}