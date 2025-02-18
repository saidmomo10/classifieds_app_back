<template>
    <!-- <div>
        <input type="search" v-model="keyword" @input="getResults">
        <ul v-if="ads.length > 0">
        <li v-for="ad in ads" :key="ad.id" v-text="ad.title"></li>
        </ul>
    </div> -->

    <div class="search-form wow fadeInUp" data-wow-delay=".7s">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 p-0">
                <div class="search-input">
                    <label for="keyword"><i class="lni lni-search-alt theme-color"></i></label>
                    <input v-model="keyword" @input="getResults" type="text" name="keyword" id="keyword" placeholder="Rechercher">
                </div>
            </div>
            <ul v-if="ads.length > 0">
                <li v-for="ad in ads" :key="ad.id">
                  <RouterLink :to="{name: 'adShow', params: {id:ad.id}}" class="col-lg-12">
                    <div v-if="ad" class="item-image">
                        <img :src="getImageUrl(ad.images)" alt="#">
                        <div class="content">
                            <h6 class="title"><a href="">{{ ad.title }}</a></h6>
                            <span v-if="ad.subcategory" class="price">{{ ad.subcategory.name }}</span>
                        </div>
                    </div>
                    <div v-else-if="ad == null">
                      paucun
                    </div>
                  </RouterLink>
                </li>
            </ul>
        </div>
    </div>
</template>
  
  <script setup lang="ts">
  import { ref, watch } from 'vue'
  import axios from 'axios'

  const backendUrl = import.meta.env.VITE_BACKEND_URL;
  const imageUrl = import.meta.env.VITE_IMAGE_URL
  
  const token = localStorage.getItem('token')
  const clientHttp = axios.create({
    baseURL: `${backendUrl}/api/`,
    headers: {
      Accept: 'application/json',
      Authorization: `Bearer ${token}`,
    }
  })
  
  const keyword = ref<string | null>(null)
  const ads = ref<any[]>([])
  
  watch(keyword, () => {
    getResults()
  })
  
  const getResults = () => {
    if (keyword.value) {
      clientHttp.get('/live', { params: { keyword: keyword.value } })
        .then(res => ads.value = res.data)
        .catch(error => console.error(error))
    } else {
      ads.value = []
    }
  }

  interface Image {
  path: string;
}

const getImageUrl = (images: Image[]) => {
    if (images && images.length > 0) {
      return `${imageUrl}/storage/` + images[0].path;
    }
    return ''; // Ou une image par défaut si aucune image n'est disponible
  };
  </script>
  