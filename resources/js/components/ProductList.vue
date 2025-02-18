<template>
    <div>
      <h2>Produits</h2>
      <div v-for="product in products" :key="product.id">
        <h3>{{ product.name }}</h3>
        <p>{{ product.description }}</p>
        <p>{{ product.price }} €</p>
        <img :src="product.image" alt="product.name" />
        <button @click="addToCart(product)">Ajouter au panier</button>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useCartStore } from '@/stores/cart';
  
  export default {
    setup() {
      const cartStore = useCartStore();
      const products = ref([]);
  
      onMounted(async () => {
        try {
          const response = await axios.get('/products.json');
          products.value = response.data;
        } catch (error) {
          console.error('Erreur lors du chargement des produits:', error);
        }
      });
  
      const addToCart = (product) => {
        cartStore.addToCart(product);
      };
  
      return {
        products,
        addToCart,
      };
    },
  };
  </script>
  