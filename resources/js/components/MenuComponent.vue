<!-- <template>
    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
        <ul>
            <li v-for="(link, index) in links" :key="index">
            <router-link  :to="link.url" :class="{ 'nav-link': true, 'active': isActiveLink(link.url) }">{{ link.text }}</router-link></li>
        </ul>
    </div>
</template> -->

<script lang="ts">
// import { useRouter } from 'vue-router';

// export default {
//   data() {
//     return {
//       links: [
//         { text: 'Publier une annonce', url: '/adCreate' },
//         { text: 'Mes Annonces', url: '/myAds' },
//         { text: 'Mettre à jour Profile', url: '/profileEdit' },
//         { text: 'Contact', url: '/contact' }
//       ]
//     };
//   },
//   methods: {
//     isActiveLink(url) {
//       const currentRoute = useRouter().currentRoute;
//       return currentRoute.path === url;
//     }
//   }
// };
</script>