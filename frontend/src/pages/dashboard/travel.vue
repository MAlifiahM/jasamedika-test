<template>
  <Navbar />
  <Loading :is-visible="isLoading" />
  <div class="mt-5">
    <h2>Travel</h2>
    <div v-if="user?.role === 'admin' && !isCreating" class="flex justify-end mx-50">
      <button
          @click="create"
          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
      >
        Create
      </button>
    </div>

    <div v-if="user?.role === 'admin' && isCreating" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-20 backdrop-blur-sm">
      <div class="flex flex-col p-6 bg-white rounded-lg shadow-lg text-black w-1/2">
        <h2>Create Travel Schedule</h2>
        <form class="mt-5" @submit.prevent="handleSubmitCreate($event)">
          <div class="my-4 flex justify-between items-center">
            <div class="">Departure</div>
            <div>
              <input
                  type="text"
                  name="departure"
                  id="departure"
                  autocomplete="departure"
                  placeholder="departure"
                  required
                  class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
              />
            </div>
          </div>
          <div class="my-4 flex justify-between items-center">
            <div class="">Destination</div>
            <div>
              <input
                  type="text"
                  name="destination"
                  id="destination"
                  autocomplete="destination"
                  placeholder="destination"
                  required
                  class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
              />
            </div>
          </div>
          <div class="my-4 flex justify-between items-center">
            <div class="">Departure time</div>
            <div>
              <input
                  type="text"
                  name="departure_time"
                  id="departure_time"
                  autocomplete="departure_time"
                  placeholder="departure time"
                  required
                  class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
              />
            </div>
          </div>
          <div class="my-4 flex justify-between items-center">
            <div class="">Arrival time</div>
            <div>
              <input
                  type="text"
                  name="arrival_time"
                  id="arrival_time"
                  autocomplete="arrival_time"
                  placeholder="arrival time"
                  required
                  class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
              />
            </div>
          </div>
          <div class="my-4 flex justify-between items-center">
            <div class="">Quota</div>
            <div>
              <input
                  type="text"
                  name="quota"
                  id="quota"
                  autocomplete="quota"
                  placeholder="quota"
                  required
                  class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
              />
            </div>
          </div>
          <div class="my-4 flex justify-between items-center">
            <div class="">Price</div>
            <div>
              <input
                  type="text"
                  name="price"
                  id="price"
                  autocomplete="price"
                  placeholder="price"
                  required
                  class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
              />
            </div>
          </div>

          <div class="flex justify-between mt-5">
            <button
                @click="cancelCreate"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            >
              Cancel
            </button>
            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="!isLoading && travels.length === 0" class="text-center text-gray-500 mt-10">
      No travel schedule available.
    </div>
    <div v-else class="grid grid-cols-auto mx-50 mt-5 text-black">
      <div v-for="(travel, index) in travels" :key="index"
           class="bg-white border-b m-4 p-4 border-gray-200 shadow rounded">
        <div class="flex justify-between p-4 items-center border-b-2 mx-2 mb-2">
          <div class="flex flex-col">
            <div class="">Departure</div>
            <div class="font-bold">{{ travel.departure }}</div>
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
            <div class="font-bold">{{ travel.destination }}</div>
          </div>
        </div>

        <div class="flex flex-col p-4">
          <div class="flex justify-between">
            <div class="">Departure time on</div>
            <div class="font-bold">{{ travel.departure_time }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Arrival time on</div>
            <div class="font-bold">{{ travel.arrival_time }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Quota</div>
            <div class="font-bold">{{ travel.quota }}</div>
          </div>

          <div class="flex justify-between">
            <div class="">Price</div>
            <div class="font-bold">{{ travel.price }} / Ticket</div>
          </div>
        </div>

        <!-- Quantity Selector -->
        <div v-if="state[index].active && state[index].role !== 'admin'" class="flex items-center mt-4 justify-center">
          <button
              @click="decreaseQty(index)"
              :disabled="state[index].qty === 0"
              class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded"
          >
            -
          </button>
          <span class="mx-4 text-lg text-black">{{ state[index].qty }}</span>
          <button
              @click="increaseQty(index, travel.quota)"
              :disabled="state[index].qty >= travel.quota"
              class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded"
          >
            +
          </button>
        </div>

        <!-- Cancel/Confirm Buttons -->
        <div v-if="state[index].active && state[index].role !== 'admin'" class="mt-5 flex justify-between mx-4">
          <button
              @click="cancel(index)"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
          >
            Cancel
          </button>
          <button
              @click="confirm(index)"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Confirm
          </button>
        </div>

        <div v-if="!state[index].active && state[index].role === 'admin'" class="mt-5 flex justify-between mx-4">
          <button
              @click="cancel(index)"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
          >
            Delete
          </button>
          <button
              @click="update(index)"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Update
          </button>
        </div>

        <div v-if="state[index].active && state[index].role === 'admin'" class="mt-5 mx-4 border-t-2">
          <form class="flex flex-col mt-5" @submit.prevent="handleSubmitUpdate(index)">
            <div class="my-2 flex justify-between items-center">
              <div>Departure</div>
              <div>
                <input
                    type="text"
                    name="departure"
                    id="departure"
                    autocomplete="departure"
                    v-model="editTravel.departure"
                    :placeholder="travel.departure"
                    class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
                />
              </div>
            </div>
            <div class="my-2 flex justify-between items-center">
              <div>Destination</div>
              <div>
                <input
                    type="text"
                    name="destination"
                    id="destination"
                    autocomplete="destination"
                    v-model="editTravel.destination"
                    :placeholder="travel.destination"
                    class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
                />
              </div>
            </div>
            <div class="my-2 flex justify-between items-center">
              <div>Departure time</div>
              <div>
                <input
                    type="text"
                    name="departure_time"
                    id="departure_time"
                    autocomplete="departure_time"
                    v-model="editTravel.departure_time"
                    :placeholder="travel.departure_time"
                    class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
                />
              </div>
            </div>
            <div class="my-2 flex justify-between items-center">
              <div>Arrival Time</div>
              <div>
                <input
                    type="text"
                    name="arrival_time"
                    id="arrival_time"
                    autocomplete="arrival_time"
                    v-model="editTravel.arrival_time"
                    :placeholder="travel.arrival_time"
                    class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
                />
              </div>
            </div>
            <div class="my-2 flex justify-between items-center">
              <div>Quota</div>
              <div>
                <input
                    type="number"
                    name="quota"
                    id="quota"
                    autocomplete="quota"
                    v-model="editTravel.quota"
                    :placeholder="travel.quota.toString()"
                    class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
                />
              </div>
            </div>
            <div class="my-2 flex justify-between items-center">
              <div>Price</div>
              <div>
                <input
                    type="number"
                    name="price"
                    id="price"
                    autocomplete="price"
                    v-model="editTravel.price"
                    :placeholder="travel.price.toString()"
                    class="block w-full px-2 rounded-md bg-white
                outline-gray-900 outline-1 py-1.5 -outline-offset-1
                placeholder:text-gray-500 text-black focus:outline-2
                focus:-outline-offset-2 focus:outline-indigo-600
                sm:text-sm/6"
                />
              </div>
            </div>

            <div class="flex justify-between mt-5">
              <button
                  @click="cancelUpdate(index)"
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              >
                Cancel
              </button>
              <button
                  type="submit"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                Submit
              </button>
            </div>
          </form>
        </div>

        <!-- Order Button -->
        <div v-if="!state[index].active && state[index].role !== 'admin'" class="mt-5 flex justify-end mx-4">
          <button
              @click="order(index)"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Order
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from "vue";
import Navbar from "../../components/navbar.vue";
import Loading from "../../components/loading.vue";
import apiClient from './../../plugins/axios';

type Travel = {
  id: number;
  departure: string;
  destination: string;
  departure_time: string;
  arrival_time: string;
  quota: number;
  price: number;
}

type User = {
  id: number;
  name: string;
  email: string;
  role: string;
}

export default {
  components: {
    Navbar,
    Loading,
  },
  setup() {
    const isLoading = ref(false);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const perPage = ref(10);
    const travels = ref<Travel[]>([]);
    const token = ref('');
    const user = ref<User>();
    const state = ref<Array<{ active: boolean, qty: number, schedule_id: number, role: string, }>>([]);
    const editTravel = ref<Travel>({
      id: 0,
      departure: "",
      destination: "",
      departure_time: "",
      arrival_time: "",
      quota: 0,
      price: 0,
    });
    const isCreating = ref(false);

    const fetchData = async () => {
      isLoading.value = true;
      try {
        const tokenData = localStorage.getItem('token');
        if (tokenData) {
          token.value = JSON.parse(tokenData);
          const userData = localStorage.getItem('user');
          if (userData) {
            user.value = JSON.parse(userData);
          }
          const travel = await apiClient.get("/travel", {
            headers: {
              Authorization: `Bearer ${token.value}`,
            }
          });

          if (travel.data.success) {
            travels.value = travel.data.data.travels;
            currentPage.value = travel.data.data.meta.current_page;
            lastPage.value = travel.data.data.meta.last_page;
            perPage.value = travel.data.data.meta.per_page;
            state.value = travels.value.map((val) => ({
              active: false,
              qty: 1,
              role: user.value?.role ?? 'passenger',
              schedule_id: val.id,
            }));
          }
        }
      } catch (e) {
        console.error("Error fetching travel data:", e);
      } finally {
        isLoading.value = false;
      }
    };

    onMounted(fetchData);

    const order = (index: number) => {
      state.value[index].active = true;
    }

    const cancel = (index: number) => {
      state.value[index].active = false;
      state.value[index].qty = 1;
    }

    const increaseQty = (index: number, maxQuota: number) => {
      if (state.value[index].qty < maxQuota) {
        state.value[index].qty += 1;
      }
    };

    const decreaseQty = (index: number) => {
      if (state.value[index].qty > 0) {
        state.value[index].qty -= 1;
      }
    };

    const confirm = async (index: number) => {
      state.value[index].active = false;
      isLoading.value = true;
      try {
        const createOrder = await apiClient.post("/booking", {
          schedule_id: state.value[index].schedule_id,
          qty: state.value[index].qty,
        },
  {
          headers: {
            Authorization: `Bearer ${token.value}`,
          }
        });

        if (createOrder.data.success) {
          alert("success order");
          await fetchData();
        }
      } catch (e) {
        if (typeof e === "object" && e !== null && "status" in e && "response" in e) {
          const error = e as { status: number; response: { data: { message: string } } };
          if (error.status === 400) {
            alert(error.response.data.message);
          }
        } else {
          console.error("An unknown error occurred: ", e);
        }
      } finally {
        isLoading.value = false;
      }
    };

    const update = async (index:number) => {
      state.value[index].active = true;
      editTravel.value = { ...travels.value[index] };
    }

    const cancelUpdate = async (index:number) => {
      state.value[index].active = false;
    }

    const handleSubmitUpdate = async (index:number) => {
      state.value[index].active = false;
      isLoading.value = true;

      try {
        const updatedTravel = editTravel.value;

        const response = await apiClient.put(`/travel/${updatedTravel.id}`, updatedTravel, {
          headers: {
            Authorization: `Bearer ${token.value}`,
          },
        });

        if (response.data.success) {
          alert("Travel updated successfully!");
          await fetchData();
        }
      } catch (e) {
        console.error("Error updating travel details: ", e);
      } finally {
        isLoading.value = false;
      }
    }

    const create = async() => {
      isCreating.value = true;
    }

    const cancelCreate = async() => {
      isCreating.value = false;
    }

    const handleSubmitCreate = async (event: Event) => {
      const submitEvent = event as SubmitEvent;
      isLoading.value = true;

      try {
        submitEvent.preventDefault();

        const form = submitEvent.target as HTMLFormElement;
        const formData = new FormData(form);

        const newTravel: Travel = {
          id: 0,
          departure: formData.get("departure") as string,
          destination: formData.get("destination") as string,
          departure_time: formData.get("departure_time") as string,
          arrival_time: formData.get("arrival_time") as string,
          quota: parseInt(formData.get("quota") as string, 10),
          price: parseFloat(formData.get("price") as string),
        };

        const response = await apiClient.post("/travel", newTravel, {
          headers: {
            Authorization: `Bearer ${token.value}`,
          },
        });

        if (response.data.success) {
          alert("Travel created successfully!");
          isCreating.value = false;
          await fetchData();
        }
      } catch (error) {
        console.error("Error creating travel:", error);
        alert("Failed to create travel. Please try again.");
      } finally {
        isLoading.value = false;
      }
    }

    return {
      isLoading,
      currentPage,
      lastPage,
      perPage,
      travels,
      state,
      user,
      editTravel,
      isCreating,
      order,
      cancel,
      increaseQty,
      decreaseQty,
      confirm,
      update,
      cancelUpdate,
      handleSubmitUpdate,
      create,
      cancelCreate,
      handleSubmitCreate,
    };
  }
}
</script>