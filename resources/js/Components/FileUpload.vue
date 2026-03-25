<script setup>
import { ref, onMounted } from "vue"
import vueFilePond from "vue-filepond"
import "filepond/dist/filepond.min.css"
import { usePage } from "@inertiajs/vue3"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginPdfPreview from "filepond-plugin-pdf-preview"
import "filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"

const FilePond = vueFilePond(
  FilePondPluginImagePreview,
  FilePondPluginFileValidateType,
  FilePondPluginPdfPreview
)

const page = usePage()

const props = defineProps({
  modelValue: Array,
  existingFiles: {
    type: Array,
    default: () => []
  }
})

// Added: existingFileRemoved emit so the parent knows if the original file was deleted
const emit = defineEmits(["update:modelValue", "update:existingFileRemoved"])

const files = ref([])

// Track the sources of files that came from the server (existing files)
const existingFileSources = ref(new Set())

const getCsrfToken = () =>
  page.props?.csrf_token ??
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''

const server = {
  load: (source, load, error) => {
    fetch(source, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken(),
      },
      credentials: 'same-origin',
    })
      .then(res => {
        if (!res.ok) throw new Error('Failed')
        return res.blob()
      })
      .then(blob => load(blob))
      .catch(() => error('Failed to load file'))
  },

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

onMounted(() => {
  if (props.existingFiles?.length) {
    files.value = props.existingFiles.map(file => {
      if (typeof file === 'object' && file.source) {
        // Remember this source so we can detect if it gets removed later
        existingFileSources.value.add(file.source)
        return file
      }

      if (typeof file === 'string') {
        existingFileSources.value.add(file)
        return { source: file, options: { type: 'local' } }
      }

      existingFileSources.value.add(file.url)
      return { source: file.url, options: { type: 'local' } }
    })
  }
})

const handleUpdateFiles = (pondFiles) => {
  emit('update:modelValue', pondFiles)

  const remainingExistingSources = new Set(
    pondFiles
      .filter(f => f.origin === 3)
      .map(f => f.source)
  )

  const existingFileWasRemoved = [...existingFileSources.value].some(
    src => !remainingExistingSources.has(src)
  )

  emit('update:existingFileRemoved', existingFileWasRemoved)
}
</script>

<template>
  <FilePond
    :server="server"
    :files="files"
    name="documents"
    @updatefiles="handleUpdateFiles"
  />
</template>