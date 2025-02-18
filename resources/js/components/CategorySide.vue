<template>
    <div class="single-widget">
        <h3>Categories</h3>
        <ul class="list">
            <li v-for="category in categoryData" :key="category.id">
                <RouterLink @click="reloadPage(category.id)" :to="{ name: 'categoryShow', params: { id: category.id }}" :class="{ 'nav-link': true, 'active': isActiveLink({ name: 'categoryShow', params: { id: category.id } }) }">
                    <img :src="getImageUrl(category.icone)" :alt="category.name" width="40px" id="icone">{{ category.name }}
                </RouterLink>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts"> 
import { useRouter, useRoute } from 'vue-router';
import { ref, onMounted } from 'vue'
import { useCategories } from './composables/categoriesApi';
import { RouterLink } from 'vue-router'

const backendUrl = import.meta.env.VITE_BACKEND_URL;
const imageUrl = import.meta.env.VITE_IMAGE_URL

const {categoryData, status} = useCategories()
onMounted(status)

const route = useRoute();
const isActiveLink = (to: { name: string; params: { id: number } }) => {
    return route.name === to.name && route.params.id === String(to.params.id);
}

const reloadPage = (categoryId: any) => {
    // Construire l'URL de la page de destination
    const destinationUrl = `/categories/${categoryId}`;
    // Recharger la page
    window.location.reload();
    // Naviguer vers la page de destination après le rechargement de la page
    window.location.href = destinationUrl;
}

const getImageUrl = (icone: string) => {
    return `${imageUrl}/storage/${icone}`;
  };

</script>