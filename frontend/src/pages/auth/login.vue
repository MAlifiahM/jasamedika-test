<template>
  <Loading :isVisible="isLoading" />
  <div class="flex items-center justify-center flex-col bg-white p-5 mt-[50%] md:mt-[10%] mx-5 md:mx-25 lg:mx-50 xl:mx-96 items-center border-2 rounded-2xl">
    <div class="text-black">
      <h2 class="font-bold">Sign in to your account</h2>
    </div>

    <div class="w-full md:w-1/2">
      <form @submit.prevent="handleLogin">
        <div class="my-5 text-left">
          <label for="email" class="text-black font-medium text-sm/6">Email address</label>
          <div class="mt-2">
            <input
                type="email"
                name="email"
                id="email"
                v-model="form.email"
                autocomplete="email"
                required
                class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-black text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
            />
          </div>
        </div>

        <div class="mb-5">
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
            <div class="text-sm">
              <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
            </div>
          </div>

          <div class="mt-2">
            <input
                type="password"
                name="password"
                id="password"
                v-model="form.password"
                autocomplete="current-password"
                required
                class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
            />
          </div>
        </div>

        <div>
          <button
              type="submit"
              class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Sign in
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Not a member?
        <a href="/register" class="font-semibold text-indigo-600 hover:text-indigo-500">Register</a>
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from './../../plugins/axios';
import Loading from "../../components/loading.vue";

export default {
  components: {Loading},
  setup() {
    const router = useRouter();
    const form = ref({
      email: '',
      password: '',
    });
    const isLoading = ref(false);

    const handleLogin = async () => {
      isLoading.value = true;
      try {
        const loginData = await apiClient.post('/login', form.value);
        if (loginData.data.success) {
          localStorage.setItem('user', JSON.stringify(loginData.data.data.user));
          localStorage.setItem('token', JSON.stringify(loginData.data.data.token));
          await router.push('/home');
        }
      } catch (error) {
        alert('Login failed!');
      } finally {
        isLoading.value = false;
      }
    };

    return {form, handleLogin, isLoading};
  },
};
</script>

<style>
/* Add your styles here */
</style>