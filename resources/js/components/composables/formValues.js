import { reactive } from "vue";
import { onMounted } from 'vue'
import axios from 'axios';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

const token = localStorage.getItem('token');

const clientHttp = axios.create(
    {
        baseURL: `${backendUrl}/api/`,
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        }
    }
)

// const statusData = ref([])

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


const activeSubscription = async () => {
    if (token){
        try {
            const statusResponse = await clientHttp.get('getSubscriptionId');
            
            // Vérifiez si la réponse est réussie et si elle contient des données
            if (statusResponse.status === 200 && statusResponse.data.id) {
                // Retournez uniquement l'ID de l'utilisateur
                return statusResponse.data;
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


export default function useForm(){
    const values = reactive({
        title:'',
        description:'',
        department:'',
        city:'',
        price:'',
        delivery_status:'',
        state:'',
        images:'',
        price_type:'',
        phone:'',
        subcategory_id:'',
        user_id: status(),
        user_subscription_id: activeSubscription(),
    });

    return{
        values,
    }
}




