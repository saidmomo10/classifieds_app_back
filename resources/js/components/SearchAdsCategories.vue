<template>
    <div class="col-4" id="input">
        <h3>Rechercher</h3>
        <form action="">
            <input v-model="query" @input="search" type="text" placeholder="Search Here...">
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useCategoryStore } from './stores/categoryStore.js'

const categoryStore = useCategoryStore()

const query = ref<string>('') // Définir le type de query comme une chaîne de caractères
const search = () => {
    categoryStore.filter(query.value)
}
console.log(query.value);

</script>

<style>
#input input{
  height: 50px;
  width: 100%;
  border: 1px solid #eee;
  border-radius: 6px;
  color: #081828;
  padding: 0px 15px;
  padding-right: 50px;
}
</style>
