import { h } from 'vue'
import { Button } from '@/Components/ui/button'
import { ArrowUpDown } from 'lucide-vue-next'

export const documentColumns = [
  {
    accessorKey: 'id',
    header: () => h('div', { class: 'text-center' }, '#'),
    cell: ({ row }) => h('div', { class: 'text-center text-muted-foreground' }, row.getValue('id')),
  },
  {
    accessorKey: 'name',
    header: ({ column }) => h(Button, {
      variant: 'ghost',
      onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
    }, () => ['Document Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
    cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('name')),
  },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: ({ row }) => {
      const status = row.getValue('status')
      const colors = {
        'Active': 'bg-green-100 text-green-800',
        'Expiring Soon': 'bg-yellow-100 text-yellow-800',
        'Expired': 'bg-red-100 text-red-800',
      }
      return h('span', { class: `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${colors[status] ?? 'bg-gray-100 text-gray-800'}` }, status)
    }
  }
]