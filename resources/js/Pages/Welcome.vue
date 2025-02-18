<template>
    <main>
      <NavBar/>
      <section class="hero-area overlay">
          <div class="container">
              <div class="row">
                  <div class="col-lg-10 offset-lg-1 col-md-12 col-12">
                      <div class="hero-text text-center">
                          <!-- Start Hero Text -->
                          <div class="section-heading">
                              <h2 style = "color: white;" class="wow fadeInUp" data-wow-delay=".3s">Bienvenue sur <span>KAOLO ANNONCE</span></h2>
                              <p style = "color: white;" class="wow fadeInUp" data-wow-delay=".5s">Votre plateforme de publication d'annonces de toutes sortes <br> Achetez, vendez, développez votre business et denichez l'emploi de vos rêves</p>
                          </div>
                          <!-- End Search Form -->
                          <!-- Start Search Form -->
                          <LiveSearch/>
                          <!-- End Search Form -->
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- End Hero Area -->
     
      <!-- Start Categories Area -->
      <section class="categories">
          <div class="container">
              <div class="cat-inner">
                  <div class="row">
                      <div class="col-12 p-0">
                          <CategorySlider/>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- /End Categories Area -->
  
      <!-- Start Items Grid Area -->
      <section class="items-grid section custom-padding">
          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="section-title">
                          <h2 class="wow fadeInUp" data-wow-delay=".4s">Dernières annonces</h2>
                      </div>
                  </div>
              </div>
              <div class="single-head">
                  <div class="row">
                      <div v-for="ads in statusData" class="col-lg-4 col-md-6 col-12">
                          <!-- Start Single Grid -->
                          <div class="single-grid wow fadeInUp" data-wow-delay=".2s">
                              <div class="image">
                                  <RouterLink :to="{name: 'adShow', params: {id:ads.id}}"><img :src="getImageUrl(ads.images)" alt="#"></RouterLink>
                                  <div class="author">
                                      <div class="author-image">
                                          <a href=""><img :src="getAvatarUrl(ads.user.avatar)" :alt="ads.user.name" width="60px">
                                          <span>{{ ads.user.name }}</span></a>
                                      </div>
                                  </div>
                              </div>
                              <div class="content">
                                  <div class="top-content">
                                      <a href="javascript:void(0)" v-if="ads.subcategory" class="tag">{{ ads.subcategory.name }}</a>
                                      <h3 class="title">
                                          <a href="item-details.html">{{ ads.title }}</a>
                                      </h3>
                                      <p class="update-time">{{moment(ads.created_at).fromNow()}}</p>
  
                                      <ul class="info-list">
                                          <li><i class="lni lni-map-marker"></i>{{ ads.city }}</li>
                                      </ul>
                                  </div>
                                  <div class="bottom-content">
                                      <p class="price">Prix: <span>CFA {{ ads.price }}</span></p>
                                      <a href="javascript:void(0)" class="like"><i class="lni lni-heart"></i></a>
                                  </div>
                              </div>
                          </div>
                          <!-- End Single Grid -->
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-12">
                          <div class="pagination left">
                              <ul class="pagination-list">
                                  <li v-if="previousPage"><a @click="fetchPrevAds(previousPage)" href="javascript:void(0)"><i class="lni lni-chevron-left"></i></a></li>
                                  <li v-for="pageNumber in totalPages" :key="pageNumber" :class="{ active: currentPage === pageNumber }">
                                      <a @click="fetchPageAds(pageNumber)" href="javascript:void(0)">{{ pageNumber }}</a>
                                  </li>
                                  <li v-if="nextPage"><a @click="fetchNextAds(nextPage)" href="javascript:void(0)"><i class="lni lni-chevron-right"></i></a></li>
                              </ul>
                          </div>
                          
                      </div>
                  </div>
                  <!-- <div class="row">
                      <div class="col-12">
                          <a href="/adsList" class="btn">Voir plus</a>
                      </div>
                  </div> -->
              </div>
          </div>
      </section>
      <!-- /End Items Grid Area -->
  
      <!-- Start Why Choose Area -->
      <section class="why-choose section">
          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="section-title">
                          <h2 class="wow fadeInUp" data-wow-delay=".4s">Pourquoi KAOLO ANNONCE ?</h2>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                      <div class="choose-content">
                          <div class="row">
                              <div class="col-lg-4 col-md-6 col-12">
                                  <!-- Start Single List -->
                                  <div class="single-list wow fadeInUp" data-wow-delay=".4s">
                                      <i class="fa-solid fa-business-time"></i>
                                      <h4>Opportunités d'affaires</h4>
                                      <p>Vendez et achetez tout types d'articles dans les normes</p>
                                  </div>
                                  <!-- Start Single List -->
                              </div>
                              <div class="col-lg-4 col-md-6 col-12">
                                  <!-- Start Single List -->
                                  <div class="single-list wow fadeInUp" data-wow-delay=".2s">
                                      <i class="fa-regular fa-comment"></i>
                                      <h4>Interractions</h4>
                                      <p>Interagissez avec vos clients, vendeurs ou entreprises</p>
                                  </div>
                                  <!-- Start Single List -->
                              </div>
                              <div class="col-lg-4 col-md-6 col-12">
                                  <!-- Start Single List -->
                                  <div class="single-list wow fadeInUp" data-wow-delay=".6s">
                                      <i class="fa-solid fa-money-bill"></i>
                                      <h4>Booster votre chiffre d'affaire</h4>
                                  </div>
                                  <!-- Start Single List -->
                              </div>
                              <div class="col-lg-4 col-md-6 col-12">
                                  <!-- Start Single List -->
                                  <div class="single-list wow fadeInUp" data-wow-delay=".2s">
                                      <i class="fa-solid fa-eye"></i>
                                      <h4>Visibilité</h4>
                                      <p>Mettez en lumière vos compétences et votre entreprise</p>
                                  </div>
                                  <!-- Start Single List -->
                              </div>
                              <div class="col-lg-4 col-md-6 col-12">
                                  <!-- Start Single List -->
                                  <div class="single-list wow fadeInUp" data-wow-delay=".4s">
                                      <i class="lni lni-layout"></i>
                                      <h4>Construisez votre image</h4>
                                  
                                  </div>
                                  <!-- Start Single List -->
                              </div>
                              <div class="col-lg-4 col-md-6 col-12">
                                  <!-- Start Single List -->
                                  <div class="single-list wow fadeInUp" data-wow-delay=".6s">
                                      <i class="lni lni-laptop-phone"></i>
                                      <h4>Expérience utilisateur <br> optimale</h4>
                                  </div>
                                  <!-- Start Single List -->
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- /End Why Choose Area -->
  
      <ByCity/>
  
      <!-- Start Call Action Area -->
      <section class="call-action overlay section">
          <div class="container">
              <div class="row ">
                  <div class="col-lg-8 offset-lg-2 col-12">
                      <div class="inner">
                          <div class="content">
                              <h2 class="wow fadeInUp" data-wow-delay=".4s">Publier une annonce en quelques clics</h2>
                              <div class="button wow fadeInUp" data-wow-delay=".8s">
                                  <a href="/adCreate" class="btn">Publier une annonce</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- End Call Action Area -->
  
      <!-- Start Pricing Table Area -->
      <section class="pricing-table section">
          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="section-title">
                          <h2 class="wow fadeInUp" data-wow-delay=".4s">Pricing Plan</h2>
                          <p class="wow fadeInUp" data-wow-delay=".6s">There are many variations of passages of Lorem
                              Ipsum available, but the majority have suffered alteration in some form.</p>
                      </div>
                  </div>
              </div>
              <div v-if = "subscriptionData" class="row">
                  <div  v-for = "sub in subscriptionData" class="col-lg-4 col-md-6 col-12">
                      <!-- Single Table -->
                      <div class="single-table wow fadeInUp" data-wow-delay=".2s">
                          <!-- Table Head -->
                          <div class="table-head">
                              <div class="price">
                                  <h2 class="amount">${{ sub.price }}<span class="duration">/ Month</span></h2>
                              </div>
                              <h4 class="title">{{ sub.name }}</h4>
                          </div>
                          <!-- End Table Head -->
                          <!-- Table List -->
                          <ul class="table-list">
                              <li>{{ sub.duration }}</li>
                              <li>{{ sub.description }}</li>
                              <li>Image Gallery</li>
                              <li>30 Days Availablity</li>
                              <li>Non-Featured</li>
                              <li>Business Tagline</li>
                          </ul>
                          <!-- End Table List -->
                          <!-- Table Bottom -->
                              <div class="button">
                                  <button @click="activateSubscription(sub.id)" class="btn">Activate</button>                            </div>
                          <!-- End Table Bottom -->
                      </div>
                      <!-- End Single Table-->
                  </div>
                  <!-- <div class="col-lg-4 col-md-6 col-12">
                      
                      <div class="single-table wow fadeInUp" data-wow-delay=".4s">
                          
                          <div class="table-head">
                              <div class="price">
                                  <h2 class="amount">$59<span class="duration">/ Month</span></h2>
                              </div>
                              <h4 class="title">Standard</h4>
                          </div>
                          
                          
                          <ul class="table-list">
                              <li>One Listing</li>
                              <li>Contact Display</li>
                              <li>Image Gallery</li>
                              <li>60 Days Availablity</li>
                              <li>Non-Featured</li>
                              <li>Business Tagline</li>
                          </ul>
                          
                          <div class="button">
                              <a class="btn" href="javascript:void(0)">Select Plan</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-12">
                      <div class="single-table wow fadeInUp" data-wow-delay=".6s">
                          <div class="table-head">
                              <div class="price">
                                  <h2 class="amount">$99<span class="duration">/ Month</span></h2>
                              </div>
                              <h4 class="title">Premium</h4>
                          </div>
                          <ul class="table-list">
                              <li>One Listing</li>
                              <li>Contact Display</li>
                              <li>Image Gallery</li>
                              <li>90 Days Availablity</li>
                              <li>Non-Featured</li>
                              <li>Business Tagline</li>
                          </ul>
                          <div class="button">
                              <a class="btn" href="javascript:void(0)">Select Plan</a>
                          </div>
                      </div>
                  </div> -->
              </div>
          </div>
      </section>
      <!--/ End Pricing Table Area -->
  
      <!-- Start How Works Area -->
      <section class="how-works section" style="background-color: #fff;">
          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="section-title">
                          <h2 class="wow fadeInUp" data-wow-delay=".4s">How it Works</h2>
                          <p class="wow fadeInUp" data-wow-delay=".6s">There are many variations of passages of Lorem
                              Ipsum available, but the majority have suffered alteration in some form.</p>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-4 col-md-4 col-12">
                      <!-- Start Single Work -->
                      <div class="single-work wow fadeInUp" data-wow-delay=".2s">
                          <span class="serial">01</span>
                          <h3>Create Account</h3>
                          <p>Lorem ipsum dolor sit amet constur adipisicing sed do eiusmod tempor incididunt labore.</p>
                      </div>
                      <!-- End Single Work -->
                  </div>
                  <div class="col-lg-4 col-md-4 col-12">
                      <!-- Start Single Work -->
                      <div class="single-work wow fadeInUp" data-wow-delay=".4s">
                          <span class="serial">02</span>
                          <h3>Post Your Ads</h3>
                          <p>Lorem ipsum dolor sit amet constur adipisicing sed do eiusmod tempor incididunt labore.</p>
                      </div>
                      <!-- End Single Work -->
                  </div>
                  <div class="col-lg-4 col-md-4 col-12">
                      <!-- Start Single Work -->
                      <div class="single-work wow fadeInUp" data-wow-delay=".6s">
                          <span class="serial">03</span>
                          <h3>Sell Your Item</h3>
                          <p>Lorem ipsum dolor sit amet constur adipisicing sed do eiusmod tempor incididunt labore.</p>
                      </div>
                      <!-- End Single Work -->
                  </div>
              </div>
          </div>
      </section>
      <!-- End How Works Area -->
  
      <!-- Start Newsletter Area -->
      <!-- <div class="newsletter section">
          <div class="container">
              <div class="inner-content">
                  <div class="row align-items-center">
                      <div class="col-lg-6 col-md-6 col-12">
                          <div class="title">
                              <i class="lni lni-alarm"></i>
                              <h2>Newsletter</h2>
                              <p>We don't send spam so don't worry.</p>
                          </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-12">
                          <div class="form">
                              <form action="#" method="get" target="_blank" class="newsletter-form">
                                  <input name="EMAIL" placeholder="Your email address" type="email">
                                  <div class="button">
                                      <button class="btn">Subscribe<span class="dir-part"></span></button>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div> -->
      <!-- End Newsletter Area -->
          <!-- Start Footer Top -->
      <!-- <FooterComponent/> -->
    </main>
  </template>
  
  <script setup>
  import NavBar from '../components/NavBar.vue'
  // import FooterComponent from '@/components/FooterComponent.vue';
  import CategorySlider from '@/components/CategorySlider.vue';
  import LiveSearch from '@/components/LiveSearch.vue';
  import ByCity from '@/components/ByCity.vue'
  import moment from 'moment'
  import { ref, onMounted } from 'vue';
  import WOW from 'wowjs';
  import {useAds} from '../components/composables/adsApi';
  import axios from 'axios';
  
  const backendUrl = import.meta.env.VITE_BACKEND_URL;
  const imageUrl = import.meta.env.VITE_IMAGE_URL
  
  const { nextPage, previousPage, currentPage, totalPages, statusData, fetchPageAds, fetchNextAds, fetchPrevAds, status } = useAds();
  
  // Initialisation de WOW.js après le chargement du composant
