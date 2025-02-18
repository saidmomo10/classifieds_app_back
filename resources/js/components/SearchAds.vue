<template>
    <div class="single-widget search">
        <h3>Rechercher</h3>
        <form action="">
            <input v-model="query" @input="search" type="text" placeholder="Search Here...">
            <button @click="search" type="button"><i class="lni lni-search-alt"></i></button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAdStore } from './stores/adStore.js'

const adStore = useAdStore()

const query = ref<string>('') // Définir le type de query comme une chaîne de caractères
const search = () => {
    adStore.filter(query.value)
}
console.log(query.value);

</script>
