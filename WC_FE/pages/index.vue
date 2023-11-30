<script setup>
import { localStorageClient } from '~/utils/helper/localStorage'
const router = useRouter()

onMounted(() => {
  const token = localStorageClient.getItem('accessToken')
  if (!token) {
    router.push('/auth/login')
  }
})
</script>

<template></template>

<style scoped></style>
