<template>
    <div class="category-slider">
      <RouterLink :to="{ name: 'categoryShow', params: { id: category.id }}" v-for="category in categories" :key="category.id" class="single-cat">
        <div class="icon">
          <img :src="getImageUrl(category.icone)" :alt="category.name">
        </div>
        <h3>{{ category.name }}</h3>
        <h5 class="total">{{ category.ads_count }}</h5>
      </RouterLink>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted, nextTick } from 'vue';
  import axios from 'axios';
  import { tns } from 'tiny-slider/src/tiny-slider';

  const backendUrl = import.meta.env.VITE_BACKEND_URL;
  const imageUrl = import.meta.env.VITE_IMAGE_URL
  
  const token = localStorage.getItem('token');
  const categories = ref<any[]>([]);
  
  const clientHttp = axios.create({
    baseURL: `${backendUrl}/api/`,
    headers: {
      Accept: 'application/json',
      Authorization: `Bearer ${token}`,
    },
  });
  
  const fetchCategories = () => {
    clientHttp.get('subcategories')
      .then(response => {
        categories.value = response.data;
        nextTick(() => {
          initSlider();
        });
      })
      .catch(error => {
        console.error('Error fetching categories:', error);
      });
  };
  
  const initSlider = () => {
    tns({
      container: '.category-slider',
      items: 3,
      slideBy: 'page',
      autoplay: false,
      mouseDrag: true,
      gutter: 0,
      nav: false,
      controls: true,
      controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
      responsive: {
        0: { items: 1 },
        540: { items: 2 },
        768: { items: 4 },
        992: { items: 5 },
        1170: { items: 6 },
      },
    });
  };
  
  const getImageUrl = (icone: string) => {
    return `${imageUrl}/storage/${icone}`;
  };
  
  onMounted(fetchCategories);
  </script>
  