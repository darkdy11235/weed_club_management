<script setup>
import { useDecodeTokenStore } from '#imports';

const decoded = useDecodeTokenStore()

decoded.decodeToken

console.log(decoded.decoded)

</script>

<template>
  <NuxtLayout name="custom">
    <div>
      this is home page
    </div>
  </NuxtLayout>
</template>

<style scoped></style>
