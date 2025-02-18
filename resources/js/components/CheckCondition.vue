<template>
    <div>
        <div class="single-widget search">
            <h3>Rechercher</h3>
            <form action="">
                <input v-model="titleQuery" @input="applyFilters" type="text" placeholder="Search Here...">
                <button @click.prevent="applyFilters" type="button"><i class="lni lni-search-alt"></i></button>
            </form>
        </div>
        <div class="single-widget state">
            <h3>État</h3>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="newChecked" @change="applyFilters">
                <label class="form-check-label">Neuf</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="usedChecked" @change="applyFilters">
                <label class="form-check-label">Usé</label>
            </div>
        </div>

        <div class="single-widget price-mode">
            <h3>Mode de Prix</h3>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="fixedPriceChecked" @change="applyFilters">
                <label class="form-check-label">Prix Fixe</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="negotiablePriceChecked" @change="applyFilters">
                <label class="form-check-label">Négociable</label>
            </div>
        </div>

        <div class="single-widget range">
            <h3>Price Range</h3>
            <input type="range" class="form-range" min="100" max="1000000" v-model="maxPrice" @change="applyFilters">
            <div class="range-inner">
                <label>F</label>
                <input type="text" v-model="maxPrice" readonly />
            </div>
            <!-- Bouton de réinitialisation -->
            <button @click="resetFilters" class="btn btn-secondary mt-3">Réinitialiser</button>
        </div>

    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { useAdStore } from './stores/adStore';

const adStore = useAdStore();

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

    adStore.filter(query);
};

// Fonction pour réinitialiser les filtres
const resetFilters = () => {
    titleQuery.value = '';
    newChecked.value = false;
    usedChecked.value = false;
    fixedPriceChecked.value = false;
    negotiablePriceChecked.value = false;
    maxPrice.value = 1000000;

    // Réinitialise les annonces en appelant `getAds` pour obtenir toutes les annonces sans filtres
    adStore.getAds();
};

// Applique les filtres en temps réel lorsqu'il y a un changement
watch([newChecked, usedChecked, fixedPriceChecked, negotiablePriceChecked, maxPrice], applyFilters);
</script>

<style scoped>
/* Ajoutez votre style personnalisé ici */
</style>
