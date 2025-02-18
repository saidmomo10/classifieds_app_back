<template>
    <div>
      <h2>Panier</h2>
      <div v-if="cartItems.length === 0">Votre panier est vide.</div>
      <div v-else>
        <div v-for="item in cartItems" :key="item.id">
          <h3>{{ item.name }} ({{ item.quantity }})</h3>
          <p>{{ item.price }} €</p>
          <button @click="removeFromCart(item.id)">Retirer</button>
        </div>
        <button @click="clearCart">Vider le panier</button>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { computed } from 'vue';
  import { useCartStore } from '@/stores/cart';
  
  export default {
    setup() {
      const cartStore = useCartStore();
  
      const cartItems = computed(() => cartStore.cartItems);
      const removeFromCart = (productId) => cartStore.removeFromCart(productId);
      const clearCart = () => cartStore.clearCart();
  
      return {
        cartItems,
        removeFromCart,
        clearCart,
      };
    },
  };
  </script>
  