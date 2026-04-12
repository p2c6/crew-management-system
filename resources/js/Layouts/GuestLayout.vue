<script lang="ts">
export const description = "A two column login page with a cover image."
</script>
<script setup lang="ts">
import { GalleryVerticalEnd } from "lucide-vue-next"
</script>

<template>
 <div class="grid min-h-svh lg:grid-cols-2">
    <div class="flex flex-col gap-4 p-6 md:p-10">
      <div class="flex justify-center gap-2 md:justify-start">
        <a href="#" class="flex items-center gap-3 font-medium">
          <img
            src="../../../public/images/logo_256.png"
            alt="Logo"
            class="h-12 w-auto md:h-16 lg:h-20 object-contain"
          >
          SeaLink Crewing Inc.
        </a>
      </div>
      <div class="flex flex-1 items-center justify-center">
        <slot />
      </div>
    </div>
    <div class="bg-muted relative hidden lg:block">
      <img
        src="../../../public/images/hero2.jpg"
        alt="Image"
        class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
      >
    </div>
  </div>
</template>
