<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const page = usePage()
const sidebarOpen = ref(false);
const profileOpen = ref(false);
const form = useForm({});
const changeRequestOpen = ref(false);

const logout = () => form.post(route('logout'));

const handleClickOutside = (e) => {
  const dropdown = document.getElementById('profile-dropdown');
  const button = document.getElementById('profile-button');
  if (dropdown && button && !dropdown.contains(e.target) && !button.contains(e.target)) {
    profileOpen.value = false;
  }
};

onMounted(() => window.addEventListener('click', handleClickOutside));
onBeforeUnmount(() => window.removeEventListener('click', handleClickOutside));
</script>

<template>
  <div class="flex h-screen bg-slate-100 overflow-hidden">
    
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-30 w-64 shrink-0 overflow-y-auto bg-slate-950 border-r border-slate-800 transition-transform transform lg:translate-x-0 lg:static lg:inset-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <div class="p-6 flex items-center justify-between lg:justify-center">
        <h1 class="text-xl font-bold text-cyan-400">
          <Link :href="route('system-administrator.dashboard')">
            <ApplicationLogo />
          </Link>
        </h1>
        <button class="lg:hidden text-slate-400" @click="sidebarOpen = false">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <nav class="px-6 py-4 space-y-2">
        <Link
          :href="route('system-administrator.dashboard')"
          class="flex items-center text-slate-300 py-2 px-4 rounded-lg hover:bg-cyan-500/10 hover:text-cyan-400 transition duration-200"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v15a1 1 0 0 0 1 1h15M8 16l2.5-5.5 3 3L17.273 7 20 9.667"/>
          </svg>
          Dashboard
        </Link>
      </nav>
    </aside>

    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-20 lg:hidden"
      @click="sidebarOpen = false"
    ></div>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      
      <header class="flex items-center justify-between bg-slate-50 border-b border-slate-200 p-4 shrink-0">
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-600 lg:hidden">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div class="relative ml-auto">
          <button
            id="profile-button"
            @click.stop="profileOpen = !profileOpen"
            class="flex items-center space-x-2 focus:outline-none text-slate-700 hover:text-cyan-600 transition"
          >
            <img
              src="https://ui-avatars.com/api/?name=Admin"
              alt="Profile"
              class="w-8 h-8 rounded-full border-2 border-cyan-500"
            />
            <span class="hidden sm:block font-medium">System Administrator</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <div
            v-if="profileOpen"
            id="profile-dropdown"
            class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50"
          >
            <Link
              :href="route('profile.edit')"
              class="block px-4 py-2 text-slate-700 hover:bg-cyan-50 hover:text-cyan-600 transition"
            >
              Profile
            </Link>
            <button
              @click="logout"
              class="w-full text-left px-4 py-2 text-slate-700 hover:bg-cyan-50 hover:text-cyan-600 transition"
            >
              Logout
            </button>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-slate-100">
        <slot />
      </main>
    </div>
  </div>
</template>


<style>
aside { transition: transform 0.3s ease-in-out; }
</style>