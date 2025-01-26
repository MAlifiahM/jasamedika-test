<template>
  <Navbar />
  <div class="mt-[10%] mx-20 bg-white border-b border-gray-200 shadow rounded text-black">
    <h2 class="">Profile</h2>
    <div class="flex mt-4 justify-center p-20">
      <div class="mr-5">
          <div class="name flex flex-col justify-start text-start">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <div class="mt-1">
              <input
                  type="text"
                  name="name"
                  id="name"
                  autocomplete="name"
                  :placeholder="userName"
                  disabled
                  class="block w-full px-2 rounded-md bg-white
                  outline-gray-900 outline-1 py-1.5 -outline-offset-1
                  placeholder:text-gray-500 text-black focus:outline-2
                  focus:-outline-offset-2 focus:outline-indigo-600
                  sm:text-sm/6"
              />
            </div>
          </div>
      </div>

      <div class="">
        <div class="email flex flex-col justify-start text-start">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <div class="mt-1">
            <input
                type="email"
                name="email"
                id="email"
                autocomplete="email"
                :placeholder="userEmail"
                disabled
                class="block w-full px-2 rounded-md bg-white
                  outline-gray-900 outline-1 py-1.5 -outline-offset-1
                  placeholder:text-gray-500 text-black focus:outline-2
                  focus:-outline-offset-2 focus:outline-indigo-600
                  sm:text-sm/6"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from "vue";
import Navbar from "../../components/navbar.vue";

export default {
  components: { Navbar },
  setup() {
    const userName = ref("");
    const userEmail = ref("");

    onMounted(() => {
      const storedUser = localStorage.getItem("user");
      if (storedUser) {
        try {
          const user = JSON.parse(storedUser);
          userName.value = user.name || "User";
          userEmail.value = user.email || "User@example.com";
        } catch (e) {
          console.error("Error parsing user data:", e);
        }
      } else if (storedUser === undefined) {
        userName.value = "User";
        userEmail.value = "User@example.com";
      }
    });

    return {
      userName,
      userEmail
    }
  }
}
</script>