<template>
  <div class="step-one-content">
    <form class="default-form-style" @submit.prevent="validateAndProceed">
      <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label>Titre de l'annonce*</label>
                <input name="title" type="text" placeholder="Titre" v-model="props.formValues.title">
                <span v-if="errors.title" class="error-message">{{ errors.title }}</span>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label>Categorie*</label>
                <div class="selector-head">
                    <span class="arrow"><i class="lni lni-chevron-down"></i></span>
                    <select class="user-chosen-select" v-model="props.formValues.subcategory_id">
                        <option value="">Selectionner</option>
                        <option v-for="category in statusData" :key="category.id" :value="`${category.id}`">{{ category.name }}</option>
                    </select>
                    <span v-if="errors.subcategory_id" class="error-message">{{ errors.subcategory_id }}</span>
                  </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label>Mode de prix*</label>
                <div class="selector-head">
                    <span class="arrow"><i class="lni lni-chevron-down"></i></span>
                    <select v-model="props.formValues.price_type" class="user-chosen-select">
                        <option value="">Selectionner</option>
                        <option value="Fixe">Fixe</option>
                        <option value="Débattable">Débattable</option>
                    </select>
                    <span v-if="errors.price_type" class="error-message">{{ errors.price_type }}</span>
                </div>
            </div>
        </div>
        <div v-if="props.formValues.price_type == 'Fixe'" class="col-12">
            <div class="form-group">
                <label>Prix*</label>
                <input name="price" type="number" placeholder="Enter Price" v-model="props.formValues.price">
                <span v-if="errors.price" class="error-message">{{ errors.price }}</span>
            </div>
        </div>

        <input type="hidden" name="user_id" v-model="props.formValues.user_id">
        <input type="hidden" name="user_subscription_id" v-model="props.formValues.user_subscription_id">

        <div class="col-lg-6 col-12">
            <div class="upload-input">
                <input :v-model="props.formValues.images" type="file" id="upload" name="images[]" multiple @change="handleImageUpload">
                <label for="upload" class="text-center content">
                    <span class="text">
                      <span class=" mb-15 plus-icon"><i
                              class="lni lni-plus"></i></span>
                      <span class="main-btn d-block btn-hover">Selectionner des images</span>
                      <span class="d-block">Taille maximum : 10Mb</span>
                      <span v-if="userData.pivot">
                          <span v-if="userData.pivot.status == 'Abonnement actif'">
                              <p>{{ userData.max_ads }} images maximum</p>
                          </span>
                      </span>
                    </span>
                </label>
            </div>
        </div>
        <span v-if="errors.images" class="error-message">{{ errors.images }}</span>
            

        <div class="col-lg-6 col-12">
            <p v-if="props.formValues.images && props.formValues.images.length > 0">Images sélectionnées :</p>
            <div class="row" v-for="image in props.formValues.images" :key="image.name">
                <img :src="getImageUrl(image)" alt="Selected image" style="max-width: 100px; max-height: 200px; margin-bottom: 10px;">
            </div>
        </div>

        <div v-if="userData.pivot">
            <div v-if="userData.pivot.status == 'Abonnement actif'" class="col-12">
            <div class="form-group">
                <label class="video-label">Lien de la vidéo* <span>Input only
                        YouTube & Vimeo</span></label>
                <input name="video" type="text" placeholder="Input link" v-model="props.formValues.video">
                <span v-if="errors.video" class="error-message">{{ errors.video }}</span>
                <a href="javascript:void(0)" class="add-video"><i class="lni lni-plus"></i> Ajouter</a>
            </div>
        </div>
        </div>
        <div class="col-12">
            <div class="form-group mt-30">
                <label>Description*</label>
                <textarea name="message" placeholder="Input ad description" v-model="props.formValues.description"></textarea>
                <span v-if="errors.description" class="error-message">{{ errors.description }}</span>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="form-group">
                <label>Livraison*</label>
                <div class="selector-head">
                    <span class="arrow"><i class="lni lni-chevron-down"></i></span>
                    <select class="user-chosen-select" v-model="props.formValues.delivery_status">
                        <option value="">Selectionner</option>
                        <option value="Oui">Oui</option>
                        <option value="Non">Non</option>
                    </select>
                    <span v-if="errors.delivery_status" class="error-message">{{ errors.delivery_status }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="form-group">
                <label>Etat*</label>
                <div class="selector-head">
                    <span class="arrow"><i class="lni lni-chevron-down"></i></span>
                    <select class="user-chosen-select" v-model="props.formValues.state">
                        <option value="">Selectionner</option>
                        <option value="Neuf">Neuf</option>
                        <option value="Usé">Usé</option>
                    </select>
                    <span v-if="errors.state" class="error-message">{{ errors.state }}</span>
                </div>
            </div>
        </div>
        <div class="col-12">
          <div class="form-group button mb-0">
            <button type="submit" class="btn">Next Step</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import {ref, onMounted} from 'vue';
import axios from 'axios';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

const token = localStorage.getItem('token');

const clientHttp = axios.create({
baseURL: `${backendUrl}/api/`,
headers: {
  Accept: "application/json",
  Authorization: `Bearer ${token}`,
}
})

const props = defineProps(['formValues']);

const errors = ref({
title: '',
subcategory_id: '',
price_type: '',
price: '',
images: '',
video: '',
description: '',
delivery_status: '',
state: ''
});

const handleImageUpload = (event: { target: { files: any; }; }) => {
  const files = event.target.files;
  const images = [];
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    // Vous pouvez traiter chaque fichier ici, par exemple, l'ajouter à un tableau d'images
    images.push(file);
  }
  // Mettre à jour la propriété formValues.images avec le tableau d'images
  // eslint-disable-next-line vue/no-mutating-props
  props.formValues.images = images;
}