//   onMounted(() => {
//       new WOW().init();
//       fetchPrevAds();
//       fetchNextAds();
//       fetchPageAds();
//       status();
//   });
  
  const token = localStorage.getItem('token');
  const clientHttp = axios.create({
      baseURL: `${backendUrl}/api/`,
      headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
      }
  });
  
  const subscriptionData = ref([]);
  
  // Fonction pour récupérer les données d'abonnement depuis l'API
  const subscription = async () => {
      if (token) {
          try {
              const subscriptionResponse = await clientHttp.get('subscriptions-list');
              if (subscriptionResponse.status === 200) {
                  subscriptionData.value = subscriptionResponse.data;
              }
          } catch (error) {
              console.log(error);
          }
      }
  };
  
  // Appeler la fonction d'abonnement lors du chargement du composant
  subscription();
  
  // Fonction pour activer un abonnement
  async function activateSubscription(id) {
      try {
          const activate = await clientHttp.put(`activateSubscription/${id}`);
          window.location.reload();
      } catch (error) {
          console.log(error);
      }
  }
  
  // Fonction pour obtenir l'URL de l'image
  const getImageUrl = (images) => {
      if (images && images.length > 0) {
          return `${imageUrl}/storage/` + images[0].path;
      }
      return ''; // Ou une image par défaut si aucune image n'est disponible
  };
  
  const getAvatarUrl = (avatar) =>{
      // Implémentez votre fonction getImageUrl ici
      // Par exemple, si les images ont des chemins relatifs, vous pouvez les préfixer avec une URL de base
      return `${imageUrl}/storage/${avatar}`;
  }
  
  // Définir d'autres fonctions ou variables nécessaires ici
  
  const statuData = ref([])
  
      const statu = async ()=>{
      
          try{
              // const email = router.currentRoute.params.email;
              const statusResponse = await clientHttp.get('profile')
        console.log(statusResponse);
        
              if(statusResponse.status === 200){
                  statuData.value = statusResponse.data.message
              }
          } catch(error){
              console.log(error);
              
          }
      
  }
  onMounted(() => {
          // Appeler fetchStatus au démarrage
          statu();
  
          // Actualiser les données toutes les X secondes (par exemple, toutes les 5 secondes)
          // setInterval(status, 2000);
      });
  </script>
  
  
  
  