<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"
import { Button } from "@/Components/ui/button"
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from "@/Components/ui/field"
import { Input } from "@/Components/ui/input"
import { useForm } from "@inertiajs/vue3"
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';


const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <form  @submit.prevent="submit" :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">
          Login to your account
        </h1>
        <p class="text-muted-foreground text-sm text-balance">
          Enter your email below to login to your account
        </p>
      </div>
      <Field>
        <FieldLabel for="email">
          Email
        </FieldLabel>

        <InputLabel for="email" value="Email" />

        <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </Field>
      <Field>
        <div class="flex items-center">
          <FieldLabel for="password">
            Password
          </FieldLabel>
        </div>

        <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </Field>
      <Field>
        <Button type="submit">
          Login
        </Button>
      </Field>

    </FieldGroup>
  </form>
</template>
