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
                            
                            <button
                                class="navbar-toggler mobile-menu-btn"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                                @click="toggleMenu"
                                :class="{ active: isActive }"
                            >
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item" v-for="(link, index) in links" :key="index">
                                    <router-link aria-label="Toggle navigation" :to="link.url" :class="{ 'nav-link': true }">{{ link.text }}</router-link>
                                    </li>
                                </ul>
                                <div class="button header-button">
                                    <div id="drop" class="dropdown">
                                        <button class="btn btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{statuData.name}}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><RouterLink class="dropdown-item" :to="{name: 'dashboard'}">Dashboard</RouterLink></li>
                                            <li><RouterLink class="dropdown-item" :to="{name: 'myAds'}">Mes annonces</RouterLink></li>
                                            <li><span class="dropdown-item">{{ statusData.status }}</span></li>
                                            <li>
                                                <form @submit.prevent = "logout" action="">
                                                    <button class="dropdown-item" href="">Se déconnecter</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                        </nav> <!-- navbar -->
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    <!-- End Header Area -->
</template>



<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
// import router from '@/router';

// Variable réactive pour gérer l'état actif du bouton
const isActive = ref(false);

// Fonction pour basculer l'état du bouton
const toggleMenu = () => {
  isActive.value = !isActive.value;
};

const backendUrl = import.meta.env.VITE_BACKEND_URL;

const token = localStorage.getItem('token');
const isLoggedIn = ref(!!token);

const clientHttp = axios.create({
    baseURL: `${backendUrl}/api/`,
    headers: {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
    }
});

const statusData = ref([]);
const statuData = ref([]);

const status = async () => {
    if (token) {
        try {
            const statusResponse = await clientHttp.get('showSubscription');
            console.log(statusResponse);
            if (statusResponse.status === 200) {
                statusData.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }
};

const profile = async () => {
    if (token) {
        try {
            const statusResponse = await clientHttp.get('profile');
            console.log(statusResponse);
            if (statusResponse.status === 200) {
                statuData.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }
};

onMounted(() => {
    status();
    profile();
});

async function logout() {
    try {
        const user = await clientHttp.post('logout');
        console.log(user);
        localStorage.removeItem('token');
        router.replace('/login');
    } catch (error) {
        console.log(error);
    }
}

const links = ref([
    { text: 'Annonces', url: '/adsList' },
    { text: 'Catégories', url: '/categoryList' },
    { text: 'Nouvelle Annonce', url: '/adCreate' }
]);
// const route = useRoute();

// const isActiveLink = (url) => {
//     return route.path === url;
// };
</script>

