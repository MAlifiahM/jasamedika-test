<template>
  <div
      v-if="isVisible"
      class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-20 backdrop-blur-sm"
  >
    <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-lg">
      <svg
          class="animate-spin h-10 w-10 text-blue-500"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
      >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        ></circle>
        <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 000 8v4a8 8 0 01-8-8z"
        ></path>
      </svg>
      <p class="mt-4 text-gray-700">Loading...</p>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
};
</script>