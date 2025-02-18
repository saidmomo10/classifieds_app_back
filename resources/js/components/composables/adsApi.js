import {ref} from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import router from '@/router';
// import { useRoute } from 'vue-router';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

// import { useHttpStore } from '@/stores/http'

export function useAds(){
    const token = localStorage.getItem('token');
    const user = localStorage.getItem('userId')

    const clientHttp = axios.create(
        {
            baseURL: `${backendUrl}/api/`,
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            }
        }
    )

    const status = async () => {
        if (token){
            try {
                const statusResponse = await clientHttp.get('profile');
                
                // Vérifiez si la réponse est réussie et si elle contient des données
                if (statusResponse.status === 200 && statusResponse.data.id) {
                    // Retournez uniquement l'ID de l'utilisateur
                    return statusResponse.data.id;
                } else {
                    // Si la réponse est invalide ou ne contient pas l'ID, retournez null ou une valeur par défaut
                    return null;
                }
            } catch(error) {
                console.log(error);
                // En cas d'erreur, retournez null ou une valeur par défaut
                return null;
            }
        } else {
            // Si le token est invalide ou non défini, retournez null ou une valeur par défaut
            return null;
        }
    }
    
    const statusData = ref([])
    const nextPage = ref("")
    const previousPage = ref("")
    const currentPage = ref(1); // Numéro de page actuel
    const totalPages = ref(1); // Nombre total de pages

    // // Fonction pour charger les annonces de la page spécifiée
    const fetchPageAds = async (pageNumber) => {
    try {
        const statusResponse = await clientHttp.get(`ads?page=${pageNumber}`);
        console.log(statusResponse);

        if (statusResponse.status === 200) {
        const pagination = statusResponse.data;
        statusData.value = pagination.data;
        nextPage.value = pagination.next_page_url;
        previousPage.value = pagination.prev_page_url;
        currentPage.value = pageNumber;
        }
    } catch (error) {
        console.log(error);
    }
    }

    // // Fonction pour charger les annonces suivantes
    const fetchNextAds = async (url) => {
    try {
        const statusResponse = await clientHttp.get(url);
        console.log(statusResponse);

        if (statusResponse.status === 200) {
        const pagination = statusResponse.data;
        statusData.value = pagination.data;
        nextPage.value = pagination.next_page_url;
        previousPage.value = pagination.prev_page_url;
        currentPage.value++;
        }
    } catch (error) {
        // console.log(error);
    }
    }

    // Fonction pour charger les annonces précédentes
    const fetchPrevAds = async (url) => {
    try {
        const statusResponse = await clientHttp.get(url);
        // console.log(statusResponse);

        if (statusResponse.status === 200) {
        const pagination = statusResponse.data;
        statusData.value = pagination.data;
        nextPage.value = pagination.next_page_url;
        previousPage.value = pagination.prev_page_url;
        currentPage.value--;
        }
    } catch (error) {
        // console.log(error);
    }
    }

    // Chargement initial des annonces (première page)
    // const getUserId = async () => {
    //     try {
    //         const statusResponse = await clientHttp.get('ads');
    //         console.log(statusResponse);

    //         if (statusResponse.status === 200) {
    //         const pagination = statusResponse.data;
    //         statusData.value = pagination.data;
    //         // filteredAds.value = statusData.value
    //         nextPage.value = pagination.next_page_url;
    //         previousPage.value = pagination.prev_page_url;
    //         totalPages.value = pagination.last_page; // Définit le nombre total de pages
    //         }
    //     } catch (error) {
    //         console.log(error);
    //     }
    // }

    const editData = ref([])
    const comment = ref([])
    //Show
    const showAd = async (id)=>{
        if (token){
            try{
                // const email = router.currentRoute.params.email;
                const statusResponse = await clientHttp.get(`ads/${id}`)
                console.log(statusResponse.data);
        
                if(statusResponse.status === 200){
                    editData.value = statusResponse.data.ad
                    comment.value = statusResponse.data.comment

                }
            } catch(error){
                console.log(error);
                
            }
        }
    }

    //Update

    
    const UpdateAd = async (id) => {
        try {
          // Envoyer les données de l'étape trois une fois soumises
          // Vous pouvez également ajouter des validations ici
          // et déclencher l'événement resetForm une fois le formulaire soumis avec succès
          const response = await clientHttp.put(`ads/${id}`, editData.value);
          toast.success(response.data)
          console.log(response.data);
          // router.replace('/adsList')
        } catch (error) {
          toast.error(error.response.data.message, {
              autoClose: 1000,
              });
          console.error('Error submitting', error);
        }
      };



    async function updateStatus(id){
        try {
            const response = await clientHttp.patch(`ads/${id}/status`);
            console.log(response.data);
            toast.success(response.data, {
                autoClose: 1000,
                })
            setTimeout(() => {
                router.push('/adsList');
            }, 2000);
        }catch(error){
                // emailError.value = 'L\'adresse email doit contenir le symbole "@".';
                // passwordError.value = 'Le mot de passe doit avoir au moins 8 caractères.';
                toast.error(error.response.data.message)
                console.log(error);
        }
    }

    const deleteAd = async (id) =>{
        
            const deleteResponse = await clientHttp.delete(`ads/${id}`)
            console.log(deleteResponse);
            // toast.success(deleteResponse.data, {
            //     autoClose: 1000,
            //     })
            // setTimeout(() => {
            //     window.location.reload();
            // }, 2000);
            
        
        // router.push('/adsList')
    }
    
    // const formData = ref({
    //     comment: '',
    //     ad_id: '', // Initialiser ad_id à null
    //     user_id: user,
    // });
    
    const formData = ref({
        comment: '',
        ad_id: '', // Initialiser ad_id à null
        user_id: user,
    })
    async function addComment(id){
        formData.value.ad_id = id

        try {
            const comments = await clientHttp.post('comments',formData.value);
            console.log(comments.data);
            formData.value.comment = "";
            // window.location.reload();
        }catch(error){
                // emailError.value = 'L\'adresse email doit contenir le symbole "@".';
                // passwordError.value = 'Le mot de passe doit avoir au moins 8 caractères.';
                console.log(error);
        }
    }

    const commentData = ref([]);
    const getComment = async () => {
        try {
            const statusResponse = await clientHttp.get('comments');
            console.log(statusResponse);

            if (statusResponse.status === 200) {
                commentData.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }


    const userData = ref([]);
    const getUser = async () => {
        try {
            const statusResponse = await clientHttp.get('getAdUser');
            console.log(statusResponse);

            if (statusResponse.status === 200) {
                userData.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }

    const myAdsData = ref([]);
    const myAds = async () => {
        try {
            const statusResponse = await clientHttp.get('myAds');
            console.log(statusResponse);

            if (statusResponse.status === 200) {
                myAdsData.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }

    //Post


    // const searchQuery = ref('');
    // const ads = ref([]);
    // const filteredAds = ref([])

    // const getAds = async ()=>{
    //     if (token){
    //         try{
    //             // const email = router.currentRoute.params.email;
    //             const getResponse = await clientHttp.get('livesearch')
    //     console.log(getResponse);
        
    //             if(getResponse.status === 200){
    //                 ads.value = getResponse.data
    //                 filteredAds.value = ads.value
    //             }
    //         } catch(error){
    //             console.log(error);
                
    //         }
    //     }
    // }    

    // const filter = (query) =>{
    //     if(query == ''){
    //         filteredAds.value = ads.value
    //         return 
    //     }
    //     filteredAds.value = ads.value.filter(ad => {
    //         return (
    //             ad.title.toLowerCase().includes(query.toLowerCase()) ||
    //             ad.city.toLowerCase().includes(query.toLowerCase())
    //         );
    //     })
    // }



    // const selectedItem = async (id)=>{
    //     if (token){
    //         try{
    //             // const email = router.currentRoute.params.email;
    //             const statusResponse = await clientHttp.get(`ad`)
    //     console.log(statusResponse);
        
    //             if(statusResponse.status === 200){
    //                 Ads.value = statusResponse.data
    //             }
    //         } catch(error){
    //             console.log(error);
                
    //         }
    //     }
    // }    

    return{
        
        showAd,
        nextPage,
        statusData,
        previousPage,
        currentPage,
        totalPages,
        editData,
        comment,
        userData,
        myAdsData,
        formData,
        commentData,
        addComment,
        getComment,
        UpdateAd,
        deleteAd,
        myAds,
        getUser,
        updateStatus,
        fetchPageAds,
        fetchNextAds,
        fetchPrevAds,
        status,
        
        // // getResults,
        // // selectedItem
    }
}