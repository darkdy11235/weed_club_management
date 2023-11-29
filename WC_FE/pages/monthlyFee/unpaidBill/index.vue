<template>
  <NuxtLayout name="custom">
      <div>
          <h1 class="mb-[24px] text-2xl font-bold text-blue-500">List Bill Unpaid</h1>
          <div class="flex flex-col w-full bg-white rounded-lg shadow-md">
              <div class="flex flex-col gap-3 pt-8">
                  <div class="flex justify-end w-full gap-10 px-6">
                      <form action="" method="GET" class="w-[400px]">
                          <div class="relative w-[100%]">
                              <input v-model="searchQuery" type="text" id="search_money" name="search_money"
                                  placeholder="Search"
                                  class="w-full px-12 bg-white border py-[10px] rounded-[4px] focus:outline-none focus:border-blue-500">
                              <font-awesome-icon :icon="['fas', 'search']"
                                  class="absolute top-[50%] left-4 translate-y-[-50%]" />
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </NuxtLayout>
</template>

<script>

export default {

}
</script>

<style scoped></style>