const getImageUrl = (image: Blob | MediaSource) => {
  return URL.createObjectURL(image);
}

const statusData = ref([]);

const status = async () => {
if (token) {
  try {
    const statusResponse = await clientHttp.get('subcategories');
    if (statusResponse.status === 200) {
      statusData.value = statusResponse.data;
    }
  } catch (error) {
    console.log(error);
  }
}
}
onMounted(status);

const userData = ref([]);

const user = async () => {
if (token) {
  try {
    const statusResponse = await clientHttp.get('adStatus');
    if (statusResponse.status === 200) {
      userData.value = statusResponse.data;
    }
  } catch (error) {
    console.log(error);
  }
}
}
onMounted(user);

const emit = defineEmits<{
(event: 'next-step'): void;
}>();

const validateAndProceed = () => {
  // Reset errors
  for (const key in errors.value) {
    errors.value[key] = '';
  }

  let valid = true;

  // Validate title
  if (!props.formValues.title.trim()) {
    errors.value.title = 'Le titre est requis.';
    valid = false;
  }

  // // Validate subcategory
  // if (!props.formValues.subcategory_id) {
  //   errors.value.subcategory_id = 'La catégorie est requise.';
  //   valid = false;
  // }

  // // Validate price type
  // if (!props.formValues.price_type) {
  //   errors.value.price_type = 'Le mode de prix est requis.';
  //   valid = false;
  // }

  // // Validate price if price type is "Fixe"
  // if (props.formValues.price_type === 'Fixe' && !props.formValues.price) {
  //   errors.value.price = 'Le prix est requis.';
  //   valid = false;
  // }

  // // Validate images
  // if (!props.formValues.images || props.formValues.images.length === 0) {
  //   errors.value.images = 'Veuillez sélectionner des images.';
  //   valid = false;
  // }

  // // Validate video link
  // // if (!props.formValues.video.trim()) {
  // //   errors.value.video = 'Le lien vidéo est requis.';
  // //   valid = false;
  // // }

  // // Validate description
  // if (!props.formValues.description.trim()) {
  //   errors.value.description = 'La description est requise.';
  //   valid = false;
  // }

  // // Validate delivery status
  // if (!props.formValues.delivery_status) {
  //   errors.value.delivery_status = 'Le statut de livraison est requis.';
  //   valid = false;
  // }

  // // Validate state
  // if (!props.formValues.state) {
  //   errors.value.state = 'L\'état est requis.';
  //   valid = false;
  // }

  if (valid) {
    emit('next-step');
  }
}
</script>

<style scoped>
.error-message {
color: red;
font-size: 0.875em;
}
</style>
