<script setup>
import { ref, watch } from "vue"
import vueFilePond from "vue-filepond"
import "filepond/dist/filepond.min.css"
import { usePage } from "@inertiajs/vue3"

const FilePond = vueFilePond()
const page = usePage()

const props = defineProps({ modelValue: Array })
const emit = defineEmits(["update:modelValue"])

const files = ref([])

const getCsrfToken = () =>
  page.props?.csrf_token ??
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''

const server = {
  process: (fieldName, file, metadata, load, error, progress, abort) => {
    const formData = new FormData()
    formData.append('documents', file, file.name)

    const request = new XMLHttpRequest()
    request.open('POST', '/upload')
    request.setRequestHeader('X-CSRF-TOKEN', getCsrfToken())
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest')

    request.upload.onprogress = (e) => progress(e.lengthComputable, e.loaded, e.total)

    request.onload = () => {
      if (request.status >= 200 && request.status < 300) {
        const serverId = request.responseText.trim().replace(/^"|"$/g, '')
        load(serverId)
      } else {
        error(`Upload failed: ${request.status}`)
      }
    }

    request.onerror = () => error('Network error')
    request.send(formData)
    return { abort: () => { request.abort(); abort() } }
  },

  revert: (uniqueFileId, load, error) => {
    const cleanId = uniqueFileId.trim().replace(/^"|"$/g, '')
    fetch('/upload', {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'text/plain',
      },
      body: cleanId,
    })
      .then(res => res.ok ? load() : error('Revert failed'))
      .catch(() => error('Revert failed'))
  }
}

const handleUpdateFiles = (pondFiles) => {
  emit('update:modelValue', pondFiles)
}
</script>

<template>
  <FilePond
    :server="server"
    name="documents"
    allow-multiple
    @updatefiles="handleUpdateFiles"
  />
</template>