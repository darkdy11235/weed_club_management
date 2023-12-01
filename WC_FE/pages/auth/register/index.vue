<template>
  <section class="flex items-center justify-center h-screen bg-background">
    <div class="w-full max-w-md">
      <a
        href="#"
        class="flex items-center justify-center mb-6 text-2xl font-semibold text-primary"
      >
        <img class="w-8 h-8 mr-2" src="../../public/losdac.png" alt="logo" />
        S-Manage
      </a>
      <div class="rounded-lg shadow-2xl bg-surface">
        <div class="p-6 space-y-4">
          <h1
            class="text-2xl font-bold leading-tight tracking-tight text-center text-gray-900"
          >
            Sign up
          </h1>

          <form @submit="register()" class="space-y-4" action="#">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label
                  for="email"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Name</label
                >
                <input
                  type="email"
                  name="email"
                  id="email"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600 text-secondary"
                  v-model="name"
                  placeholder="Name"
                  required
                />
              </div>

              <div>
                <label
                  for="email"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Age</label
                >
                <input
                  v-model="age"
                  type="number"
                  class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500"
                  required
                />
              </div>
              <div>
                <label
                  for="username"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Username</label
                >
                <input
                  type="text"
                  name="username"
                  id="username"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600 text-secondary"
                  v-model="username"
                  placeholder="Username"
                  required
                />
              </div>
              <div>
                <label
                  for="email"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Gender</label
                >
                <select
                  v-model="gender"
                  class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500"
                >
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="other">Khác</option>
                </select>
              </div>
              <div>
                <label
                  for="email"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Email</label
                >
                <input
                  type="email"
                  name="email"
                  id="email"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600 text-secondary"
                  v-model="email"
                  placeholder="Email"
                  required
                />
              </div>
              <div>
                <label
                  for="phone"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Phone</label
                >
                <input
                  type="text"
                  name="phone"
                  id="phone"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600 text-secondary"
                  v-model="phone"
                  placeholder="Phone"
                  required
                />
              </div>
              <div>
                <label
                  for="password"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Password</label
                >
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="••••••••"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                  required
                  v-model="password"
                />
              </div>
              <div>
                <label
                  for="confirmPassword"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Confirm Password</label
                >
                <input
                  type="password"
                  name="confirmPassword"
                  id="confirmPassword"
                  placeholder="••••••••"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                  required
                  v-model="confirmPassword"
                />
              </div>
              <div class="col-span-2">
                <label
                  for="address"
                  class="block mb-2 text-sm font-medium text-secondary"
                  >Address</label
                >
                <input
                  type="text"
                  name="address"
                  id="address"
                  class="w-full p-2.5 rounded-lg focus:ring-primary-600 focus:border-primary-600 text-secondary"
                  v-model="address"
                  placeholder="Address"
                  required
                />
              </div>
            </div>

            <v-btn
              @click="register"
              block
              variant="outlined"
              size="x-large"
              class="mb-4 bg-primary"
            >
              Sign Up
            </v-btn>
          </form>
        </div>
      </div>
    </div>
    <div v-if="isLoading" class="loading-overlay">
      <div class="loader"></div>
    </div>
  </section>
</template>

<script setup>
import { axios } from '../../../utils/api/axios'
import { useToast } from 'vue-toastification'
const toast = useToast()

const router = useRouter()

const username = ref('')
const password = ref('')
const confirmPassword = ref('')
const email = ref('')
const address = ref('')
const name = ref('')
const age = ref('')
const phone = ref('')
const gender = ref('')
const isLoading = ref(false);

const register = async () => {
  try {
    isLoading.value = true
    const response = await axios.post(`/register`, {
      name: username.value,
      password: password.value,
      name: name.value,
      gender: gender.value,
      email: email.value,
      age: parseInt(age.value),
      address: address.value,
      phone: phone.value,
      password_confirmation: confirmPassword.value,
    })
    console.log('response', response)
    if (response.data) {
      isLoading.value = false
      toast.success('Verification Code sent successfully, please check your email')
      router.push(`/auth/emailConfirmation/${email.value}`)
    }
  } catch (error) {
    console.log(error)
    toast.error(error.message)
  }
}
</script>

<style scoped>
input {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.loading-overlay {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10000;
}

.loader {
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #3498db;
  width: 30px;
  height: 30px;
  -webkit-animation: spin 2s linear infinite;
  /* Safari */
  animation: spin 2s linear infinite;
}
</style>
