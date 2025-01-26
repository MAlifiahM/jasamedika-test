<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";

const menuOpen = ref(false);
const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
}

const router = useRouter();
const route = useRoute();

const activeTab = ref(route.query.activeTab || "home");

const navigate = (path: string, tab: string) => {
  activeTab.value = tab;
  router.push({ path, query: { activeTab: tab }})
};

const profileMenuOpen = ref(false);
const toggleProfileMenu = () => {
  profileMenuOpen.value = !profileMenuOpen.value;
}

const userName = ref("");

onMounted(() => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) {
    try {
      const user = JSON.parse(storedUser);
      userName.value = user.name || "User";
    } catch (e) {
      console.error("Error parsing user data:", e);
    }
  } else if (storedUser === undefined) {
    userName.value = "User";
  }
})

const logout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push('/')
}
</script>

<template>
  <nav class="bg-white border-b border-gray-200 shadow">
    <div class="container mx-auto px-4 py-2 flex items-center justify-between">
      <div class="hidden md:flex space-x-2">
        <button
            @click="navigate('/home', 'home')"
            :class="activeTab === 'home' ?
            'bg-cyan-500 text-white font-semibold hover:bg-sky-500' :
            'bg-slate-400 text-white hover:bg-sky-500'">
          Home
        </button>
        <button
            @click="navigate('/travel', 'travel')"
            :class="activeTab === 'travel' ?
            'bg-cyan-500 text-white font-semibold hover:bg-sky-500' :
            'bg-slate-400 text-white hover:bg-sky-500'">
          Travel
        </button>
        <button
            @click="navigate('/booking', 'booking')"
            :class="activeTab === 'booking' ?
            'bg-cyan-500 text-white font-semibold hover:bg-sky-500' :
            'bg-slate-400 text-white hover:bg-sky-500'">
          Booking
        </button>
      </div>

      <div class="relative">
        <!-- Profile Button -->
        <button
            @click="toggleProfileMenu"
            class="flex items-center space-x-2 focus:outline-none"
        >
          <img
              src="https://img.icons8.com/windows/96/gender-neutral-user.png"
              alt="User Avatar"
              class="w-10 h-10 rounded-full border-2 border-gray-200"
          />
          <p class="hidden md:block text-gray-800 font-medium">{{ userName }}</p>
        </button>

        <!-- Dropdown Menu -->
        <div
            v-if="profileMenuOpen"
            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50 xl:left-1"
        >
          <ul class="py-1 px-2">
            <!-- Profile -->
            <li>
              <button
                  @click="navigate('/profile', 'profile')"
                  class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900"
              >
                Profile
              </button>
            </li>

            <!-- Logout -->
            <li>
              <button
                  @click="logout"
                  class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900"
              >
                Logout
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>