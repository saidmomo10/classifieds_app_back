<template>
    <div>
      <form action="" @submit.prevent="submitStepThree">
        <section class="item-details section">
          <div class="container" >
            <div class="top-area">
              <div class="row">
                  <div class="col-lg-6 col-md-12 col-12">
                      <div class="product-images">
                          <main id="gallery">
                            <div class="images">
                              <div v-for="image in props.formValues.images" :key="image.name">
                                <img :src="getImageUrl(image)" alt="Selected image" style="max-width: 200px; max-height: 200px; margin-bottom: 10px;">
                              </div>
                            </div>
                          </main>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-12 col-12">
                      <div class="product-info">
                          <h2 class="title" v-text="props.formValues.title"></h2>
                          <p class="location"><i class="lni lni-map-marker"></i>{{ props.formValues.department }}, {{ props.formValues.city }}</p>
                          <h3 v-if="props.formValues.price_type == 'Fixe'" class="price">{{ props.formValues.price }} CFA</h3>
                          <h3 v-if="props.formValues.price_type == 'Débatable'" class="price" v-text="props.formValues.price_type"></h3>
                          
                          <div class="list-info">
                              <ul>
                                  <li><span>Condition:</span>{{ props.formValues.state }}</li>
                                  <li><span>Livraison:</span>{{ props.formValues.delivery_status }}</li>
                              </ul>
                          </div>
                          <div class="contact-info">
                              <ul>
                                  <li>
                                      <a href="#" class="call">
                                          <i class="lni lni-phone-set"></i>
                                          {{ props.formValues.phone }}
                                          <span>Call & Get more info</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </section>
        <div class="col-12">
          <div class="form-group button mb-0">
            <button id="bt" @click="$emit('previous-step')" type="button" class="btn alt-btn">Retour</button>
            <button id="bt" type="submit" class="btn alt-btn">Publier</button>
          </div>
        </div>
      </form>
    </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import axios from 'axios';
  import router from '@/router';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';

  const backendUrl = import.meta.env.VITE_BACKEND_URL;

  const props = defineProps(['formValues']);
  console.log(props.formValues)

  const getImageUrl = (image: Blob | MediaSource) => {
    return URL.createObjectURL(image);
  }

  const token = localStorage.getItem('token');

  const clientHttp = axios.create(
      {
          baseURL: `${backendUrl}/api/`,
          headers: {
              'Content-Type': "multipart/form-data",
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
          }
      }
  )


  const submitStepThree = async () => {
    try {
      // Envoyer les données de l'étape trois une fois soumises
      // Vous pouvez également ajouter des validations ici
      // et déclencher l'événement resetForm une fois le formulaire soumis avec succès
      const response = await clientHttp.post('ads', props.formValues);
      toast.success(response.data, {
          autoClose: 1000,
          });
      console.log(response.data);
      // router.replace('/adsList')
    } catch (error) {
      toast.error(error.response.data.message, {
          autoClose: 1000,
          });
      console.error('Error submitting step three:', error);
    }
  };

</script>

<style scoped>
  #bt {
    /* text-transform: ; */
    margin-bottom: 5px;
    font-size: 15px;
    font-weight: 500;
    padding: 15px 30px;
    background-color: #5830E0;
    color: #fff;
    border: none;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    border-radius: 4px;
  }
</style>