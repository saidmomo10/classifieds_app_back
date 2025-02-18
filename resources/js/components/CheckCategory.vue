<template>
    <div>
      <div class="single-widget search">
        <h3>Rechercher</h3>
        <form @submit.prevent="applyFilters">
          <input v-model="titleQuery" type="text" placeholder="Search Here..." />
          <button type="button" @click="applyFilters"><i class="lni lni-search-alt"></i></button>
        </form>
      </div>
  
      <div class="single-widget state">
        <h3>État</h3>
        <div class="form-check">
          <input type="checkbox" v-model="newChecked" @change="applyFilters" />
          <label>Neuf</label>
        </div>
        <div class="form-check">
          <input type="checkbox" v-model="usedChecked" @change="applyFilters" />
          <label>Usé</label>
        </div>
      </div>
  
      <div class="single-widget price-mode">
        <h3>Mode de Prix</h3>
        <div class="form-check">
          <input type="checkbox" v-model="fixedPriceChecked" @change="applyFilters" />
          <label>Prix Fixe</label>
        </div>
        <div class="form-check">
          <input type="checkbox" v-model="negotiablePriceChecked" @change="applyFilters" />
          <label>Négociable</label>
        </div>
      </div>
  
      <div class="single-widget range">
        <h3>Price Range</h3>
        <input type="range" min="100" max="1000000" v-model="maxPrice" @change="applyFilters" />
        <div class="range-inner">
          <label>F</label>
          <input type="text" v-model="maxPrice" readonly />
        </div>
      </div>
  
      <button @click="resetFilters" class="btn btn-secondary mt-3">Réinitialiser les filtres</button>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, computed, watch } from 'vue';
  import { useRoute } from 'vue-router';
  import { useCategoryStore } from '../components/stores/categoryStore';
  
  const categoryStore = useCategoryStore();
  const categoryAds = computed(() => categoryStore.categoryAds);
  
  const route = useRoute();
  const subCategoryId = route.params.id || 1;
  
  // Définition des états des filtres
  const titleQuery = ref('');
  const newChecked = ref(false);
  const usedChecked = ref(false);
  const fixedPriceChecked = ref(false);
  const negotiablePriceChecked = ref(false);
  const maxPrice = ref(1000000);
  
  // Fonction pour appliquer les filtres
  const applyFilters = () => {
    const query = {
      title: titleQuery.value,
      state: [],
      price_type: [],
      maxPrice: maxPrice.value,
    };
  
    if (newChecked.value) query.state.push('neuf');
    if (usedChecked.value) query.state.push('usé');
    if (fixedPriceChecked.value) query.price_type.push('fixe');
    if (negotiablePriceChecked.value) query.price_type.push('débattable');
  
    categoryStore.filter(subCategoryId, query);
  };
  
  // Fonction pour réinitialiser les filtres
  const resetFilters = () => {
    titleQuery.value = '';
    newChecked.value = false;
    usedChecked.value = false;
    fixedPriceChecked.value = false;
    negotiablePriceChecked.value = false;
    maxPrice.value = 1000000;
  
    // Réinitialise les annonces en appelant `getAdsCategories` sans filtres
    categoryStore.getAdsCategories(subCategoryId);
  };
  
  // Applique les filtres en temps réel lorsqu'il y a un changement
  watch([titleQuery, newChecked, usedChecked, fixedPriceChecked, negotiablePriceChecked, maxPrice], applyFilters);
  </script>
  