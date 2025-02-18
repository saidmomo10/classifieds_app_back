<template>
    <!-- Start Header Area -->
    <header class="header navbar-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="nav-inner">
                        <nav class="navbar navbar-expand-lg">
                            <router-link v-if="!isLoggedIn" to="/guest"><img src="../assets/images/logo/logos.png" alt="Logo" width="80px"></router-link>
    
                            <!-- Lien vers la page d'accueil pour les utilisateurs -->
                            <router-link v-if="isLoggedIn" to="/"><img src="../assets/images/logo/logos.png" alt="Logo" width="80px"></router-link>
                            
                            <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item" v-for="(link, index) in links" :key="index">
                                    <router-link aria-label="Toggle navigation" :to="link.url" :class="{ 'nav-link': true, 'active': isActiveLink(link.url) }">{{ link.text }}</router-link>
                                    </li>
                                </ul>
                                <div class="button header-button">
                                    <a href="adCreate" class="btn">Publier une annonce</a>
                                </div>

                                <div class="login-button">
                                    <ul>
                                        <li>
                                            <a href="login"><i class="lni lni-enter"></i> Se connecter</a>
                                        </li>
                                        <li>
                                            <a href="signup"><i class="lni lni-user"></i> Register</a>
                                        </li>
                                    </ul> 
                                </div>
                                <!-- <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item">
                                        <a class=" active dd-menu collapsed" href="javascript:void(0)"
                                            data-bs-toggle="collapse" data-bs-target="#submenu-1-1"
                                            aria-controls="navbarSupportedContent" aria-expanded="false"
                                            aria-label="Toggle navigation">Accueil</a>
                                        <ul class="sub-menu collapse" id="submenu-1-1">
                                            <li class="nav-item active"><a href="/">Home Default</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/category" aria-label="Toggle navigation">Categories</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class=" dd-menu collapsed" href="javascript:void(0)"
                                            data-bs-toggle="collapse" data-bs-target="#submenu-1-3"
                                            aria-controls="navbarSupportedContent" aria-expanded="false"
                                            aria-label="Toggle navigation">Annonces</a>
                                        <ul class="sub-menu collapse" id="submenu-1-3">
                                            <li class="nav-item"><a href="javascript:void(0)">Ad Grid</a></li>
                                            <li class="nav-item"><a href="javascript:void(0)">Ad Listing</a></li>
                                            <li class="nav-item"><a href="javascript:void(0)">Ad Details</a></li>
                                        </ul>
                                    </li>
                                    
                                </ul> -->
                            </div> 
                            
                        </nav> 
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    <!-- End Header Area -->
</template>






<script setup lang="ts">
import {ref, onMounted} from 'vue'
import { useRoute } from 'vue-router';
import axios from 'axios';
import router from '@/router';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

const token = localStorage.getItem('token');
const isLoggedIn = ref(!!token);


const clientHttp = axios.create(
    {
        baseURL: `${backendUrl}/api/`,
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        }
    }
)


const statusData = ref([])

const status = async ()=>{
    if (token){
        try{
            // const email = router.currentRoute.params.email;
            const statusResponse = await clientHttp.get('showSubscription')
      console.log(statusResponse);
      
            if(statusResponse.status === 200){
                statusData.value = statusResponse.data
            }
        } catch(error){
            console.log(error);
            
        }
    }
}
onMounted(() => {
        status();
    });


    

const links = [
{ text: 'Annonces', url: '/adsList' },
  { text: 'Nouvelle Annonce', url: '/adCreate' },
];

const route = useRoute();

const isActiveLink = (url: string) => {
    return route.path === url;
};
</script>
