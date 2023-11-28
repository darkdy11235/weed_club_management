<template>
    <div class="  bottom-0 w-full text-center  bg-primary d-flex flex-column">
        <div>
            <v-btn v-for="icon in icons" :key="icon" class="mx-4" :icon="icon" variant="text"></v-btn>
        </div>
   
        <div class="px-5 pt-0 mx-auto text-surface ">
           Management 
        </div>

        <v-divider></v-divider>

        <div>
            {{ new Date().getFullYear() }} — <strong>Vuetify</strong>
        </div>
    </div>
</template>
<script setup>

const icons = ref([
    'mdi-facebook',
    'mdi-twitter',
    'mdi-linkedin',
    'mdi-instagram',
],)
   
 
</script>