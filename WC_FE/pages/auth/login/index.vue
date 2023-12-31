<script setup>
import { useToast } from 'vue-toastification'
import { axios } from '../../../utils/api/axios'
import { localStorageClient } from '~/utils/helper/localStorage'
import { userInfo } from '@/stores/userInfo.js'
const toast = useToast()

const username = ref('')
const password = ref('')
const router = useRouter()
const userInfoStore = userInfo()

const login = async () => {
  const { status, data } = await axios.post('/login', {
    email: username.value,
    password: password.value,
  })

  if (status === 200 && data) {
    localStorageClient.setItem('accessToken', data.token)
    const response = await axios.get('/user')
    const { user } = response.data
    userInfoStore.userInfo = user
    if (user) {
      router.push('/home')
    }
  }
}

const checkToken = async () => {
  const accessToken = localStorage.getItem('accessToken')
  if (accessToken) {
    window.location.href = router.resolve('/home').href
  }
}

onMounted(async () => {
  await checkToken()
})
</script>

<template>
  <section class="flex items-center justify-center h-screen bg-background">
    <div class="w-full max-w-md">
      <a
        href="#"
        class="flex items-center justify-center mb-6 text-2xl font-semibold text-primary"
      >
        <img class="w-8 h-8 mr-2" src="../public/losdac.png" alt="logo" />
        S-Manage
      </a>
      <div class="shadow-2xl rounded-lg bg-surface">
        <div class="p-6 space-y-4">
          <h1
            class="text-2xl font-bold leading-tight tracking-tight text-center text-gray-900"
          >
            Sign in to your account
          </h1>

          <form @submit="login" class="space-y-4" action="#">
            <div class="form-group">
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
                placeholder="Email"
                required
                v-model="username"
              />
            </div>
            <div class="form-group">
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
                v-model="password"
                required
              />
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="remember"
                    aria-describedby="remember"
                    type="checkbox"
                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                    required
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="remember" class="text-secondary"
                    >Remember me</label
                  >
                </div>
              </div>
              <a
                href="#"
                class="text-sm font-medium text-primary hover:underline dark:text-primary-500"
              >
                <NuxtLink to="/forgotPassword">Forgot password</NuxtLink>
              </a>
            </div>
            <v-btn
              block
              variant="outlined"
              size="x-large"
              class="mb-4 bg-primary"
              @click="login"
            >
              Sign In
            </v-btn>
            <p class="text-sm font-light text-secondary">
              Don’t have an account yet?
              <a
                href="#"
                class="font-medium text-primary hover:underline dark:text-primary-500"
              >
                <NuxtLink to="/auth/register">Sign up </NuxtLink>
              </a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.form-group {
  margin-bottom: 1rem;
}

label {
  margin-bottom: 0.5rem;
}

input {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>
