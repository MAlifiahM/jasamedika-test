<template>
  <Navbar />
  <Loading :is-visible="isLoading" />
  <div class="mt-5">
    <h2>Booking</h2>
    <div v-if="!isLoading && bookings.length === 0" class="text-center text-gray-500 mt-10">
      No booking available
    </div>
    <div v-else class="grid grid-cols-auto mx-50 mt-5 text-black">
      <div v-for="(booking, index) in bookings" :key="index"
        class="bg-white border-b m-4 border-gray--200 shadow rounded">
        <div class="flex justify-between p-4 items-center border-b-2 mx-2 mb-2">
          <div class="flex flex-col">
            <div class="">Departure</div>
            <div class="font-bold">{{ booking.schedule.departure }}</div>
          </div>

          <div class="px-5">
            <img
                src="https://www.svgrepo.com/show/510165/right-arrow.svg"
                alt="Right Arrow"
                class="w-10 h-10 rounded-full border-2 border-gray-200"
            />
          </div>

          <div class="flex flex-col">
            <div class="">Arrival</div>
            <div class="font-bold">{{ booking.schedule.destination }}</div>
          </div>
        </div>

        <div class="flex flex-col p-4">
          <div class="flex justify-between">
            <div class="">Departure time on</div>
            <div class="font-bold">{{ booking.schedule.departure_time }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Arrival time on</div>
            <div class="font-bold">{{ booking.schedule.arrival_time }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Qty</div>
            <div class="font-bold">{{ booking.qty }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Total Price</div>
            <div class="font-bold">{{ booking.schedule.price * booking.qty }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Status</div>
            <div class="font-bold">{{ booking.status }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Booking Date</div>
            <div class="font-bold">{{ formatDate(booking.created_at) }}</div>
          </div>
        </div>

        <div v-if="state[index].status === 'pending' && state[index].role !== 'admin' && state[index].isGoingPay == false" class="mt-5 flex justify-between mx-4">
          <button
              @click="cancel(index)"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Cancel
          </button>

          <button
              @click="pay(index)"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Pay
          </button>
        </div>
        <div v-if="state[index].status === 'pending' && state[index].role !== 'admin' && state[index].isGoingPay == true" class="mt-5 flex justify-end mx-4">
          <form @submit.prevent="handleProof(index)" class="flex">
            <input
                type="file"
                name="payment_proof"
                id="payment_proof"
                class="block w-full text-sm text-gray-900 border-gray-300 rounded-lg cursor-pointer bg-gray-200 focus:outline-none focus:ring focus:ring-blue-300 p-2 mr-4"
            />

          <button
              type="submit"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Submit
          </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {onMounted, ref} from "vue";
import Navbar from "../../components/navbar.vue";
import apiClient from './../../plugins/axios';
import Loading from "../../components/loading.vue";

type Payment = {
  id: number;
  booking_id: number;
  amount: number;
  payment_proof: string;
}

type Schedule = {
  id: number;
  departure: string;
  destination: string;
  departure_time: string;
  arrival_time: string;
  price: number;
  quota: number;
}

type Booking = {
  id: number;
  user_id: number;
  schedule_id: number;
  status: string;
  qty: number;
  payment: Payment;
  schedule: Schedule;
  created_at: string;
}

type User = {
  id: number;
  name: string;
  email: string;
  role: string;
  created_at: string;
  updated_at: string;
}

export default {
  components: {Loading, Navbar },
  setup() {
    const isLoading = ref(false);
    const bookings = ref<Booking[]>([]);
    const token = ref('');
    const user = ref<User>();
    const state = ref<Array<{ status: string; payment_id: number, booking_id: number, isGoingPay: boolean, role: string }>>([]);

    const fetchData = async () => {
      isLoading.value = true;
      try {
        const tokenData = localStorage.getItem('token');
        if (tokenData) {
          token.value = JSON.parse(tokenData);
          const userData = localStorage.getItem('user');
          if (userData) {
            user.value = JSON.parse(userData)
          }
          const booking = await apiClient.get("/booking", {
              headers: {
                Authorization: `Bearer ${token.value}`,
              }
          });

          if (booking.data.success) {
            bookings.value = booking.data.data.bookings;
            state.value = bookings.value.map((val) => ({
              status: val.status,
              payment_id: val.payment.id,
              booking_id: val.id,
              isGoingPay: false,
              role: user.value?.role ?? 'passenger',
            }))
          }
        }
      } catch (e) {
        console.log("Error fetching booking data:", e);
      } finally {
        isLoading.value = false;
      }
    }

    onMounted(fetchData);

    const formatDate = (dateString: string): string => {
      const options: Intl.DateTimeFormatOptions = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZoneName: "short",
      };
      return new Date(dateString).toLocaleDateString("en-US", options);
    };

    const cancel = async (index: number) => {
      isLoading.value = true;
      try {
        const cancelling = await apiClient.put('/booking/' + state.value[index].booking_id,
            {
              status: 'cancelled'
            },
            {
              headers: {
                Authorization: `Bearer ${token.value}`,
              }
            });

        if (cancelling.data.success) {
          alert("success cancelling order");
          await fetchData();
        }
      } catch (e) {
        console.log("Error cancelling order:", e);
      } finally {
        isLoading.value = false;
      }
    }

    const pay = async (index: number) => {
      state.value[index].isGoingPay = true;
    }

    const handleProof = async (index: number) => {
      const fileInput = document.getElementById("payment_proof") as HTMLInputElement;
      if (fileInput?.files && fileInput.files[0]) {
        const file = fileInput.files[0];

        // Log the file name and other details
        console.log("File selected: ", file.name);
        console.log("File size (bytes): ", file.size);
        console.log("File type: ", file.type);

        // Simulate file upload
        const formData = new FormData();
        formData.append("payment_proof", file);

        try {
          isLoading.value = true;

          const response = await apiClient.post("/payment/" + state.value[index].payment_id, formData, {
            headers: {
              Authorization: `Bearer ${token.value}`,
              "Content-Type": "multipart/form-data",
            },
          });

          if (response.data.success) {
            alert("Payment proof uploaded successfully!");
            await fetchData();
          } else {
            console.log("Failed to upload payment proof:", response.data.message);
          }
        } catch (e) {
          console.error("Error uploading payment proof:", e);
        } finally {
          isLoading.value = false;
        }
      } else {
        console.log("No file selected");
        alert("Please select a file before submitting.");
      }
    }

    return {
      isLoading,
      bookings,
      formatDate,
      state,
      cancel,
      pay,
      handleProof,
    }
  }
}
</script>